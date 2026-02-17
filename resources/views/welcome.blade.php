<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    {{-- <link rel="stylesheet" href="/style.css"> --}}
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen flex">

    <div class="w-full sm:max-w-xl md:max-w-2xl lg:max-w-3xl 
                mx-auto h-[100dvh] sm:h-[95vh] 
                bg-white sm:shadow-xl sm:rounded-2xl 
                flex flex-col overflow-hidden">

        <!-- Header Start -->
        <div class="bg-gray-900 text-white px-6 py-4 flex items-center justify-between">
            <p class="font-semibold text-lg">Agent Ueo</p>
        </div>
        <!-- Header End -->

        <!-- Messages Start -->
        <div class="messages flex-1 overflow-y-auto px-4 py-6 space-y-4 bg-gray-50">
            
            <!-- AI Message -->
            <div class="flex items-start gap-3">
                <img src="{{ asset('images/rio.jpeg') }}"
                class="w-10 h-10 rounded-full object-cover"
                alt="Avatar">
                <div class="bg-white px-4 py-2 rounded-2xl rounded-tl-sm shadow text-sm max-w-xs">
                    Start Chatting with Ueo
                </div>
            </div>
            
        </div>
        <!-- Messages End -->

        <!-- Input Start -->
        <div class="border-t bg-white px-4 py-3">
            <form class="flex items-center gap-3">
                <input
                type="text"
                    id="message"
                    name="message"
                    placeholder="Enter message..."
                    autocomplete="off"
                    class="flex-1 border rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900"
                    >
                    <button
                    type="submit"
                    class="bg-gray-900 text-white px-5 py-2 rounded-full text-sm hover:bg-gray-700 transition"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm.53 5.47a.75.75 0 0 0-1.06 0l-3 3a.75.75 0 1 0 1.06 1.06l1.72-1.72v5.69a.75.75 0 0 0 1.5 0v-5.69l1.72 1.72a.75.75 0 1 0 1.06-1.06l-3-3Z" clip-rule="evenodd" />
                    </svg>

                </button>
            </form>
        </div>
        <!-- Input Start -->

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
            // Ambil pesan user sebelum input dikosongkan
            let userMsg = $("form #message").val();

            // User message (kanan)
            $(".messages").append(`
                <div class="flex justify-end">
                    <div class="bg-gray-900 text-white px-4 py-2 rounded-2xl rounded-br-sm shadow text-sm max-w-xs">
                        ${userMsg}
                    </div>
                </div>
            `);

            // AI message (kiri)
            $(".messages").append(`
                <div class="flex items-start gap-3">
                    <img src="{{ asset('images/rio.jpeg') }}"
                        class="w-10 h-10 rounded-full object-cover"
                        alt="Avatar">
                    <div class="bg-white px-4 py-2 rounded-2xl rounded-tl-sm shadow text-sm max-w-xs">
                        ${res.answer}
                    </div>
                </div>
            `);


            // Cleanup & Scroll
            $("form #message").val('');
            $(".messages").scrollTop($(".messages")[0].scrollHeight);

            // Enable Form
            $("form #message").prop('disabled', false);
            $("form button").prop('disabled', false);
        });
    });
</script>
</html>