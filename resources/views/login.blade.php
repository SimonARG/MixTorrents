<x-layout>
    <div class="content-container">
        <div class="content-panel auth-container flec-c f-just-cent f-al-cent">
            <h1 class="page-title">Log into your account</h1>
            <form class="flex-c f-al-cent" method="POST" action="{{ route('users.authenticate') }}">
                @csrf
                <label for="username"><h3>Username</h3></label>
                <input type="text" name="name" id="name" required
                @if ($errors->first('name'))
                class="fillable ph-error"
                placeholder="@error('name'){{ $message }}@enderror"
                @else
                class="fillable"
                placeholder="Enter your username"
                @endif>
            
                <label for="psw"><h3>Password</h3></label>
                <input type="password" name="password" id="password" required
                @if ($errors->first('name'))
                class="fillable ph-error"
                placeholder="@error('name'){{ $message }}@enderror"
                @else
                class="fillable"
                placeholder="Enter your password"
                @endif>

                <button type="submit" class="auth-btn btn">Login</button>
            </form>
        </div>
    </div>
</x-layout>