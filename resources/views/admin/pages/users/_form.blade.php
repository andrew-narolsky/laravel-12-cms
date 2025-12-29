<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form id="users-form" enctype="multipart/form-data" method="POST" action="{{ $action }}">
                    <div class="w-350-auto">
                        @csrf
                        <input type="hidden" name="_method" value="{{ $method }}">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input name="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Name" value="{{ old('name', $user->name ?? '')}}">
                            @error('name')
                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Email" value="{{ old('email', $user->email ?? '') }}">
                            @error('email')
                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Password" value="{{ old('password') }}">
                            @error('password')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="role_id">Role</label>
                            <select name="role_id" id="role_id">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" @selected(old('role_id', $user->role?->id ?? 0) === $role?->id)>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @isset($user)
                            <div class="form-group">
                                <label for="file">Avatar</label>
                                @include('admin.partials.uploader', [
                                    'module' => $user::MODULE_NAME,
                                    'moduleId' => $user->id ?? null,
                                    'id' => $user->avatar->id ?? null,
                                    'file' => $user->avatar?->getFileUrl() ?? null,
                                    'allowed' => 'images',
                                    'uploadUrl' => route('attachments.upload'),
                                    'deleteUrl' => route('attachments.destroy', ['attachment' => '__ID__']),
                                ])
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            new Users();
        });
    </script>
@stop
