@component('mail::message')
# Olá

Uma nova mensagem foi enviada pela página <b>Fale Conosco</b> do site <b>Keewe</b>.

<b>De: </b> {{ $name }} <{{ $email }}> <br>
<b>Cidade: </b> {{ $city }} <br>
<b>Estado: </b> {{ $state }} <br>
<b>Mensagem: </b> <br>
{{ $message }}

{{ config('app.name') }}
@endcomponent
