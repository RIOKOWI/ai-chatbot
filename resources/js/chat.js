import $ from 'jquery';

$(document).ready(function () {

    $('form').submit(function (event) {
        event.preventDefault();

        const messageInput = $('#message');
        const button = $('form button');
        const messages = $('.messages');

        messageInput.prop('disabled', true);
        button.prop('disabled', true);

        $.ajax({
            url: "/chat",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content')
            },
            data: {
                content: messageInput.val()
            }
        }).done(function (res) {

            let userMsg = messageInput.val();

            messages.append(`
                <div class="flex justify-end">
                    <div class="bg-gray-900 text-white px-4 py-2 rounded-2xl rounded-br-sm shadow text-sm max-w-[75%] break-words selection:bg-[#fb695c]">
                        ${userMsg}
                    </div>
                </div>
            `);

            messages.append(`
                <div class="flex items-start gap-3">
                    <img src="/images/agent_ueo.jpeg"
                        class="w-10 h-10 rounded-full object-cover"
                        alt="Avatar">
                    <div class="bg-white px-4 py-2 rounded-2xl rounded-tl-sm shadow text-sm max-w-[75%] break-words selection:bg-[#fb695c]">
                        ${res.answer}
                    </div>
                </div>
            `);

            messageInput.val('');
            messages.scrollTop(messages[0].scrollHeight);

        }).always(function () {
            messageInput.prop('disabled', false);
            button.prop('disabled', false);
        });

    });

});
