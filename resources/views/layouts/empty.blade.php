<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Links Monitoring</title>

    @vite(['resources/assets/vendors/mdi/css/materialdesignicons.min.css'])
    @vite(['resources/assets/css/styles.css'])

    <link rel="icon" type="image/png" href="/favicon.ico"/>
</head>
<body>
<div class="container-scroller">

    @yield('content')

</div>
@vite(['resources/assets/js/app.js'])
</body>
</html>
