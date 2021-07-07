<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\BaseResourceController;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\Store\Store;
use App\Models\Store\Product;
use App\Models\Store\Order;
use App\Models\Store\OrderItem;
use App\Models\Store\Cart;
use App\Models\Store\CartItem;
use App\Services\Payments\Pagarme;
use App\Models\Location\Address;
use App\Services\Shipping\ShippingService;

use App\Services\AppMessage;
use App\Services\MailService;

class CheckoutController extends BaseResourceController
{
    public function __construct()
    {
        $this->model_name = '';
        $this->route      = '/checkout';

        // List
        $this->list_columns = [];
        $this->searchable   = [];
    }

    // OTHER PUBLIC METHODS =======================================================================
    /**
     * Shows the Checkout page
     *
     * @param Request $request
     * @return Response
     */
    public function checkout( Request $request )
    {
        // Get cart ID
        $cart_id = $request->cart;

        // Check for logged user
        if( auth()->guest() )
        {
            return redirect('/entrar?t=checkout&cart='. $cart_id);
        }

        // Get User & Cart
        $user = auth()->user();
        $cart = get_cart();

        if( ! empty($cart->address_id) )
        {
            // Load Address
            $zipcode = Address::where('id', '=', $cart->address_id)->value('zipcode');
        }
        else
        {
            // Get first Shipping User's Address
            $address = $user->addresses()->where('type', '=', 'shipping')->first();
            $zipcode = ( $address ? $address->zipcode : null );
        }

        if( $zipcode )
        {
            // Calculate shipping
            ShippingService::calculate( $zipcode );
        }

        // Load Cart
        $cart = load_cart( $cart_id );

        // Build view data
        $data = $this->commonViewData( 'Keewe | Finalizar Compra', 'Finalizar Compra' );

        $data['cart'] = $cart;
        $data['user'] = $user;
        $data['ekey'] = Pagarme::getEncKey();
        $data['cart_id'] = $cart_id;
        $data['addresses'] = $user->shippingAddresses();

        //dd( $data );

        return $this->page( 'store.checkout', $data );
    }

    /**
     * Shows the Checkout Complete page
     *
     * @param Request $request
     * @return Response
     */
    public function checkoutCompleted( Request $request )
    {
        // Order
        $order = Order::find( $request->order );

        if( ! $order )
            return redirect('/');

        // Build view data
        $data = $this->commonViewData( 'Keewe | Pedido finalizado', 'Pedido finalizado' );

        $data['order'] = $order;

        return $this->page( 'store.checkout-completed', $data );
    }

    /**
     * Process the Checkout
     *
     * @param Request $request
     * @return Response
     */
    public function processCheckout( Request $request )
    {
        //dd( $request->all() );
        $rules = [
            'cart_id'    => 'bail|required|numeric',
            'pay_method' => 'bail|required|string|in:credit_card',
            'card_hash'  => 'bail|required|string',
            'doc_cpf'    => 'bail|required|cpf',
            'phone'      => 'bail|required'
        ];

        // Validates with this rules
        $this->validateInput( $request->all(), $rules );

        // Get vars
        $cart_id    = $request->cart_id;
        $pay_method = $request->pay_method;
        //$card_hash  = $request->card_hash;
        //$doc_cpf    = $request->doc_cpf;
        $user       = auth()->user();

        // Get Transaction Data
        $data = $this->buildTransactionData( $request );

        //dd( $data );
        //dd( json_encode($data) );

        // Start Database transact
        //\DB::beginTransaction();

        try
        {
            // Creates the Order
            $order = $this->saveOrder( $data, $user, $pay_method );

            // Sends the transaction
            $res = Pagarme::addTransaction( $data );

            //dd( $res );

			/*
            if( $res->success === false )
            {
                \DB::rollBack();

                return $this->responseError( $res->error );
            }
			*/

            // Updates Order
            $this->updateOrderWithGatewayData( $order, $res );
			
			if( $res->success === true )
			{
				// Array to save stores for send email
                $vendors = [];

				// Update Products Inventory
				foreach( $order->items as $item )
				{
					$product = Product::where('id', '=', $item['product_id'])->first();

					$product->quantity = (int) $product->quantity - (int) $item->quantity;
					$product->save();

                    $vendors[] = (object) [
                        'name' => $product->shop->owner->fullname,
                        'mail' => $product->shop->owner->username
                    ];
				}
				
				// Send Mail to Customer
				MailService::sendOrderToCustomer( $user->fullname, $user->username, $order, $order->items );

                // Send Mail to Vendors
                foreach( $vendors as $seller )
                {
                    MailService::sendOrderToVendor( $seller->name, $seller->mail, $order );
                }

                // Deletes the Carts
				$this->deleteCart( $cart_id );
			}

            //\DB::commit();

            // Data
            $data = [
                'next_page' => url('/checkout/completed?order=' . $order->id)
            ];

            return $this->responseSuccess( 'Seu pedido foi concluído com sucesso!', $data );
        }
        catch( \Exception $exc )
        {
            //\DB::rollBack();

            return $this->responseError( $exc->getMessage() );
        }
    }

