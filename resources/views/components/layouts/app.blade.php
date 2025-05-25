<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">
    <head>
        <title>{{ $title ?? config("app.name") }}</title>
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />
        <meta name="author" content="StackBros" />
        <meta
            name="description"
            content="Booking - Multipurpose Online Booking Theme"
        />

        <script>
            const storedTheme = localStorage.getItem('theme');

            const getPreferredTheme = () => {
                if (storedTheme) {
                    return storedTheme;
                }
                return window.matchMedia('(prefers-color-scheme: dark)').matches
                    ? 'dark'
                    : 'light';
            };

            const setTheme = function (theme) {
                if (
                    theme === 'auto' &&
                    window.matchMedia('(prefers-color-scheme: dark)').matches
                ) {
                    document.documentElement.setAttribute(
                        'data-bs-theme',
                        'dark',
                    );
                } else {
                    document.documentElement.setAttribute(
                        'data-bs-theme',
                        theme,
                    );
                }
            };

            const showActiveTheme = (theme) => {
                console.log('Showing active theme:', theme);

                setTimeout(() => {
                    document
                        .querySelectorAll('[data-bs-theme-value]')
                        .forEach((element) => {
                            element.classList.remove('active');
                            console.log(
                                'Removed active from:',
                                element.getAttribute('data-bs-theme-value'),
                            );
                        });

                    const btnToActive = document.querySelector(
                        `[data-bs-theme-value="${theme}"]`,
                    );
                    if (btnToActive) {
                        btnToActive.classList.add('active');
                        console.log('Added active to:', theme);
                    } else {
                        console.log('Button not found for theme:', theme);
                    }

                    const activeThemeIcon = document.querySelector(
                        '.theme-icon-active use',
                    );
                    if (activeThemeIcon && btnToActive) {
                        const currentIcon =
                            btnToActive.querySelector('.mode-switch');
                        if (currentIcon) {
                            const iconPath = currentIcon.querySelector('path');
                            if (iconPath) {
                                const themeDisplay =
                                    document.querySelector(
                                        '.theme-icon-active',
                                    );
                                if (themeDisplay) {
                                    themeDisplay.innerHTML =
                                        currentIcon.innerHTML;
                                }
                            }
                        }
                    }
                }, 0);
            };

            const initThemeSystem = () => {
                setTheme(getPreferredTheme());
                showActiveTheme(getPreferredTheme());
            };

            setTheme(getPreferredTheme());

            window
                .matchMedia('(prefers-color-scheme: dark)')
                .addEventListener('change', () => {
                    const currentTheme = localStorage.getItem('theme');
                    // Only auto-update if user hasn't set a specific preference or has chosen 'auto'
                    if (!currentTheme || currentTheme === 'auto') {
                        setTheme(getPreferredTheme());
                        showActiveTheme(getPreferredTheme());
                    }
                });

            document.addEventListener('click', (e) => {
                const themeButton = e.target.closest('[data-bs-theme-value]');
                if (themeButton) {
                    e.preventDefault();
                    const theme = themeButton.getAttribute(
                        'data-bs-theme-value',
                    );
                    localStorage.setItem('theme', theme);
                    setTheme(theme);
                    showActiveTheme(theme);
                }
            });

            document.addEventListener('DOMContentLoaded', initThemeSystem);

            document.addEventListener('livewire:navigated', () => {
                console.log('Livewire navigated, re-initializing theme system');

                const currentTheme = localStorage.getItem('theme') || 'auto';
                console.log('Current stored theme:', currentTheme);

                setTheme(currentTheme);

                const observer = new MutationObserver((mutations, obs) => {
                    const themeButtons = document.querySelectorAll(
                        '[data-bs-theme-value]',
                    );
                    if (themeButtons.length > 0) {
                        console.log('Theme buttons found, updating display');
                        showActiveTheme(currentTheme);
                        obs.disconnect();
                    }
                });

                observer.observe(document.body, {
                    childList: true,
                    subtree: true,
                });

                setTimeout(() => {
                    observer.disconnect();
                    showActiveTheme(currentTheme);
                }, 200);
            });

            document.addEventListener('livewire:load', initThemeSystem);
        </script>

        <link
            rel="shortcut icon"
            href="{{ asset("assets/images/favicon.ico") }}"
        />
        <link rel="preconnect" href="https://fonts.googleapis.com/" />
        <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&amp;family=Poppins:wght@400;500;700&amp;display=swap"
            rel="stylesheet"
        />
        <link
            rel="stylesheet"
            href="{{ asset("assets/vendor/font-awesome/css/all.min.css") }}"
        />
        <link
            rel="stylesheet"
            href="{{ asset("assets/vendor/bootstrap-icons/bootstrap-icons.css") }}"
        />

        <!-- Theme CSS -->
        <link rel="stylesheet" href="{{ asset("assets/css/style.css") }}" />

        @if (Route::is("rooms.*") || Route::currentRouteName() === "rooms" || Route::currentRouteName() === "home")
            <!-- rooms CSS -->
            <link
                rel="stylesheet"
                href="{{ asset("assets/vendor/font-awesome/css/all.min.css") }}"
            />
            <link
                rel="stylesheet"
                href="{{ asset("assets/vendor/bootstrap-icons/bootstrap-icons.css") }}"
            />
            <link
                rel="stylesheet"
                href="{{ asset("assets/vendor/flatpickr/css/flatpickr.min.css") }}"
            />
            <link
                rel="stylesheet"
                href="{{ asset("assets/vendor/choices/css/choices.min.css") }}"
            />
            <link
                rel="stylesheet"
                href="{{ asset("assets/vendor/tiny-slider/tiny-slider.css") }}"
            />
            <link
                rel="stylesheet"
                href="{{ asset("assets/vendor/nouislider/nouislider.css") }}"
            />
        @endif

        <link
            rel="stylesheet"
            href="{{ asset("assets/vendor/glightbox/css/glightbox.css") }}"
        />
        <link
            rel="stylesheet"
            href="{{ asset("assets/vendor/tiny-slider/tiny-slider.css") }}"
        />

        @vite(["resources/js/app.js"])

        @livewireStyles
    </head>

    <body>
        <main>
            <x-header />
            {{ $slot }}
            <x-footer />
        </main>

        <div class="back-top"></div>

        <!-- Bootstrap JS -->
        {{-- <script src="{{ asset("assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js") }}"></script> --}}

        @if (Route::is("rooms.*") || Route::currentRouteName() == "rooms" || Route::currentRouteName() == "home")
            <!-- Vendors for rooms -->
            <script src="{{ asset("assets/vendor/flatpickr/js/flatpickr.min.js") }}"></script>
            <script src="{{ asset("assets/vendor/choices/js/choices.min.js") }}"></script>
            <script src="{{ asset("assets/vendor/tiny-slider/tiny-slider.js") }}"></script>
            <script src="{{ asset("assets/vendor/nouislider/nouislider.min.js") }}"></script>
        @endif

        <script src="{{ asset("assets/vendor/glightbox/js/glightbox.js") }}"></script>
        <script src="{{ asset("assets/vendor/tiny-slider/tiny-slider.js") }}"></script>

        <!-- ThemeFunctions -->
        <script src="{{ asset("assets/js/functions.js") }}"></script>
        @livewireScripts
    </body>
</html>
