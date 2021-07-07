<?php

return [

    // Order Statuses
    'status' => [
        'processing' => 'Em processamento',
        'authorized' => 'Autorizado',
        'paid' => 'Pago',
        'refunded' => 'Reembolsado',
        'waiting_payment' => 'Aguardando pagamento',
        'pending_refund' => 'Pagamento devolvido',
        'refused' => 'Recusado',
        //
        'in_transit' => 'Em trânsito',
        'returned' => 'Devolvido',
        'delivered' => 'Entregue'
    ],

    // Status Reasons
    'reasons' => [
        'acquirer' => 'Adquirente',
        'antifraud' => 'Antifraude',
        'internal_error' => 'Erro interno',
        'no_acquirer' => 'Sem Adquirente',
        'acquirer_timeout' => 'Tempo limite esgotado'
    ],

    // Payment methods
    'pay_methods' => [
        'credit_card' => 'Cartão de Crédito',
        'boleto' => 'Boleto Bancário'
    ],
];
