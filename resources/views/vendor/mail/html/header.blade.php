<tr>
<td class="header">
<a href="{{ url('/') }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@else
<img src="https://keewe.com.br/keewe/img/logo-keewe.png" class="logo" alt="{{ env('APP_NAME') }}">
{{-- $slot --}}
@endif
</a>
</td>
</tr>
