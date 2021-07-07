@component('mail::message')
# Olá {{ $fullname }}

É um prazer informar que sua compra foi realizada com sucesso!

O número de seu pedido é: <big><b>{{ $order->tid }}</b></big>


Veja abaixo os detalhes de seu pedido:

<table style="width: 100%;">
    <tr>
        <td>Valor do Frete</td>
        <td>{{ fmoney($order->shipping) }}</td>
    </tr>
    <tr>
        <td>Valor Total</td>
        <td>{{ fmoney($order->amount) }}</td>
    </tr>
</table>

<hr>

## {{ $count }} Produtos na sua compra

<table style="width: 100%;">
    @foreach( $items as $item )
        <tr>
            <td>{{ $item->name }}</td>
            <td>{{ $item->quantity }}x</td>
            <td>{{ fmoney( $item->total_price ) }}</td>
        </tr>
    @endforeach
</table>

<hr>

Você pode acompanhar seu pedido pelo nosso site, pelo link abaixo:

@component('mail::button', ['url' => $link])
Meus Pedidos
@endcomponent

Agradecemos sua compra,<br>
{{ config('app.name') }}
@endcomponent
