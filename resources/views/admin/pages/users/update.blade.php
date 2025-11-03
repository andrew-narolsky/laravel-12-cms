@extends('layouts.admin')

@section('title', 'Update user')
@section('wrapper', 'users-page')

@section('content')

    <div class="page-header mb-3">
        <div class="title-wrapper mb-2">
            <div class="col-auto d-block">
                <h3 class="page-title">
                    <span class="page-title-icon bg-gradient-primary text-white me-2">
                        <i class="mdi mdi-account menu-icon"></i>
                    </span> Update "{{ $user->name }}"
                </h3>
            </div>
            <div class="col-auto ms-auto text-end mt-n1">
                <button type="submit" form="users-form" class="btn btn-primary" id="update">Update</button>
            </div>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('users.index') }}">Users</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Update</li>
            </ol>
        </nav>
    </div>

    @include('admin.pages.users._form', ['user' => $user, 'method' => 'PUT', 'action' => route('users.update', $user) ])

@endsection
