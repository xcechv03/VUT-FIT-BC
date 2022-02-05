{{-- layout.blade.php --}}
{{----}}
{{-- autor: Vojtěch Orava (xorava02) --}}
{{----}}

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../css/app.css">
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>

    </style>
    <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script>
        $(function(){
            $(document).on('click', '.removeItem', function(){
                var id = $(this).data('id');
                $.ajax({
                    type: 'GET',
                    cache: false,
                    url:'/remove_from_cart/' + id,
                    success: function(data) {
                        $("#list").html(data);
                    },
                    error: function(request){
                        alert(request.responseText);
                    }
                });
                $("#items").load("/items_refresh");
            });
        });

        $( document ).ready(function() {
            var id = -1;
            $.ajax({
                type: 'GET',
                cache: false,
                url:'/remove_from_cart/' + id,
                success: function(data) {
                    $("#list").html(data);
                },
                error: function(request){
                    alert(request.responseText);
                }
            });
            $("#items").load("/items_refresh");
        });
    </script>
</head>
<body class="bg-blue-900">
    <header class="fixed w-full bg-blue-900 z-10 top-0 items-center h-20 flex justify-between text-2xl text-white border-b shadow">
        <!-- link to website -->
        <a href="/" class="ml-4 py-2 px-3 font-bold hover:text-white rounded-lg hover:bg-blue-700 shadow">Warehouse</a>

        <ul class="pr-4">
            @if (Route::has('login'))
                @auth
                    <li class="inline-block mr-2 text-lg py-1.5 px-3">Log as <b>{{ auth()->user()->name }}</b></li>
                    <li class="inline-block hover:text-white">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link class="mr-2 text-lg py-1.5 px-3 rounded-lg text-white hover:bg-blue-700 shadow"
                                             :href="route('logout')"
                                             onclick="event.preventDefault();
                                            this.closest('form').submit();">
                                <b>{{ __('Log Out') }}</b>
                            </x-dropdown-link>
                        </form>
                    </li>
                @else
                    <li class="inline-block"><a href="{{ route('login') }}" class="mr-2 text-lg py-1.5 px-4 rounded-lg hover:text-white hover:bg-blue-700 shadow"><b>Log in</b></a></li>

                    @if (Route::has('register'))
                        <li class="inline-block"><a href="{{ route('register') }}" class="mr-2 text-lg py-1.5 px-4 rounded-lg hover:text-white hover:bg-blue-700 shadow"><b>Register</b></a></li>
                    @endif
                @endauth
            @endif
        </ul>

    </header>

{{--      !!!  content here   !!!      --}}
    {{ $content }}


    @if (session()->has('success'))
        <div x-data="{ show: true }"
             x-init="setTimeout(() => show = false, 4000)"
             x-show="show"
             class="fixed top-20 right-6 margin-auto bg-blue-600 text-white py-2 px-4 rounded flex-nowrap">
            <p><b>{{ session('success') }}</b></p>
        </div>
    @endif

</body>
</html>
