<?php

return [

    // Status
    'active' => 'Ativo',
    'inactive' => 'Inativo',
    'deleted' => 'Excluído',

    'yes' => 'Sim',
    'no' => 'Não',

    // Coupons
    'usage_limit' => [
        'one_per_customer' => 'Apenas 1 vez por cliente',
        'many_times' => 'Diversas vezes',
        'limited_times' => 'Limitado',
    ],
    'products_on_sale' => [
        'include' => 'São considerados no cálculo do desconto',
        'not_include' => 'Não são considerados no cálculo do desconto',
    ],
    'discount_type' => [
        'none' => 'Nenhum desconto',
        'products_amount' => 'Valor fixo na soma dos produtos',
        'total_amount' => 'Valor fixo no total do pedido',
        'shipping_amount' => 'Valor fixo no frete',
        'products_percent' => 'Porcentagem na soma dos produtos',
        'total_percent' => 'Porcentagem no total do pedido',
        'shipping_pecent' => 'Porcentagem no frete',
    ],

    // Replacement / Refund
    'occurrences' => [
        'replacement' => 'Troca',
        'return' => 'Devolução',
        'protest' => 'Reclamação',
        'compliment' => 'Elogio'
    ],
];
