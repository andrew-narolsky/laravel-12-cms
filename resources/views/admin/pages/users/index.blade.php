@extends('layouts.admin')

@section('title', 'Users')
@section('wrapper', 'users-page')

@section('content')

    <div class="page-header mb-3">
        <div class="title-wrapper mb-2">
            <div class="col-auto d-block">
                <h3 class="page-title">
                    <span class="page-title-icon bg-gradient-primary text-white me-2">
                        <i class="mdi mdi-account menu-icon"></i>
                    </span> Users
                </h3>
            </div>
            <div class="col-auto ms-auto text-end mt-n1">
                <a href="{{ route('users.create') }}" class="btn btn-primary">New User</a>
            </div>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Users</li>
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
                                <th style="width: 70px"> </th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td class="py-1">
                                        <img src="{{ $user->avatar?->getFileUrl() ?: asset('/build/images/user-default-image.jpg')  }}" alt="{{ $user->name }}">
                                    </td>
                                    <td>
                                        {{ $user->name }}
                                    </td>
                                    <td>
                                        {{ $user->email }}
                                    </td>
                                    <td>
                                        @if($user->role?->slug === 'admin')
                                            <label class="badge" style="background-color: #1bcfb4">{{ $user->role?->name }} </label>
                                        @else
                                            <label class="badge" style="background-color: #144fff">{{ $user->role?->name }} </label>
                                        @endif
                                    </td>
                                    <td class="d-flex flex-row justify-content-end">
                                        <a href="{{ route('users.edit', $user) }}" type="button" class="btn btn-info btn-icon">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        @if($currentUser->id != $user->id)
                                            <button type="button"
                                                    class="btn btn-danger btn-icon"
                                                    onclick="FormHelper.deleteElement(this)"
                                                    form="delete-user-{{ $user->id }}">
                                                <i class="mdi mdi-delete"></i>
                                            </button>

                                            <form method="POST" id="delete-user-{{ $user->id }}" action="{{ route('users.destroy', $user) }}">
                                                @method('DELETE')
                                                @csrf
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $users->appends(request()->input())->links('admin.partials.pagination') }}
                </div>
            </div>
        </div>
    </div>

@endsection
