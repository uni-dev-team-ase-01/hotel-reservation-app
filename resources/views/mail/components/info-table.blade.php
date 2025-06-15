<table width="100%" cellpadding="0" cellspacing="0" border="0"
    style="background-color: {{ $backgroundColor ?? '#f8f9fa' }}; border-radius: 5px; margin: 20px 0;">
    <tr>
        <td style="padding: 20px;">
            @if (isset($title))
                <h3 style="margin: 0 0 15px 0; color: {{ $titleColor ?? '#007bff' }}; font-size: 18px;">
                    {{ $title }}</h3>
            @endif

            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                @foreach ($items as $label => $value)
                    <tr>
                        <td
                            style="padding: 8px 0; font-weight: bold; color: #555; font-size: 14px; width: 40%; vertical-align: top;">
                            {{ $label }}:
                        </td>
                        <td style="padding: 8px 0; color: #333; font-size: 14px; vertical-align: top;">
                            {!! $value !!}
                        </td>
                    </tr>
                @endforeach
            </table>
        </td>
    </tr>
</table>
