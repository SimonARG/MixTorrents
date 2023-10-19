<x-layout>
    <div class="content-container">
        <div class="content-panel auth-container flec-c f-just-cent f-al-cent">
            <h1 class="page-title">Register an account</h1>
            <form class="flex-c f-al-cent" method="POST" action="{{ route('users.store') }}">
                @csrf
                <label for="username"><h3>Username</h3></label>
                <span>Unique username, at least 6 characters</span>
                <input type="text" name="name" id="name" required
                @if ($errors->first('name'))
                class="fillable ph-error" 
                placeholder="@error('name'){{ $message }}@enderror"
                @else
                class="fillable" 
                placeholder="Choose a username"
                value="{{ old('name') }}"
                @endif>

                <label for="email"><h3>E-mail</h3></label>
                <span>E-mail for account security</span>
                <input type="text" name="email" id="email" required
                @if ($errors->first('email'))
                class="fillable ph-error" 
                placeholder="@error('email'){{ $message }}@enderror"
                @else
                class="fillable" 
                placeholder="Enter your e-mail"
                value="{{ old('email') }}"
                @endif>
            
                <label for="psw"><h3>Password</h3></label>
                <span>Minimum 8 characters with a number, uppercase letter, lowercase letter, and symbol</span>
                <input type="password" name="password" id="password" required
                @if ($errors->first('password'))
                class="fillable ph-error" 
                placeholder="@error('password'){{ $message }}@enderror"
                @else
                class="fillable" 
                placeholder="Enter a password"
                value="{{ old('password') }}"
                @endif>
            
                <label for="psw-repeat"><h3>Repeat the password</h3></label>
                <span>Hopefully they match!</span>
                <input type="password" name="password_confirmation" id="password-repeat" required
                @if ($errors->first('password_confirmation'))
                class="fillable ph-error" 
                placeholder="@error('password_confirmation'){{ $message }}@enderror"
                @else
                class="fillable" 
                placeholder="Repeat the password"
                value="{{ old('password_confirmation') }}"
                @endif>

                <button type="submit" class="auth-btn btn">Register</button>
            </form>
        </div>
    </div>
</x-layout>