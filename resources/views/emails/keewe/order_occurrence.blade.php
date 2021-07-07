@component('mail::message')
# Olá {{ $fullname }}

Um de seus clientes enviou uma solicitação através do nosso site, sobre o pedido número <big><b>{{ $order_tid }}</b></big>

<b>Cliente: </b> {{ $occurrence->customer }} - {{ $occurrence->email }} <br>
<br>Produto: </br> {{ $occurrence->item }} <br>
<br>Assunto: </br> {{ $occurrence->reason }} <br>
<br>Detalhes: </br> {{ $occurrence->description }} <br>

Você precisa entrar em contato diretamente com o cliente para atendê-lo da melhor maneira possível.

Agradecemos sua preferência,<br>
{{ config('app.name') }}
@endcomponent