    // PROTECTED METHODS ==========================================================================
    /**
     * Updates the Order Entity with data from Payment Gateway
     *
     * @param Order $order
     * @param stdClass $gateway_data
     * @return void
     */
    protected function updateOrderWithGatewayData( &$order, $gateway_data )
    {
		if( $gateway_data->success === true )
		{
			// Order successfully placed
			$transaction = $gateway_data->transaction;
			
			$order->authorized_amount      = floatval( (int) $transaction->authorized_amount / 100 );
			$order->paid_amount            = floatval( (int) $transaction->paid_amount / 100 );
			$order->refunded_amount        = floatval( (int) $transaction->refunded_amount / 100 );
			$order->boleto_url             = $transaction->boleto_url;
			$order->boleto_barcode         = $transaction->boleto_barcode;
			$order->boleto_expiration_date = $transaction->boleto_expiration_date;
			$order->acquirer_name          = $transaction->acquirer_name;
			$order->acquirer_id            = $transaction->acquirer_id;
			$order->acquirer_response_code = $transaction->acquirer_response_code;
			$order->authorization_code     = $transaction->authorization_code;
			$order->tid                    = $transaction->tid;
			$order->nsu                    = $transaction->nsu;
			$order->response_data          = json_encode( $transaction );
			$order->status                 = $transaction->status;
			$order->refuse_reason          = $transaction->refuse_reason;
			$order->status_reason          = $transaction->status_reason;
		}
		else
		{
			$order->response_data          = json_encode( $gateway_data );
			$order->status                 = 'refused';
			$order->refuse_reason          = 'internal_error';
			$order->status_reason          = 'internal_error';
		}
		
		$order->save();
    }

    /**
     * Deletes the Cart and Cart Items
     *
     * @param int $cart_id
     * @return void
     */
    protected function deleteCart( $cart_id )
    {
        CartItem::where('cart_id', '=', $cart_id)->delete();
        Cart::where('id', '=', $cart_id)->delete();
    }

