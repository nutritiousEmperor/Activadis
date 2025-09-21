<form action="{{ route('posts.store') }}" method="POST">
    @csrf {{-- Laravel requires this for security --}}

    <div>
        <label for="title">Title</label>
        <input type="text" name="title" id="title" value="{{ old('title') }}">
        @error('title')
            <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="name">Naam</label>
        <input type="text" name="name" id="name" value="{{ old('name') }}">
        @error('name')
            <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="email">E-mail</label>
        <input type="email" name="email" id="email" value="{{ old('email') }}">
        @error('email')
            <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="functieTitel">Functie Titel</label>
        <input type="text" name="functieTitel" id="functieTitel" value="{{ old('functieTitel') }}">
        @error('functieTitel')
            <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="rol">User</label>
        <input type="radio" name="rol" id="rol" value="user" checked>
        <label for="rol">Admin</label>
        <input type="radio" name="rol" id="rol" value="admin">
        @error('rol')
            <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>

    

   

    <button type="submit">Save</button>
</form>
