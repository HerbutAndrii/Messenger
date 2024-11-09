@extends('layout')
@section('title', 'Register')
@section('content')
    <div class="auth-form-container">
        <div class="auth-form">
            <h1>Register</h1>
            <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label>
                    Name <br> 
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter your name"> <br>
                </label> 
                @error('name')
                    <div style="color: red; font-size: 20px; margin-bottom: 20px" >{{ $message }}</div>
                @enderror
                <label>
                    Email <br> 
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email"> <br>
                </label> 
                @error('email')
                    <div style="color: red; font-size: 20px; margin-bottom: 20px" >{{ $message }}</div>
                @enderror
                <label>
                    Password <br>
                    <input type="password" name="password" value="{{ old('password') }}" placeholder="Enter your password"> <br> 
                </label> <br>
                @error('password')
                    <div style="color: red; font-size: 20px; margin-bottom: 20px" >{{ $message }}</div>
                @enderror 
                <label>
                    Avatar <br>
                    <input type="file" name="avatar"> <br> 
                </label> <br>
                @error('avatar')
                    <div style="color: red; font-size: 20px; margin-bottom: 20px" >{{ $message }}</div>
                @enderror 
                <button type="submit">Register</button>
            </form>
        </div>
    </div>
@endsection