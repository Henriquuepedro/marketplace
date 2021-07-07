@component('mail::message')
# Olá {{ $fullname }}

É um prazer informar que você recebeu um pedido através da nossa plataforma.

O número do pedido é: <big><b>{{ $order->tid }}</b></big>

Para ver os detalhes do pedido, acesse seu painel pelo link abaixo:

@component('mail::button', ['url' => $link])
Meus Pedidos
@endcomponent

Parabéns!<br>
{{ config('app.name') }}
@endcomponent
