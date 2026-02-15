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
<script>
    // Broadcast messages
    $('form').submit(function (event) {
        event.preventDefault();

        // disable form
        $('form #message').prop('disabled', true);
        $('form button').prop('disabled', true);

        $.ajax({
            url: "/chat",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            data: {
                "content": $("form #message").val()
            }
        }).done(function (res) {
            // Populate sending message
            $(".messages > .message").last().after('<div class="right message">' + '<p>' + $("form #message").val() + '</p>' + '</div>');

            // Populate resolving message
            $(".messages > .message").last().after('<div class="left message">' + '<img src="{{ asset('images/rio.jpeg') }}" alt="Avatar">' + '<p>' + res.choices[0].message.content + '</p>' + '</div>');

            // Cleanup
            ${"form #message"}.val('');
            $(document).scrollTop($(document).height());

            // Enable Form
            $("form #message").prop('disabled', false);
            $("form button").prop('disabled', false);

        });
    });
</script>
</html>