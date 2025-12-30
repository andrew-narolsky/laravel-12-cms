<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form id="settings-form" enctype="multipart/form-data" method="POST" action="{{ $action }}">
                    <div class="w-350-auto">
                        @csrf
                        <input type="hidden" name="_method" value="{{ $method }}">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input name="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Name" value="{{ old('name', $setting->name ?? '')}}">
                            @error('name')
                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input name="slug" type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" placeholder="Slug" value="{{ old('slug', $setting->slug ?? '')}}">
                            @error('name')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="value">Value</label>
                            <input name="value" type="text" class="form-control @error('value') is-invalid @enderror" id="value" placeholder="Value" value="{{ old('value', $setting->value ?? '') }}">
                            @error('value')
                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            new Settings();
        });
    </script>
@stop
