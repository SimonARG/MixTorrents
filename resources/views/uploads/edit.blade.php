<x-layout>
    <div class="content-container">
        <div class="content-panel upload-container">
            <h1 class="page-title">Edit Torrent</h1>
            <form class="upload-form" method="POST" enctype="multipart/form-data" action="{{route('uploads.update', $upload->id) }}">
                @csrf
                @method('PATCH')

                <div class="form-container flex-c">
                    <label class="descriptor" for="torrent_file">Torrent file</label>
                    <div class="file-container flex-v">
                        <label class="in-btn upload-btn" for="torrent_file">Browse...</label>
                        <input type="text" readonly 
                        @if ($errors->first('torrent_file'))
                        class="upload-field ph-error"
                        placeholder="@error('torrent_file'){{$message}}@enderror"
                        @else
                        class="upload-field"
                        value="{{ $upload->filename }}"
                        @endif>
                        <input class="hidden" id="torrent_file" name="torrent_file" type="file" accept=".torrent">
                    </div>
                </div>

                <div class="info-container flex-v f-just-bet">

                    <div class="info flex-c">
                        <label class="descriptor" for="name">Torrent display name (optional)</label>
                        <input id="display_name" name="name" type="text"
                        @if ($errors->first('name'))
                        class="fillable ph-error" 
                        placeholder="@error('name'){{ $message }}@enderror"
                        @elseif (isset($upload->title))
                        class="fillable" 
                        placeholder="Display name"
                        value="{{ $upload->title }}"
                        @elseif (!(isset($upload->title)))
                        class="fillable" 
                        placeholder="Display name"
                        value="{{ old('name') }}"
                        @endif>

                        <label class="descriptor" for="info">Information (optional)</label>
                        <input id="information" name="info" type="text" 
                        @if ($errors->first('info'))
                        class="fillable ph-error"
                        placeholder="@error('info'){{ $message }}@enderror"
                        @elseif (isset($upload->info))
                        class="fillable"
                        placeholder="Short uploader info and/or link"
                        value="{{ $upload->info }}"
                        @elseif (!(isset($upload->info)))
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
                            @elseif (isset($upload->category_id))
                            <option value={{ $upload->category_id }}>{{ $upload->category->category }}</option>
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
                            @elseif (isset($upload->subcat_id))
                            <option value={{ $upload->subcat_id }}>{{ $upload->subcat->subcat }}</option>
                            @endif
                        </select>
                    </div>

                </div>

                <label class="descriptor tabs-desc" for="description">
                    Description (optional) (<a href="https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet" class="small" target="_blank">Markdown supported</a>)
                </label>

                <ul class="editor-tabs flex-v">
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
                        @endif
                        @if (!(isset($upload->description)))
                        >{{{ old('description') }}}</textarea>
                        @elseif (isset($upload->description))
                        >{{{ $upload->description }}}</textarea>
                        @endif
                    </div>

                    <div class="tab-pane">
                        <div class="markdown-preview" id="description-preview"></div>
                    </div>
                </div>

                <input class="end-btn" type="submit" value="Edit">

                <div class="dot-container" data-title="dot-pulse">
                    <div class="stage">
                        <div class="dot-pulse"></div>
                    </div>
                </div>

            </form>

        </div>
    </div>
</x-layout>