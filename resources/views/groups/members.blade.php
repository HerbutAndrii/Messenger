@extends('layout')
@section('title', 'Group')
@section('content')
@include('header')
    <div class="member-list-container">
        <div class="member-list-header">
            <h1>{{ $group->name }}  ({{ $members->count() }})</h1>
        </div>
        <div class="search-input-area">
            <input class="search-input" type="text" name="search" placeholder="Search members...">
        </div>
        <div class="member-list">
            @foreach($members as $member)
                <div class="member-item" data-id="{{ $member->id }}">
                    <a href="{{ route('profiles.show', $member) }}">
                        <div class="member-avatar">
                            <img src="{{ asset('storage/avatars/' . $member->avatar) }}" alt="avatar">
                        </div>
                        <div class="member-details">
                            <div style="display: flex">
                                <div class="member-name">{{ auth()->user()->id === $member->id ? 'You' : $member->name }}</div>
                                <div class="member-status">{{ auth()->user()->id === $member->id ? '| Online' : '| Offline' }}</div>
                            </div>
                            <div class="member-email">{{ $member->email }}</div>
                        </div>
                    </a>
                    @if(auth()->user()->id === $group->user_id && auth()->user()->id != $member->id)
                        <span class="delete-member" data-id="{{ $member->id }}">Remove</span>
                    @endif
                </div>
            @endforeach
        </div>
        <div id="search-results" style="display: none"></div>
    </div>

    <script type="module">
        $(document).ready(function () {
            $('.search-input').keyup(function () {
                if($(this).val() == '') {
                    $('#search-results').html('');
                    $('#search-results').hide();
                    $('.member-list').show();
                } else {
                    $.ajax({
                        type: 'GET',
                        url: "{{ route('search.members', $group) }}",
                        data: {
                            search: $(this).val()
                        },
                        success: function (data) {
                            $('.member-list').hide();
                            if(data.length === 0) {
                                $('#search-results').html('<h2 style="text-align: center; color: #333">No results</h2>');
                            } else {
                                $('#search-results').html('<div class="member-list"></div>');
                                $.each(data, function(index, member) {
                                    var avatar = "{{ asset('storage/avatars') }}" + '/' + member.avatar;
                                    var url = "{{ route('profiles.show', ':id') }}".replace(':id', member.id);
                                    $('#search-results').find('.member-list').append(
                                        '<div class="member-item">' +
                                            '<a href="' + url + '">' +
                                                '<div class="member-avatar">' +
                                                    '<img src="' + avatar + '" alt="avatar">' +
                                                '</div>' +
                                                '<div class="member-details">' +
                                                    '<div style="display: flex">' +
                                                        '<div class="member-name">' + member.name + '</div>' +
                                                    '</div>' +
                                                    '<div class="member-email">' + member.email + '</div>' +
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

            $('.delete-member').click(function () {
                var chatItem = $(this).closest('.member-item');

                if(confirm('Are you sure you want to remove this member?')) {
                    $.ajax({
                        type: 'DELETE',
                        url: "{{ route('groups.remove.member', ['group' => $group, 'user' => ':id']) }}".replace(':id', $(this).data('id')),
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

        Echo.join('groups.' + '{{ $group->id }}')
            .here((users) => {
                $.each(users, function (index, value) {
                    $('.member-item[data-id="' + value.id + '"]').find('.member-status').text('| Online');
                });
            })
            .joining((user) => {
                $('.member-item[data-id="' + user.id + '"]').find('.member-status').text('| Online');
            })
            .leaving((user) => {
                $('.member-item[data-id="' + user.id + '"]').find('.member-status').text('| Offline');
            });
    </script>
@endsection
