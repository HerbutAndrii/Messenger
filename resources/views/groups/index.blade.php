@extends('layout')
@section('title', 'Groups')
@section('content')
@include('header')
    <a href="{{ route('groups.create') }}" class="group-create-link">Create Group</a>
    <div class="group-list-container">
        <div class="group-list-header">
            <h1>Your Groups</h1>
        </div>
        <div class="search-input-area">
            <input class="search-input" type="text" name="search" placeholder="Search...">
        </div>
        <div class="group-list">
            @foreach($groups as $group)
                <div class="group-item">
                    <a href="{{ route('groups.show', $group) }}">
                        <div class="group-avatar">
                            <img src="{{ asset('storage/avatars/' . $group->avatar) }}" alt="avatar">
                        </div>
                        <div class="group-details">
                            <div class="group-name">{{ $group->name }}</div>
                            <div class="group-last-message">{{ $group->latestMessage ? $group->latestMessage->content : 'No messages' }}</div>
                        </div>
                        @if($group->latestMessage)
                            <div class="chat-time">{{ $group->latestMessage->updated_at->format('H:i') }}</div>
                        @endif
                    </a>
                    <span class="delete-group" data-id="{{ $group->id }}">Leave</span>
                </div>
            @endforeach
        </div>
        <div id="search-results" style="display: none"></div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $('.group-list-container').on('click', '.delete-group', function () {
                var groupItem = $(this).closest('.group-item');

                if(confirm('Are you sure you want to delete this group?')) {
                    $.ajax({
                        type: 'DELETE',
                        url: "{{ route('groups.leave', ':id') }}".replace(':id', $(this).data('id')),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (data) {
                            groupItem.slideUp(500, function () {
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
                    $('.group-list').show();
                } else {
                    $.ajax({
                        type: 'GET',
                        url: "{{ route('search.groups') }}",
                        data: {
                            search: input
                        },
                        success: function (data) {
                            $('.group-list').hide();
                            if(data.length === 0) {
                                $('#search-results').html('<h2 style="text-align: center; color: #333">No results</h2>');
                            } else {
                                $('#search-results').html('<div class="group-list"></div>');
                                $.each(data, function(index, group) {
                                    var avatar = "{{ asset('storage/avatars') }}" + '/' + group.avatar;
                                    var url = "{{ route('groups.show', ':id') }}".replace(':id', group.id);
                                    var groupLatestMessage = group.latest_message ? group.latest_message.content : 'No messages';
                                    var groupName = group.name.replace(new RegExp(input, 'gi'), function(matchedText) {
                                        return '<span class="highlight">' + matchedText + '</span>';
                                    });
                                    var groupItem = $('#search-results').find('.group-list').append(
                                        '<div class="group-item">' + 
                                            '<a href="' + url + '">' + 
                                                '<div class="group-avatar">' + 
                                                    '<img src="' + avatar + '" alt="avatar">' + 
                                                '</div>' +
                                                '<div class="group-details">' +
                                                    '<div class="group-name">' + groupName + '</div>' +
                                                    '<div class="group-last-message">' + groupLatestMessage + '</div>' +
                                                '</div>' +
                                            '</a>' +
                                            '<span class="delete-group" data-id="' + group.id + '">Leave</span>' +
                                        '</div>'
                                    );
                                    if(group.latest_message) {
                                        groupItem.find('a').append(
                                            '<div class="chat-time">' + 
                                                new Date(group.latest_message.updated_at).toISOString().slice(11, 16) + 
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