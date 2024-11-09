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
                <form action="{{ route('chats.store', $user) }}" method="POST">
                    @csrf
                    <button class="contact-chat" type="submit">Chat</button>
                </form>
                <form action="{{ route('contacts.store', $user) }}" method="POST">
                    @csrf
                    <button class="add-contact" type="submit">Add To Contacts</button>
                </form>
            </div>
        </div>
    </div>
@endsection