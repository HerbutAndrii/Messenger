@extends('layout')
@section('title', 'Chats')
@section('content')
@include('header')
    <div class="chat-list-container">
        <div class="chat-list-header">
            <h1>Your Chats</h1>
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
                            <div class="chat-last-message">{{ $chat->latestMessage() ? $chat->latestMessage()->content : 'No messages' }}</div>
                        </div>
                        @if($chat->latestMessage())
                            <div class="chat-time">{{ $chat->latestMessage()->updated_at->format('H:i') }}</div>
                        @endif
                    </a>
                    <span class="delete-chat" data-id="{{ $chat->id }}">Delete</span>
                </div>
            @endforeach
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $('.delete-chat').click(function () {
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
        });
    </script>
@endsection