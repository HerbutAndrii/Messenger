@extends('layout')
@section('title', 'Contacts')
@section('content')
@include('header')
    <a href="{{ route('contacts.create') }}" class="contact-create-link">Create Contact</a>
    <div class="contact-list-container">
        <div class="contact-list-header">
            <h1>Your Contacts</h1>
        </div>
        <div class="search-input-area">
            <input class="search-input" type="text" name="search" placeholder="Search...">
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
        <div id="search-results" style="display: none"></div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $('.contact-list-container').on('click', '.delete-contact', function () {
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

            $('.search-input').keyup(function (event) {
                var input = $(this).val();
                if(input == '') {
                    $('#search-results').html('');
                    $('#search-results').hide();
                    $('.contact-list').show();
                } else {
                    $.ajax({
                        type: 'GET',
                        url: "{{ route('search.contacts') }}",
                        data: {
                            search: input
                        },
                        success: function (data) {
                            $('.contact-list').hide();
                            if(data.length === 0) {
                                $('#search-results').html('<h2 style="text-align: center; color: #333">No results</h2>');
                            } else {
                                $('#search-results').html('<div class="contact-list"></div>');
                                $.each(data, function(index, contact) {
                                    var avatar = "{{ asset('storage/avatars') }}" + '/' + contact.avatar;
                                    var url = "{{ route('contacts.show', ':id') }}".replace(':id', contact.id);
                                    var contactName = contact.name.replace(new RegExp(input, 'gi'), function(matchedText) {
                                        return '<span class="highlight">' + matchedText + '</span>';
                                    }); 
                                    var contactEmail = contact.email.replace(new RegExp(input, 'gi'), function(matchedText) {
                                        return '<span class="highlight">' + matchedText + '</span>';
                                    });
                                    $('#search-results').find('.contact-list').append(
                                        '<div class="contact-item">' +
                                            '<a href="' + url + '">' +
                                                '<div class="contact-avatar">' +
                                                    '<img src="' + avatar + '" alt="avatar">' + 
                                                '</div>' +
                                                '<div class="contact-details">' +
                                                    '<div class="contact-name">' + contactName + '</div>' +
                                                    '<div class="contact-email">' + contactEmail + '</div>' +
                                                '</div>' +
                                            '</a>' +
                                            '<span class="delete-contact" data-id="' + contact.id + '">Delete</span>' +
                                        '</div>'
                                    );
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