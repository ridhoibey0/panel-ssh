@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'PREMIUMSSH.NET')
<img src="{{ asset('assets/images/logo.png') }}" class="logo" alt="premiumssh.net">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
