@component('mail::message')
# Olá

Você está recebendo essa mensagem porque foi solicitado a redefinição de senha através da nossa plataforma.

Caso você não tenha feito essa solicitação, não precisa se preocupar.
Basta excluir esse e-mail e o acesso à sua conta continuará como antes.

Mas se você realmente deseja redefinir sua senha, o código é:

## {{ $token }}

Obrigado,<br>
{{ config('app.name') }}
@endcomponent
