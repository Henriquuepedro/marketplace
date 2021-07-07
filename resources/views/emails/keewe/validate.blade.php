@component('mail::message')
# Olá {{ $fullname }}

Obrigado por se cadastrar em nossa plataforma.

Para completar seu cadastro e ter seu acesso liberado,
você deve clicar no botão abaixo e validar seu endereço de email.

@component('mail::button', ['url' => $link])
Validar meu email
@endcomponent

Caso o botão acima não funcione, copie o link abaixo, cole-o na barra
de endereços do seu navegador e aperte a tecla "Enter".

<a href="{{ $link }}">{{ $link }}</a>

Obrigado,<br>
{{ config('app.name') }}
@endcomponent
