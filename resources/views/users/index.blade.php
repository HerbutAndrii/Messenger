@extends('layout')
@section('title', 'Search')
@section('content')
@include('header')
    <div class="user-list-container">
        <div class="user-list-header">
            <h1>Search Users</h1>
        </div>
        <div class="search-input-area">
            <input class="search-input" type="text" name="search" placeholder="Search users...">
        </div>
        <div class="user-list"></div>
        <div id="search-results" style="display: none"></div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $('.search-input').keyup(function (event) {
                var input = $(this).val();
                if(input == '') {
                    $('#search-results').html('');
                    $('#search-results').hide();
                    $('.user-list').show();
                } else {
                    $.ajax({
                        type: 'GET',
                        url: "{{ route('search.users') }}",
                        data: {
                            search: input
                        },
                        success: function (data) {
                            $('.user-list').hide();
                            if(data.length === 0) {
                                $('#search-results').html('<h2 style="text-align: center; color: #333">No results</h2>');
                            } else {
                                $('#search-results').html('<div class="user-list"></div>');
                                $.each(data, function(index, user) {
                                    var avatar = "{{ asset('storage/avatars') }}" + '/' + user.avatar;
                                    var url = "{{ route('profiles.show', ':id') }}".replace(':id', user.id);
                                    var userName = user.name.replace(new RegExp(input, 'gi'), function(matchedText) {
                                        return '<span class="highlight">' + matchedText + '</span>';
                                    }); 
                                    var userEmail = user.email.replace(new RegExp(input, 'gi'), function(matchedText) {
                                        return '<span class="highlight">' + matchedText + '</span>';
                                    }); 
                                    $('#search-results').find('.user-list').append(
                                        '<div class="user-item">' +
                                            '<a href="' + url + '">' +
                                                '<div class="user-avatar">' +
                                                    '<img src="' + avatar + '" alt="avatar">' + 
                                                '</div>' +
                                                '<div class="user-details">' +
                                                    '<div class="user-name">' + userName + '</div>' +
                                                    '<div class="user-email">' + userEmail + '</div>' +
                                                '</div>' +
                                            '</a>' +
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