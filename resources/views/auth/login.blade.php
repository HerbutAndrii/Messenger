@extends('layout')
@section('title', 'Login')
@section('content')
    <div class="auth-form-container">
        <div class="auth-form">
            <h1>Login</h1>
            <form action="{{ route('login') }}" method="POST">
                @csrf
                @error('login')
                    <div style="color: red; font-size: 20px; margin-bottom: 20px; text-align:center" >{{ $message }}</div>
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
                @error('email')
                    <div style="color: red; font-size: 20px; margin-bottom: 20px" >{{ $message }}</div>
                @enderror 
                <button type="submit">Login</button>
            </form>
            <a href="{{ route('register.show') }}" class="register-link">Create an account</a>
        </div>
    </div>
@endsection