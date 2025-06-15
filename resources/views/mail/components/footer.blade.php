<tr>
    <td style="padding: 20px 30px 30px 30px; text-align: center; border-top: 1px solid #ddd;">
        <p style="margin: 0 0 15px 0; font-size: 16px; line-height: 1.6; color: #333; font-style: italic;">
            {{ $signature ?? 'Best regards,' }}<br>
            The {{ $teamName ?? config('app.name') }} Team
        </p>

        @if (isset($socialLinks) && count($socialLinks) > 0)
            <table cellpadding="0" cellspacing="0" border="0" style="margin: 15px auto;">
                <tr>
                    @foreach ($socialLinks as $platform => $url)
                        <td style="padding: 0 10px;">
                            <a href="{{ $url }}"
                                style="text-decoration: none; color: #007bff;">{{ ucfirst($platform) }}</a>
                        </td>
                    @endforeach
                </tr>
            </table>
        @endif

        <p style="margin: 0; font-size: 12px; line-height: 1.4; color: #666;">
            {{ $footerText ?? 'This is an automated message. Please do not reply to this email.' }}<br>
            @if (isset($supportText))
                {{ $supportText }}
            @else
                If you need assistance, please contact our support team.
            @endif
        </p>

        @if (isset($unsubscribeUrl))
            <p style="margin: 10px 0 0 0; font-size: 12px; color: #666;">
                <a href="{{ $unsubscribeUrl }}" style="color: #666; text-decoration: underline;">Unsubscribe</a>
            </p>
        @endif
    </td>
</tr>
