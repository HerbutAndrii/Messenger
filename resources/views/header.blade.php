<header class="header">
    <div class="user-info">
        <img src="{{ asset('storage/avatars/' . auth()->user()->avatar) }}" alt="avatar">
        <span>{{ auth()->user()->name }}</span>
    </div>
    <nav class="nav-links">
        <a href="{{ route('chats.index') }}">Chats</a>
        <a href="{{ route('contacts.index') }}">Contacts</a>
    </nav>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button class="logout" type="submit">Logout</button>
    </form>
</header>