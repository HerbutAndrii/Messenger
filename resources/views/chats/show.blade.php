@extends('layout')
@section('title', 'Chat')
@section('content')
    <div class="chat-container">
        <div class="chat-header">
            <div class="chat-header-avatar">
                <img src="{{ asset('storage/avatars/' . $chat->userAvatar()) }}" alt="avatar">
            </div>
            <span>{{ $chat->userName() }}</span>
            <div class="chat-menu">
                <i class="fa-solid fa-ellipsis-vertical"></i>
                <div class="chat-menu-content">
                    <form action="{{ route('chats.destroy', $chat) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" id="delete-chat">Delete Chat</button>
                    </form> 
                </div>
            </div>
        </div>
        <div class="message-area" id="messages">
            @foreach($messages as $message)
                @if($message->user->id === auth()->user()->id)
                    <div class="message sent" data-id="{{ $message->id }}">
                        <span class="message-content">{{ $message->content }}</span>
                        <span class="timestamp">{{ $message->updated_at->format('H:i') }}</span>
                        <div class="message-options">
                            <span class="edit-message">Edit</span>
                            <span class="delete-message">Delete</span>
                        </div>
                    </div>
                @else
                    <div class="message received" data-id="{{ $message->id }}"> 
                        <span class="message-content">{{ $message->content }}</span>
                        <span class="timestamp">{{ $message->updated_at->format('H:i') }}</span>
                    </div>
                @endif
            @endforeach
            <div id="typing-indicator" style="display: none;"></div>
        </div>
        <form action="{{ route('chats.messages.store', $chat) }}" method="POST" id="chat-input">
            @csrf
            <div class="chat-input-area">
                <input type="text" placeholder="Type your message here..." name="content">
                <button type="submit">Send</button>
            </div>
        </form>
        <div style="color: red; font-size: 15px; margin-bottom: 20px; margin-left: 20px; display: none" id="content-error"></div>
    </div>

    <script type="module">
        $(document).ready(function () {
            $('#chat-input').submit(function (event) {
                event.preventDefault();

                var input = $(this).find('input');

                $.ajax({
                    type: $(this).attr('method'),
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#typing-indicator').remove();
                        $('#messages').append(
                            '<div class="message sent" data-id="' + data.message.id + '">' + 
                                '<span class="message-content">' + data.message.content + '</span>' + 
                                '<span class="timestamp">' + new Date(data.message.updated_at).toLocaleString([], { hour: '2-digit', minute: '2-digit' }) + '</span>' +
                                '<div class="message-options">' + 
                                    '<span class="edit-message">Edit</span>' + 
                                    '<span class="delete-message">Delete</span>' + 
                                '</div>' + 
                            '</div>'
                        );
                        input.val('');
                    },
                    error: function (err) {
                        let error = err.responseJSON;
                        $('#content-error').show();
                        $.each(error.errors, function (index, value) {
                            $('#content-error').text(value);
                        });
                    }
                });
            });
        });

        $('#messages').on('click', '.delete-message', function (event) {
            event.preventDefault();

            var message = $(this).closest('.message');

            if(confirm('Are you sure you want to delete this message?')) {
                $.ajax({
                    type: 'DELETE',
                    url: "{{ route('messages.destroy', ':id') }}".replace(':id', message.data('id')),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        message.slideUp(500, function () {
                            $(this).remove();
                        });
                        console.log('Message deleted');
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            }
        });

        $('#messages').on('click', '.edit-message', function() {
            var message = $(this).closest('.message');
            var messageContent = message.find('.message-content').text();
            var newMessageContent = prompt('Edit your message:', messageContent);
            if (newMessageContent) {
                $.ajax({
                    type: 'PUT',
                    url: "{{ route('messages.update', ':id') }}".replace(':id', message.data('id')),
                    data: {
                        content: newMessageContent
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        message.find('.message-content').text(data.message.content);
                        message.find('.timestamp').text(new Date().toLocaleString([], { hour: '2-digit', minute: '2-digit' }));
                        console.log('Message edited');
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            }
        });

        function typing(isTyping) {
            axios.post("{{ route('chats.messages.typing', ':id') }}".replace(':id', '{{ $chat->id }}'), {
                is_typing: isTyping
            });
        }

        var typingTimeout1;
        var typingTimeout2;

        $('#chat-input').keyup(function (event) {
            if(event.which != 13) {
                clearTimeout(typingTimeout2);
                clearTimeout(typingTimeout1);
                typingTimeout2 = setTimeout(() => {
                    typing(true);
                    typingTimeout1 = setTimeout(() => {
                        typing(false);
                    }, 3000);
                }, 500);
            } else {
                clearTimeout(typingTimeout2);
                clearTimeout(typingTimeout1);
                typing(false);
            }
        });

        $('#messages').on('click', '.sent', function () {
            if($(this).find('.message-options').is(':visible')) {
                $(this).find('.message-options').hide(300);
            } else {
                $('.message-options').hide(300);
                $(this).find('.message-options').css('display', 'flex').hide().show(300);
            }
        });

        $('.chat-menu').click(function () {
            if($(this).find('.chat-menu-content').is(':visible')) {
                $(this).find('.chat-menu-content').slideUp(300);
            } else {
                $(this).find('.chat-menu-content').slideDown(300);
            }
        });

        $('#delete-chat').click(function (event) {
            if(confirm('Are you sure you want to delete this chat?')) {
                return;
            } else {
                event.preventDefault();
            }
        });

        Echo.private('chats.' + '{{ $chat->id }}')
        .listen('MessageSent', (e) => {
            $('#typing-indicator').remove();
            $('#messages').append(
                '<div class="message received" data-id="' + e.message.id + '">' + 
                    '<span class="message-content">' + e.message.content + '</span>' + 
                    '<span class="timestamp">' + new Date(e.message.updated_at).toLocaleString([], { hour: '2-digit', minute: '2-digit' }) + '</span>' +
                '</div>' 
            );
        })
        .listen('MessageDeleted', (e) => {
            $('.message[data-id="' + e.messageId + '"]').slideUp(500, function () {
                $(this).remove();
            });
        })
        .listen('MessageEdited', (e) => {
            var message = $('.message[data-id="' + e.message.id + '"]');
            message.find('.message-content').text(e.message.content);
            message.find('.timestamp').text(new Date().toLocaleString([], { hour: '2-digit', minute: '2-digit' }));
        })
        .listen('UserTyping', (e) => {
            if(! $('#typing-indicator').length) {
                $('#messages').append('<div id="typing-indicator" style="display: none;"></div>');
            }

            if(e.isTyping) {
                $('#typing-indicator').text('Typing...');
                $('#typing-indicator').slideDown();
            } else {
                $('#typing-indicator').slideUp();
            }
        });

    </script>
@endsection