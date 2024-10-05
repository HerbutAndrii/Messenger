@extends('layout')
@section('title', 'Contacts')
@section('content')
@include('header')
    <a href="{{ route('contacts.create') }}" class="contact-create-link">Create Contact</a>
    <div class="contact-list-container">
        <div class="contact-list-header">
            <h1>Your Contacts</h1>
        </div>
        <div class="contact-list">
            @foreach($contacts as $contact)
                <div class="contact-item">
                    <a href="{{ route('contacts.show', $contact) }}">
                        <div class="contact-avatar">
                            <img src="{{ asset('storage/avatars/' . $contact->user->avatar) }}" alt="avatar">
                        </div>
                        <div class="contact-details">
                            <div class="contact-name">{{ $contact->name }}</div>
                            <div class="contact-email">{{ $contact->user->email }}</div>
                        </div>
                    </a>
                    <span class="delete-contact" data-id="{{ $contact->id }}">Delete</span>
                </div>
            @endforeach
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $('.delete-contact').click(function () {
                var contactItem = $(this).closest('.contact-item');

                if(confirm('Are you sure you want to delete this contact?')) {
                    $.ajax({
                        type: 'DELETE',
                        url: "{{ route('contacts.destroy', ':id') }}".replace(':id', $(this).data('id')),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (data) {
                            contactItem.slideUp(500, function () {
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