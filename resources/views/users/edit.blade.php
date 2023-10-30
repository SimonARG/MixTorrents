<x-layout>
    <div class="content-container">
        <div class="content-panel user-edit">
            <h1 class="page-title">Edit your account</h1>
            <form method="POST" enctype="multipart/form-data" action="{{ route('users.update', Auth::user()->id) }}">
                @csrf
                @method('PATCH')

                <div class="flex-c">
                    <div class="user-edit-1 flex-v">
                        <div class="user-edit-2 flex-c f-al-strt">
                            <label class="descriptor" for="name">Username</h3></label>
                            <span class="subtitle">Unique username, at least 6 characters</span>
                            <input type="text" name="name" id="name"
                            @if ($errors->first('name'))
                            class="fillable ph-error" 
                            placeholder="@error('name'){{ $message }}@enderror"
                            @elseif (isset(Auth::user()->name))
                            class="fillable" 
                            placeholder="Display name"
                            value="{{ Auth::user()->name }}"
                            @elseif (!(isset(Auth::user()->name)))
                            class="fillable" 
                            placeholder="Change your username"
                            value="{{ old('name') }}"
                            @endif>

                            <label class="descriptor" for="email">E-mail</h3></label>
                            <span class="subtitle">E-mail for account security</span>
                            <input type="text" name="email" id="email"
                            @if ($errors->first('email'))
                            class="fillable ph-error" 
                            placeholder="@error('email'){{ $message }}@enderror"
                            @elseif (isset(Auth::user()->email))
                            class="fillable" 
                            placeholder="example@email.com"
                            value="{{ Auth::user()->email }}"
                            @elseif (!(isset(Auth::user()->email)))
                            class="fillable" 
                            placeholder="Change your email"
                            value="{{ old('email') }}"
                            @endif>

                            <label class="descriptor" for="about">About</h3></label>
                            <span class="subtitle">Tell us something about you</span>
                            <textarea id="about" name="about" 
                            @if ($errors->first('about'))
                            class="fillable markdown-source ph-error"
                            placeholder="@error('about'){{ $message }}@enderror"
                            @else
                            class="fillable markdown-source"
                            placeholder="..."
                            @endif
                            @if (!(isset(Auth::user()->about)))
                            >{{{ old('about') }}}</textarea>
                            @elseif (isset(Auth::user()->about))
                            >{{{ Auth::user()->about }}}</textarea>
                            @endif

                            <label class="descriptor" for="new-password">New password</h3></label>
                            <span class="subtitle">Minimum 8 characters with a number, uppercase letter, lowercase letter, and symbol</span>
                            <input type="password" name="new-password" id="new-password"
                            @if ($errors->first('password'))
                            class="fillable ph-error" 
                            placeholder="@error('new-password'){{ $message }}@enderror"
                            @else
                            class="fillable" 
                            placeholder="Enter a new password"
                            value="{{ old('new-password') }}"
                            @endif>

                            <label class="descriptor" for="password-repeat">Repeat the new password</h3></label>
                            <span class="subtitle">Hopefully they match!</span>
                            <input type="password" name="new-password_confirmation" id="password-repeat"
                            @if ($errors->first('new-password_confirmation'))
                            class="fillable ph-error" 
                            placeholder="@error('new-password_confirmation'){{ $message }}@enderror"
                            @else
                            class="fillable" 
                            placeholder="Repeat the new password"
                            value="{{ old('new-password_confirmation') }}"
                            @endif>
                        </div>

                        <div class="user-edit-3 flex-c f-al-cent">
                            <div class="img-container">
                                <img src="{{url('storage/avatars/'.Auth::user()->pic)}}" alt="Avatar">
                            </div>

                                <label class="descriptor" for="avatar">Avatar</label>
                                <div class="file-container flex-v">
                                    <label class="in-btn upload-btn" for="avatar">Browse...</label>
                                    <input type="text" readonly 
                                    @if ($errors->first('avatar'))
                                    class="upload-field ph-error"
                                    placeholder="@error('avatar'){{$message}}@enderror"
                                    @else
                                    class="upload-field"
                                    value="{{ Auth::user()->pic }}"
                                    @endif>
                                    <input class="hidden" id="avatar" name="avatar" type="file" accept=".jpg,.png,.avif,.webp,.gif">
                                </div>
                        </div>
                    </div>

                    <hr>
                
                    <div class="flex-c f-al-cent">
                        <label class="descriptor" for="password">Current password</h3></label>
                        <span class="subtitle">So we know it's you</span>
                        <input type="password" name="password" id="password" required
                        @if ($errors->first('password'))
                        class="fillable ph-error" 
                        placeholder="@error('password'){{ $message }}@enderror"
                        @else
                        class="fillable" 
                        placeholder="Enter your password"
                        value="{{ old('password') }}"
                        @endif>
                    </div>

                    <button type="submit" class="auth-btn btn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</x-layout>