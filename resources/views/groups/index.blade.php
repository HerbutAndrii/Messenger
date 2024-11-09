@extends('layout')
@section('title', 'Groups')
@section('content')
@include('header')
    <a href="{{ route('groups.create') }}" class="group-create-link">Create Group</a>
    <div class="group-list-container">
        <div class="group-list-header">
            <h1>Your Groups</h1>
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
                    </a>
                    <span class="delete-group" data-id="{{ $group->id }}">Leave</span>
                </div>
            @endforeach
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $('.delete-group').click(function () {
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
        });
    </script>
@endsection