<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Vue</title>
    

</head>
<body class=" py-24">
    <main class="container mx-auto flex justify-center items-center max-h-screen h-full">
        <div id="app" class="max-w-7xl w-full mx-auto rounded-lg shadow-lg shadow-gray-300 p-4"></div>
    </main>
    @if (auth()->check())
        <script>
            window.Laravel = {!!
                json_encode([
                    'isLoggedIn' => true,
                    'user' => auth()->user(),
                    'token' => session('token'),
                ]);
            !!}
        </script>
    @else
        <script>
            window.Laravel = {!!
                json_encode([
                    'isLoggedIn' => false,
                ]);
            !!}
        </script>
    @endif
    @vite(['resources/js/vue/main.js'])
</body>
</html>