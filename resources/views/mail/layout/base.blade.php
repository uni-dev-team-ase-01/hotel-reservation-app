<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
</head>

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f4f4f4; padding: 20px;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table width="600" cellpadding="0" cellspacing="0" border="0"
                    style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">

                    @include('mail.components.header')

                    <!-- Content -->
                    <tr>
                        <td style="padding: 30px;">
                            @yield('greeting')

                            @yield('content')

                            @yield('action')

                            @yield('closing')
                        </td>
                    </tr>

                    @include('mail.components.footer')

                </table>
            </td>
        </tr>
    </table>
</body>

</html>
