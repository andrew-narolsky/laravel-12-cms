<div class="file-uploader @if($file) loaded @endif"
     data-upload-url="{{ route('attachments.upload') }}"
     data-delete-url="{{ route('attachments.destroy', ['attachment' => '__ID__']) }}"
     data-module="{{ $module }}"
     data-module-id="{{ $moduleId ?? '' }}">
    <div class="drop-zone">
        <span class="text">
            <i class="mdi mdi-upload"></i>
        </span>
        <input type="file" id="file">
    </div>
    <div class="preview-list">
        @if($file)
            <div class="preview-item uploaded" data-id="{{ $attachment }}" data-url="{{ $file }}">
                <div class="thumb">
                    <img src="{{ $file }}" alt="{{ $module }}" loading="lazy">
                </div>
                <div class="info">
                    <button class="copy-btn btn btn-info">
                        <i class="mdi mdi-link"></i>
                    </button>
                    <button class="delete-btn btn btn-danger">
                        <i class="mdi mdi-window-close"></i>
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
