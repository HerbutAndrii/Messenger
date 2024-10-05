@extends('layout')
@section('title', 'Contact')
@section('content')
@include('header')
    <div class="profile-container">
        <div class="profile">
            <img src="{{ asset('storage/avatars/' . $contact->user->avatar) }}" alt="avatar" class="profile-avatar">
            <h2 class="profile-name">{{ $contact->name }}</h2>
            <div class="profile-details">
                <p>{{ $contact->user->email }}</p>
            </div>
            <div class="profile-actions">
                <form action="{{ route('chats.store', $contact->user) }}" method="POST">
                    @csrf
                    <button class="contact-chat" type="submit">Chat</button>
                </form>
                <button class="contact-edit">Edit</button>
                <form action="{{ route('contacts.destroy', $contact) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="contact-delete">Delete</button>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $('.contact-edit').click(function () {
                var newContactName = prompt('Edit contact: ', $('.profile-name').text());
                if(newContactName) {
                    $.ajax({
                        type: 'PUT',
                        url: "{{ route('contacts.update', ':id') }}".replace(':id', '{{ $contact->id }}'),
                        data: {
                            name: newContactName
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (data) {
                            $('.profile-name').text(data.contact.name);
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