<tr>
    <td
        style="padding: 30px 30px 20px 30px; text-align: center; border-bottom: 2px solid {{ $headerColor ?? '#007bff' }};">
        @if (isset($logo) && $logo)
            <img src="{{ $logo }}" alt="{{ config('app.name') }}" style="max-height: 50px; margin-bottom: 10px;">
        @endif
        <h1 style="margin: 0; color: {{ $headerColor ?? '#007bff' }}; font-size: 24px; font-weight: bold;">
            {{ $appName ?? config('app.name') }}
        </h1>
        @if (isset($subtitle))
            <p style="margin: 10px 0 0 0; color: #666; font-size: 14px;">{{ $subtitle }}</p>
        @endif
    </td>
</tr>
