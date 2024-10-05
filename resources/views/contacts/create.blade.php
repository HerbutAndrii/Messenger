@extends('layout')
@section('title', 'New Contact')
@section('content')
@include('header')
    <div class="create-contact-container">
        <div class="create-contact-form">
            <h1>Create a New Contact</h1>
            <form action="{{ route('contacts.store') }}" method="POST">
                @csrf
                <label>
                    Name <br>
                    <input type="text" name="name" placeholder="Enter contact name">
                </label>
                @error('name')
                    <div style="color: red; font-size: 20px; margin-bottom: 20px" >{{ $message }}</div>
                @enderror
                <label>
                    Email <br>
                    <input type="email" name="email" placeholder="Enter contact email">
                </label>
                @error('email')
                    <div style="color: red; font-size: 20px; margin-bottom: 20px" >{{ $message }}</div>
                @enderror
                <button type="submit">Create Contact</button>
            </form>
        </div>
@endsection 