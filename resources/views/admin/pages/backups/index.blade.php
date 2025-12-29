@extends('layouts.admin')

@section('title', 'Backups')

@section('content')

    <div class="page-header mb-3">
        <div class="title-wrapper mb-2">
            <div class="col-auto d-block">
                <h3 class="page-title">
                    <span class="page-title-icon bg-gradient-primary text-white me-2">
                        <i class="mdi mdi-account menu-icon"></i>
                    </span> Backups
                </h3>
            </div>

            <div class="col-auto ms-auto text-end mt-n1">
                <button type="button" class="btn btn-primary" id="make-backup">Make backup</button>
                <button type="button" class="btn btn-gradient-success btn-fw" data-bs-toggle="modal" data-bs-target="#restore-modal">Upload backup</button>
            </div>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Backups</li>
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
                                <th style="width: 20px;"></th>
                                <th>File name</th>
                                <th>File size</th>
                                <th>Creating date</th>
                                <th>Progress</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($backups as $backup)
                                <tr data-id="{{ $backup->id }}">
                                    <td>{{ $loop->index + 1 }}.</td>
                                    <td>
                                        <span><i class="mdi mdi mdi-database"></i> {{ $backup->file }}</span>
                                    </td>
                                    <td>{{ $backup->getHumanSize() }}</td>
                                    <td>
                                        <i class="fa fa-calendar"></i> {{ $backup->created_at }}
                                    </td>
                                    <td>
                                        <div class="progress progress-md progress-transparent">
                                            <div class="progress-bar bg-info" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>
                                    <td class="d-flex flex-row justify-content-end">
                                        <a href="{{ route('backups.download', $backup) }}" class="btn btn-success btn-icon">
                                            <i class="mdi mdi-download"></i>
                                        </a>
                                        <button class="btn btn-info btn-icon" data-restore-backup>
                                            <i class="mdi mdi-cached"></i>
                                        </button>
                                        <button class="btn btn-danger btn-icon" data-remove-backup>
                                            <i class="mdi mdi-delete-forever"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('modals')
    @include('admin.pages.backups._restore')
@endpush

@section('js')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            new Backups();
        });
    </script>
@endsection
