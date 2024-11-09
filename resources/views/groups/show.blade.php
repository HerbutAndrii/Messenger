@extends('layout')
@section('title', 'Group')
@section('content')
    <div class="chat-container">
        <div class="chat-header">
            <div class="chat-header-avatar">
                <img src="{{ asset('storage/avatars/' . $group->avatar) }}" alt="avatar">
            </div>
            <div class="group-info">
                <div>{{ $group->name }}</div>
                <div>{{ $group->users->count() }} members | <span id="online-users"></span> online</div>
            </div>
            <div class="chat-menu">
                <i class="fa-solid fa-ellipsis-vertical"></i>
                <div class="chat-menu-content">
                    <a href="{{ route('groups.show.members', $group) }}">Members</a>
                    <form action="{{ route('groups.leave', $group) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" id="leave-group">Leave Group</button>
                    </form> 
                    @if($group->user_id === auth()->user()->id)
                        <form action="{{ route('groups.destroy', $group) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" id="delete-group">Delete Group</button>
                        </form> 
                        <a href="{{ route('groups.edit', $group) }}">Edit Group</a>
                    @endif
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
                    <div class="message-header" data-id="{{ $message->id }}">
                        <div class="member-avatar">
                            <img src="{{ asset('storage/avatars/' . $message->user->avatar) }}" alt="avatar">
                        </div>
                        <span>{{ $message->user->name }}</span>
                    </div>
                    <div class="message received" data-id="{{ $message->id }}"> 
                        <span class="message-content">{{ $message->content }}</span>
                        <span class="timestamp">{{ $message->updated_at->format('H:i') }}</span>
                        @if($group->user_id === auth()->user()->id)
                            <div class="message-options">
                                <span class="delete-message">Delete</span>
                            </div>
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
        <form action="{{ route('groups.messages.store', $group) }}" method="POST" id="chat-input">
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
                        $('.message-header[data-id="' + message.data('id') + '"]').remove();
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
                    errro: function (error) {
                        console.log(error);
                    }
                });
            }
        });

        $('#messages').on('click', '.message', function () {
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

        $('#delete-group').click(function (event) {
            if(confirm('Are you sure you want to delete this group?')) {
                return;
            } else {
                event.preventDefault();
            }
        });

        $('#leave-group').click(function (event) {
            if(confirm('Are you sure you want to leave this group?')) {
                return;
            } else {
                event.preventDefault();
            }
        });

        Echo.join('groups.' + '{{ $group->id }}')
            .here((users) => {
                console.log(users);
                $('#online-users').text(users.length);
            })
            .joining((user) => {
                console.log(user.name + ' is joining');
                $('#online-users').text(Number($('#online-users').text()) + 1);
            })
            .leaving((user) => {
                console.log(user.name + ' is leaving');
                $('#online-users').text(Number($('#online-users').text()) - 1);
            })
            .error((error) => {
                console.log(error);
            })
            .listen('MessageSent', (e) => {
                var userAvatar = "{{ asset('storage/avatars') }}" + '/' + e.user.avatar;
                $('#messages').append(
                    '<div class="message-header" data-id="' + e.message.id + '">' + 
                        '<div class="member-avatar">' + 
                            '<img src="' + userAvatar + '" alt="avatar">' +
                        '</div>' + 
                        '<span>' + e.user.name + '</span>' + 
                    '</div>' +
                    '<div class="message received" data-id="' + e.message.id + '">' + 
                        '<span class="message-content">' + e.message.content + '</span>' + 
                        '<span class="timestamp">' + new Date(e.message.updated_at).toLocaleString([], { hour: '2-digit', minute: '2-digit' }) + '</span>' +
                    '</div>' 
                );
                if({{ auth()->user()->id }} == e.conversation.user_id) {
                    $('.message[data-id="' + e.message.id + '"]').append(
                        '<div class="message-options">' + 
                            '<span class="delete-message">Delete</span>' +
                        '</div>'
                    );
                }
            })
            .listen('MessageDeleted', (e) => {
                $('.message-header[data-id="' + e.messageId + '"]').remove();
                $('.message[data-id="' + e.messageId + '"]').slideUp(500, function () {
                    $(this).remove();
                });
            })
            .listen('MessageEdited', (e) => {
                var message = $('.message[data-id="' + e.message.id + '"]');
                message.find('.message-content').text(e.message.content);
                message.find('.timestamp').text(new Date().toLocaleString([], { hour: '2-digit', minute: '2-digit' }));
            });

    </script>
@endsection