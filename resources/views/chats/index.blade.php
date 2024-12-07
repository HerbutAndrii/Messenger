@extends('layout')
@section('title', 'Chats')
@section('content')
@include('header')
    <div class="chat-list-container">
        <div class="chat-list-header">
            <h1>Your Chats</h1>
        </div>
        <div class="search-input-area">
            <input class="search-input" type="text" name="search" placeholder="Search chats...">
        </div>
        <div class="chat-list">
            @foreach($chats as $chat)
                <div class="chat-item">
                    <a href="{{ route('chats.show', $chat) }}">
                        <div class="chat-avatar">
                            <img src="{{ asset('storage/avatars/' . $chat->userAvatar()) }}" alt="avatar">
                        </div>
                        <div class="chat-details">
                            <div class="chat-name">{{ $chat->userName() }}</div>
                            <div class="chat-last-message">{{ $chat->latestMessage ? $chat->latestMessage->content : 'No messages' }}</div>
                        </div>
                        @if($chat->latestMessage)
                            <div class="chat-time">{{ $chat->latestMessage->updated_at->format('H:i') }}</div>
                        @endif
                    </a>
                    <span class="delete-chat" data-id="{{ $chat->id }}">Delete</span>
                </div>
            @endforeach
        </div>
        <div id="search-results" style="display: none"></div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $('.chat-list-container').on('click', '.delete-chat', function () {
                var chatItem = $(this).closest('.chat-item');

                if(confirm('Are you sure you want to delete this contact?')) {
                    $.ajax({
                        type: 'DELETE',
                        url: "{{ route('chats.destroy', ':id') }}".replace(':id', $(this).data('id')),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (data) {
                            chatItem.slideUp(500, function () {
                                $(this).remove();
                            });
                        },
                        error: function (error) {
                            console.log(error);
                        }
                    });
                }
            });

            $('.search-input').keyup(function (event) {
                var input = $(this).val();
                if(input == '') {
                    $('#search-results').html('');
                    $('#search-results').hide();
                    $('.chat-list').show();
                } else {
                    $.ajax({
                        type: 'GET',
                        url: "{{ route('search.chats') }}",
                        data: {
                            search: input
                        },
                        success: function (data) {
                            console.log(data);
                            $('.chat-list').hide();
                            if(data.length === 0) {
                                $('#search-results').html('<h2 style="text-align: center; color: #333">No results</h2>');
                            } else {
                                $('#search-results').html('<div class="chat-list"></div>');
                                $.each(data, function(index, chat) {
                                    var avatar = "{{ asset('storage/avatars') }}" + '/' + chat.user_avatar;
                                    var url = "{{ route('chats.show', ':id') }}".replace(':id', chat.id);
                                    var chatLatestMessage = chat.latest_message ? chat.latest_message.content : 'No messages';
                                    var chatUserName = chat.user_name.replace(new RegExp(input, 'gi'), function(matchedText) {
                                        return '<span class="highlight">' + matchedText + '</span>';
                                    }); 
                                    var chatItem = $('#search-results').find('.chat-list').append(
                                        '<div class="chat-item">' +
                                            '<a href="' + url + '">' + 
                                                '<div class="chat-avatar">' +
                                                    '<img src="' + avatar + '" alt="avatar">' +
                                                '</div>' +
                                                '<div class="chat-details">' +
                                                    '<div class="chat-name">' + chatUserName + '</div>' +
                                                    '<div class="chat-last-message">' + chatLatestMessage + '</div>' +
                                                '</div>' +
                                            '</a>' +
                                            '<span class="delete-chat" data-id="' + chat.id + '">Delete</span>' +
                                        '</div>' 
                                    );
                                    if(chat.latest_message) {
                                        chatItem.find('a').append(
                                            '<div class="chat-time">' + 
                                                new Date(chat.latest_message.updated_at).toISOString().slice(11, 16) + 
                                            '</div>'
                                        );
                                    }
                                });
                            }
                            $('#search-results').show();
                        }
                    });
                }
            });
        });
    </script>
@endsection