    /**
     * Builds and Returns the Transaction array
     *
     * @param Request $request
     * @return array
     */
    protected function buildTransactionData( Request $request )
    {
        // Get vars
        $cart_id    = $request->cart_id;
        $pay_method = $request->pay_method;
        $card_hash  = $request->card_hash;
        $doc_cpf    = $request->doc_cpf;
        $phone      = $request->phone;
        $user       = auth()->user();

        // Get cart
        $cart = get_cart( $cart_id );
        $days = 3;

        foreach( $cart->items as $item )
        {
            if( $item->days > $days )
                $days = $item->days;
        }

        //dd( $cart );

        // Address in Cart
        $addr_id = $cart->address_id;

        // Calculates estimated delivery date
        $delivery = Carbon::now()->addDays( $days );

        // Load cart with products
        $cart = load_cart( $cart_id );

        // Get split rules
        $split_rules = $this->buildSplitRules( $cart );
        $amount = 0;

        foreach( $split_rules as $key => $rule )
        {
            $amount += intval( $rule['amount'] );
        }

        //dd( $amount );

        // Build transaction data
        $data = [
            'amount'              => $amount,
            'payment_method'      => $pay_method,
            'card_hash'           => $card_hash,
            'boleto_instructions' => 'Sr. caixa, não receber após o vencimento.',
            'boleto_rules'        => [ 'strict_expiration_date' ],
            'async'               => false,
            'capture'             => true,
            'customer'            => $this->buildCustomerData( $user, $doc_cpf, $phone ),
            'billing'             => $this->buildBillingData( $user ),
            'shipping'            => $this->buildShippingData( $user, $cart->shipping, $delivery, $addr_id ),
            'items'               => $this->buildItemsData( $cart ),
            'split_rules'         => $split_rules,
        ];

        return $data;
    }

    /**
     * Returns the User's data array
     *
     * @param User $user
     * @return array
     */
    protected function buildCustomerData( $user, $doc, $phone )
    {
        return [
            'external_id' => (string) $user->id,
            'name'        => $user->fullname,
            'email'       => $user->username,
            'type'        => 'individual',
            'country'     => 'br',
            'documents'   => [
                [
                    'type'   => 'cpf',
                    'number' => preg_replace('/[^0-9]/', '', $doc )
                ]
            ],
            'phone_numbers' => [ '+55' . preg_replace('/[^0-9]/', '', $phone ) ]
        ];
    }

    /**
     * Builds and Returns the Billing Data
     *
     * @param User $user
     * @return array
     */
    protected function buildBillingData( $user )
    {
        return [
            'name'    => $user->fullname,
            'address' => $this->buildBillingAddressData( $user )
        ];
    }

    /**
     * Builds and Returns the Shipping Data
     *
     * @param User $user
     * @param float $shipping_cost
     * @param Carbon $delivery_date
     * @return array
     */
    protected function buildShippingData( $user, $shipping_cost, $delivery_date, $address_id )
    {
        return [
            'name'          => $user->fullname,
            'fee'           => intval( $shipping_cost * 100 ),
            'delivery_date' => $delivery_date->format('Y-m-d'),
            'expedited'     => false,
            'address'       => $this->buildShippingAddressData( $user, $address_id )
        ];
    }

    /**
     * Returns the User's Billing Address array
     *
     * @param User $user
     * @return array
     */
    protected function buildBillingAddressData( $user )
    {
        $address = $user->addresses()->where('addresses.type', '=', 'billing')->first();

        if( ! $address )
            $address = $user->addresses[0];

        return $this->addressData( $address );
    }

    /**
     * Returns the User's Address array
     *
     * @param User $user
     * @return array
     */
    protected function buildShippingAddressData( $user, $address_id )
    {
        $address = $user->addresses()->where('addresses.id', '=', $address_id)->first();

        if( ! $address )
            $address = $user->addresses[0];

        return $this->addressData( $address );
    }

    /**
     * Returns the Address array
     *
     * @param Address $address
     * @return array
     */
    protected function addressData( $address )
    {
        return [
            'country'       => 'br',
            'street'        => $address->address,
            'street_number' => $address->number,
            'state'         => $address->state->code,
            'city'          => $address->city,
            'neighborhood'  => $address->neighborhood,
            'zipcode'       => preg_replace('/[^0-9]/', '', $address->zipcode )
        ];
    }

