<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div class="chat">

        {{-- Header Start --}}
        
        <div class="top">
            <div>
                <p>Rio Achyar</p>
            </div>
        </div>

        {{-- Header End --}}


        {{-- Chat Start --}}

        <div class="messages">
            <div class="left messages">
                <img src="{{ asset('images/rio.jpeg') }}" alt="Avatar">
                <p>Start Chatting with ChatGPT AI</p>
            </div>
        </div>

        {{-- Chat End --}}

        {{-- Footer Start --}}

        <div class="bottom">
            <form action="">
                <input type="text" id="message" name="message" placeholder="Enter Message...." autocomplete="off">
                <button type="submit"></button>
            </form>
        </div>

        {{-- Footer End --}}
    </div>
</body>
</html>