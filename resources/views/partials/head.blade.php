<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>
    {{ filled($title ?? null) ? $title.' - '.config('app.name', 'Piiston') : config('app.name', 'Piiston') }}
</title>

<script>
    (function () {
        const storedTheme = localStorage.getItem('piiston_theme');
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const theme = storedTheme || (systemPrefersDark ? 'dark' : 'light');
        document.documentElement.setAttribute('data-theme', theme);
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    })();
</script>

@vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/landing.js'])

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<link rel="stylesheet" href="https://cdn.hugeicons.com/font/hgi-stroke-rounded.css" crossorigin="anonymous" />

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">

<link rel="icon" href="{{ asset('piiston/favicon.ico') }}" sizes="any">
<link rel="icon" href="{{ asset('piiston/favicon-32x32.png') }}" type="image/png">
<link rel="apple-touch-icon" href="{{ asset('piiston/apple-touch-icon.png') }}">
