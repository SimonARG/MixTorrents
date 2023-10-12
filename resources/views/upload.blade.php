<x-layout>
    <div class="content-container">
        <div class="content-panel upload-container">
            <h1 class="page-title">Upload Torrent</h1>
            <form class="upload-form" method="POST" enctype="multipart/form-data" action="{{route('uploads.store')}}">
                @csrf

                <p class="advice"><strong>Important:</strong> make sure you have read <strong><a href="{{ route('rules') }}">the rules</a></strong> before uploading!</p>

                <br>

                <div class="form-container">
                    <label class="descriptor" for="torrent_file">Torrent file</label>
                    <div class="file-container">
                        <label class="in-btn upload-btn" for="torrent_file">Browse...</label>
                        <input type="text" readonly 
                        @if ($errors->first('torrent_file'))
                        class="upload-field ph-error"
                        placeholder="@error('torrent_file'){{$message}}@enderror"
                        @else
                        class="upload-field"
                        placeholder=".torrent"
                        @endif>
                        <input class="hidden" id="torrent_file" name="torrent_file" required type="file" accept=".torrent">
                    </div>
                </div>

                <div class="info-container--flex">

                    <div class="info">
                        <label class="descriptor" for="name">Torrent display name (optional)</label>
                        <input id="display_name" name="name" type="text"
                        @if ($errors->first('name'))
                        class="fillable ph-error" 
                        placeholder="@error('name'){{ $message }}@enderror"
                        @else
                        class="fillable" 
                        placeholder="Display name"
                        value="{{ old('name') }}"
                        @endif>

                        <label class="descriptor" for="info">Information (optional)</label>
                        <input id="information" name="info" type="text" 
                        @if ($errors->first('info'))
                        class="fillable ph-error"
                        placeholder="@error('info'){{ $message }}@enderror"
                        @else
                        class="fillable"
                        placeholder="Short uploader info and/or link"
                        value="{{ old('info') }}"
                        @endif>
                    </div>

                    <div class="info">
                        <label class="descriptor" for="category">Category</label>
                        <select id="category" name="category"
                        @if ($errors->first('category'))
                        class="category-select select-error"
                        @else
                        class="category-select"
                        value="{{ old('category') }}"
                        @endif>
                            @if ($errors->first('category'))
                            <option value="0">@error('category'){{ $message }}@enderror</option>
                            @else
                            @endif
                        </select>

                        <label class="descriptor" for="subcat">Sub-category</label>
                        <select id="subcategory" name="subcat"
                        @if ($errors->first('subcat'))
                        class="category-select select-error"
                        @else
                        class="category-select"
                        value="{{ old('subcat') }}"
                        @endif>>
                            @if ($errors->first('subcat'))
                            <option value="0">@error('subcat'){{ $message }}@enderror</option>
                            @else
                            <option value="0">Select a sub-category</option>
                            @endif
                        </select>
                    </div>

                </div>

                <label class="descriptor tabs-desc" for="description">
                    Description (optional) (<a href="https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet" class="small" target="_blank">Markdown supported</a>)
                </label>

                <ul class="editor-tabs">
                    <li class="input-tab tab-active">
                        <a href="#description">
                            <span>Write</span>
                        </a>
                    </li>
                    <li class="description-tab tab-inactive">
                        <a href="#description-preview">
                            <span>Preview</span>
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active">
                        <textarea id="description" name="description" 
                        @if ($errors->first('description'))
                        class="fillable markdown-source ph-error"
                        placeholder="@error('description'){{ $message }}@enderror"
                        @else
                        class="fillable markdown-source"
                        placeholder="Upload description..."
                        @endif>{{{ old('description') }}}</textarea>
                    </div>

                    <div class="tab-pane">
                        <div class="markdown-preview" id="description-preview"></div>
                    </div>
                </div>

                <input class="end-btn" type="submit" value="Upload">

                <div class="dot-container" data-title="dot-pulse">
                    <div class="stage">
                        <div class="dot-pulse"></div>
                    </div>
                </div>

            </form>

        </div>
    </div>
</x-layout>