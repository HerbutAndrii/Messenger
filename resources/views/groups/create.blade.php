@extends('layout')
@section('title', 'New Group')
@section('content')
@include('header')
    <div class="create-group-container">
        <div class="create-group-form">
            <h1>Create Group</h1>
            <form action="{{ route('groups.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label>
                    Name 
                    <input type="text" name="name" placeholder="Enter group name" value="{{ old('name') }}">
                </label>
                @error('name')
                    <div style="color: red; font-size: 20px; margin-top: 10px" >{{ $message }}</div>
                @enderror
                <div class="user-selection-container">
                    <label>
                        <h3>Users</h3>
                        <select name="users[]" multiple>
                            @foreach($contacts as $contact)
                                <option value="{{ $contact->user_id }}" {{ in_array($contact->user_id, old('users', [])) ? 'selected' : ''}}>
                                    {{ $contact->name }}
                                </option>
                            @endforeach
                        </select>
                    </label> 
                </div>
                @error('users')
                    <div style="color: red; font-size: 20px; margin-bottom: 20px" >{{ $message }}</div>
                @enderror
                <label>
                    Avatar <br>
                    <input type="file" name="avatar"> <br> 
                </label> <br>
                @error('avatar')
                    <div style="color: red; font-size: 20px; margin-bottom: 20px" >{{ $message }}</div>
                @enderror 
                <button type="submit">Create Group</button>
            </form>
        </div>
@endsection 