    /**
     * Builds and Returns the Transaction Items
     *
     * @param \stdClass $cart
     * @return array
     */
    protected function buildItemsData( $cart )
    {
        // Build items array
        $items = [];

        foreach( $cart->products as $item )
        {
            $title = $item->name;

            if( count($item->variations) > 0 )
            {
                $vars  = implode(' - ', $item->variations);
                $title = $title .' ('. strip_tags( $vars ) .')';
            }

            $items[] = [
                'id'         => (string) $item->id,
                'title'      => $title,
                'unit_price' => intval( $item->unit_price * 100 ),
                'quantity'   => $item->quantity,
                'tangible'   => true
            ];
        }

        return $items;
    }

    /**
     * Creates and Returns the Split rules
     *
     * @param array $cart
     * @return array
     */
    protected function buildSplitRules( $cart )
    {
        // Calculate marketplace amount
        $subtotal   = intval( $cart->subtotal * 100 );
        $comission  = 12;
        $mkt_amount = (($subtotal * $comission) / 100);

        //dd( $subtotal, $comission, $mkt_amount, round($mkt_amount) );

        //$mkt_amount = (($cart->subtotal * 12) / 100 ) * 100;
        $rules = [
            // First rule is the Marketplace rule
            [
                'recipient_id' => Pagarme::getSiteRecipientID(),
                'charge_processing_fee' => true,
                'liable' => true,
                'amount' => round( $mkt_amount )
            ]
        ];

        $shops = [];

        // Get Stores
        foreach( $cart->products as $item )
        {
            //dd( $item );

            // Load Store & Recipient ID
            $store  = Store::find( $item->store_id );
            $recip  = $store->info->recipient_id;
            $exists = false;

            foreach( $shops as &$shop )
            {
                if( $shop['recipient_id'] === $recip )
                {
                    // Existing shop, sum value
                    $shop['amount'] += $item->subtotal;
                    $exists = true;

                    break;
                }
            }

            if( ! $exists )
            {
                $shops[] = [
                    'recipient_id' => $recip,
                    'amount' => $item->subtotal,
                    'charge_processing_fee' => false,
                    'liable' => false,
                    'shipping' => $item->shipping
                ];
            }
        }

        // Calculate
        foreach( $shops as &$shop )
        {
            // Calculate percentage, Sum Shipping
            $amount = ($shop['amount'] * 88) / 100;
            $amount = $amount + $shop['shipping'];
            $amount = $amount * 100;
            $shop['amount'] = round( $amount );
        }

        return array_merge( $rules, $shops );
    }

    /**
     * Creates and Returns the Order
     *
     * @param array $data
     * @return Order
     */
    protected function saveOrder( $data, $user, $payment_method )
    {
        // Creates the Order
        $order = Order::create([
            'user_id'        => $user->id,
            'payment_method' => $payment_method,
            'amount'         => $data['amount'] / 100,
            'shipping'       => $data['shipping']['fee'] / 100,
            'installments'   => 1,
            'request_data'   => json_encode( $data ),
            'status'         => 'waiting_payment',
        ]);

        if( ! $order )
        {
            $res = new AppMessage( false, 'Ocorreu um erro com a gravação de seu pedido. Por favor, tente novamente.' );

            abort( $this->responseJson( $res ) );
        }

        // Create Order Items
        foreach( $data['items'] as $item )
        {
            // Get Product name
            //$name = Product::where('id', '=', $item['id'])->value('name');

            $order_item = OrderItem::create([
                'order_id'    => $order->id,
                'product_id'  => $item['id'],
                //'name'        => $name,
                'name'        => $item['title'],
                'quantity'    => $item['quantity'],
                'unit_price'  => $item['unit_price'] / 100,
                'total_price' => ($item['unit_price'] * $item['quantity']) / 100
            ]);

            if( ! $order_item )
            {
                $res = new AppMessage( false, 'Ocorreu um erro na gravação dos itens de seu pedido. Por favor, tente novamente.' );

                abort( $this->responseJson( $res ) );
            }
        }

        return $order;
    }
}
