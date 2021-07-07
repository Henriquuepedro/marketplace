@component('mail::message')
# Olá {{ $fullname }}

Sobre seu pedido número <big><b>{{ $order->tid }}</b></big>

Esse e-mail é pra avisar que sua compra está pronta e já foi encaminhada para o endereço:

{!! address_from_order( $json->shipping->address ) !!}

O código de rastreamento dos Correios é: <big><b>{{ $order->tracking_code }}</b></big>

Agradecemos sua preferência,<br>
{{ config('app.name') }}
@endcomponent
