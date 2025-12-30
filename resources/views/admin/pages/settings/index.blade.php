@extends('layouts.admin')

@section('title', 'Settings')
@section('wrapper', 'settings-page')

@section('content')

    <div class="page-header mb-3">
        <div class="title-wrapper mb-2">
            <div class="col-auto d-block">
                <h3 class="page-title">
                    <span class="page-title-icon bg-gradient-primary text-white me-2">
                        <i class="mdi mdi-image-filter-vintage menu-icon"></i>
                    </span> Settings
                </h3>
            </div>
            <div class="col-auto ms-auto text-end mt-n1">
                <a href="{{ route('settings.create') }}" class="btn btn-primary">New Setting</a>
            </div>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Settings</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Value</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($settings as $setting)
                            <tr>
                                <td>
                                    {{ $setting->name }}
                                </td>
                                <td>
                                    {{ $setting->value }}
                                </td>
                                <td class="d-flex flex-row justify-content-end">
                                    <a href="{{ route('settings.edit', $setting) }}" type="button" class="btn btn-info btn-icon">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-danger btn-icon"
                                            onclick="FormHelper.deleteElement(this)"
                                            form="delete-user-{{ $setting->id }}">
                                        <i class="mdi mdi-delete"></i>
                                    </button>

                                    <form method="POST" id="delete-user-{{ $setting->id }}" action="{{ route('settings.destroy', $setting) }}">
                                        @method('DELETE')
                                        @csrf
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $settings->appends(request()->input())->links('admin.partials.pagination') }}
                </div>
            </div>
        </div>
    </div>

@endsection
