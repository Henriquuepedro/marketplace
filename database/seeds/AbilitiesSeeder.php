<?php

use Illuminate\Database\Seeder;
use Silber\Bouncer\Bouncer;

class AbilitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Gets the Bouncer instance
        $bouncer  = Bouncer::create();

        // Customers ------------------------------------------------------------------------------
        // Gets or Creates the Customers role
        $customers = $bouncer->role()->firstOrCreate([
            'name' => 'customers',
            'title' => 'Clientes'
        ]);

        // Abilities
        $customers_abilities = [
            'login' => $bouncer->ability()->firstOrCreate(['name' => 'login', 'title' => 'Acessar conta']),
            // Profile / Account
            'view-profile' => $bouncer->ability()->firstOrCreate(['name' => 'view-profile', 'title' => 'Ver perfil']),
            'update-profile' => $bouncer->ability()->firstOrCreate(['name' => 'update-profile', 'title' => 'Atualizar perfil']),
            // Addresses
            'view-addresses' => $bouncer->ability()->firstOrCreate(['name' => 'view-addresses', 'title' => 'Ver endereços']),
            'add-addresses' => $bouncer->ability()->firstOrCreate(['name' => 'add-addresses', 'title' => 'Adicionar endereços']),
            'update-addresses' => $bouncer->ability()->firstOrCreate(['name' => 'update-addresses', 'title' => 'Atualizar endereços']),
            // Phones
            'view-phones' => $bouncer->ability()->firstOrCreate(['name' => 'view-phones', 'title' => 'Ver telefones']),
            'add-phones' => $bouncer->ability()->firstOrCreate(['name' => 'add-phones', 'title' => 'Adicionar telefones']),
            'update-phone' => $bouncer->ability()->firstOrCreate(['name' => 'update-phones', 'title' => 'Atualizar telefones']),
            // Products
            'view-products' => $bouncer->ability()->firstOrCreate(['name' => 'view-products', 'title' => 'Visualizar produtos']),
            'buy' => $bouncer->ability()->firstOrCreate(['name' => 'buy', 'title' => 'Comprar']),
            'add-review' => $bouncer->ability()->firstOrCreate(['name' => 'add-review', 'title' => 'Avaliar produtos']),
            // Orders
            'view-orders' => $bouncer->ability()->firstOrCreate(['name' => 'view-orders', 'title' => 'Visualizar pedidos']),
            'cancel-orders' => $bouncer->ability()->firstOrCreate(['name' => 'cancel-orders', 'title' => 'Cancelar compras']),
            // Messages
            'view-messages' => $bouncer->ability()->firstOrCreate(['name' => 'view-messages', 'title' => 'Visualizar mensagens']),
            'add-messages' => $bouncer->ability()->firstOrCreate(['name' => 'add-messages', 'title' => 'Enviar mensagens']),
            // Others
            'become-a-seller' => $bouncer->ability()->firstOrCreate(['name' => 'become-a-seller', 'title' => 'Tornar-se vendedor']),
            'cancel-account' => $bouncer->ability()->firstOrCreate(['name' => 'cancel-account', 'title' => 'Cancelar conta']),
        ];

        foreach( $customers_abilities as $name => $ability )
        {
            $bouncer->allow( $customers )->to( $ability );
        }

        // Sellers --------------------------------------------------------------------------------
        $sellers = $bouncer->role()->firstOrCreate([
            'name' => 'sellers',
            'title' => 'Vendedores'
        ]);

        // Abilities
        $sellers_abilities = [
            // Store
            'add-store' => $bouncer->ability()->firstOrCreate(['name' => 'create-store', 'title' => 'Criar loja']),
            'view-store' => $bouncer->ability()->firstOrCreate(['name' => 'view-store', 'title' => 'Visualizar loja']),
            'update-store' => $bouncer->ability()->firstOrCreate(['name' => 'update-store', 'title' => 'Atualizar loja']),
            'delete-store' => $bouncer->ability()->firstOrCreate(['name' => 'delete-store', 'title' => 'Excluir loja']),
            // Store logo
            'view-logo' => $bouncer->ability()->firstOrCreate(['name' => 'view-logo', 'title' => 'Visualizar logotipo']),
            'add-logo' => $bouncer->ability()->firstOrCreate(['name' => 'add-logo', 'title' => 'Adicionar logotipo']),
            'update-logo' => $bouncer->ability()->firstOrCreate(['name' => 'update-logo', 'title' => 'Atualizar logotipo']),
            'delete-logo' => $bouncer->ability()->firstOrCreate(['name' => 'delete-logo', 'title' => 'Excluir logotipo']),
            // Store cover image
            'view-cover' => $bouncer->ability()->firstOrCreate(['name' => 'view-cover', 'title' => 'Visualizar capa']),
            'add-cover' => $bouncer->ability()->firstOrCreate(['name' => 'add-cover', 'title' => 'Adicionar capa']),
            'update-cover' => $bouncer->ability()->firstOrCreate(['name' => 'update-cover', 'title' => 'Atualizar capa']),
            'delete-cover' => $bouncer->ability()->firstOrCreate(['name' => 'delete-cover', 'title' => 'Excluir capa']),
            // Products
            'view-products' => $bouncer->ability()->firstOrCreate(['name' => 'view-products', 'title' => 'Visualizar produtos']),
            'add-products' => $bouncer->ability()->firstOrCreate(['name' => 'add-products', 'title' => 'Adicionar produtos']),
            'update-products' => $bouncer->ability()->firstOrCreate(['name' => 'update-products', 'title' => 'Atualizar produtos']),
            'delete-products' => $bouncer->ability()->firstOrCreate(['name' => 'delete-products', 'title' => 'Excluir produtos']),
            'sell' => $bouncer->ability()->firstOrCreate(['name' => 'sell', 'title' => 'Vender']),
            // Promo
            'view-promo' => $bouncer->ability()->firstOrCreate(['name' => 'view-promo', 'title' => 'Visualizar promoções']),
            'add-promo' => $bouncer->ability()->firstOrCreate(['name' => 'add-promo', 'title' => 'Adicionar promoções']),
            'update-promo' => $bouncer->ability()->firstOrCreate(['name' => 'update-promo', 'title' => 'Atualizar promoções']),
            'delete-promo' => $bouncer->ability()->firstOrCreate(['name' => 'delete-promo', 'title' => 'Excluir promoções']),
            // Coupons
            'view-coupons' => $bouncer->ability()->firstOrCreate(['name' => 'view-coupons', 'title' => 'Visualizar cupons']),
            'add-coupons' => $bouncer->ability()->firstOrCreate(['name' => 'add-coupons', 'title' => 'Adicionar cupons']),
            'update-coupons' => $bouncer->ability()->firstOrCreate(['name' => 'update-coupons', 'title' => 'Atualizar cupons']),
            'delete-coupons' => $bouncer->ability()->firstOrCreate(['name' => 'delete-coupons', 'title' => 'Excluir cupons']),
            // Orders
            'view-orders' => $bouncer->ability()->firstOrCreate(['name' => 'view-orders', 'title' => 'Visualizar pedidos']),
            'update-orders' => $bouncer->ability()->firstOrCreate(['name' => 'update-orders', 'title' => 'Atualizar pedidos']),
            'cancel-orders' => $bouncer->ability()->firstOrCreate(['name' => 'cancel-orders', 'title' => 'Cancelar pedidos']),
            // Messages
            'view-messages' => $bouncer->ability()->firstOrCreate(['name' => 'view-messages', 'title' => 'Visualizar mensagens']),
            'add-messages' => $bouncer->ability()->firstOrCreate(['name' => 'add-messages', 'title' => 'Enviar mensagens']),
        ];

        foreach( $sellers_abilities as $name => $ability )
        {
            $bouncer->allow( $sellers )->to( $ability );
        }
    }
}
