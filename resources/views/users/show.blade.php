@extends('layout')
@section('title', 'Profile')
@section('content')
@include('header')
    <div class="profile-container">
        <div class="profile">
            <img src="{{ asset('storage/avatars/' . $user->avatar) }}" alt="avatar" class="profile-avatar">
            <h2 class="profile-name">{{ $user->name }}</h2>
            <div class="profile-details">
                <p>{{ $user->email }}</p>
            </div>
            <div class="profile-actions">
                @if($user->id === auth()->user()->id)
                    <button class="profile-edit">Edit</button>
                @else
                    <form action="{{ route('chats.store', $user) }}" method="POST">
                        @csrf
                        <button class="contact-chat" type="submit">Chat</button>
                    </form>
                    <form action="{{ route('contacts.store', $user) }}" method="POST">
                        @csrf
                        <button class="add-contact" type="submit">Add To Contacts</button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $('.profile-edit').click(function () {
                var newUserName = prompt('Edit name: ', $('.profile-name').text());
                if(newUserName) {
                    $.ajax({
                        type: 'PUT',
                        url: "{{ route('profiles.update', ':id') }}".replace(':id', '{{ $user->id }}'),
                        data: {
                            name: newUserName
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (data) {
                            $('.profile-name').text(data.user.name);
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