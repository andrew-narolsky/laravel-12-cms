@extends('layouts.admin')

@section('title', 'Log Viewer')

@section('content')

    <div class="page-header mb-3">
        <div class="title-wrapper mb-2">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                    <i class="mdi mdi-alert-outline"></i>
                </span> Log Viewer
            </h3>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Log Viewer</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    @if ($logs === null)
                        <div>
                            Log file >50M, please download it.
                        </div>
                    @else
                        <table id="table-log" class="table table-striped"
                               data-ordering-index="{{ $standardFormat ? 2 : 0 }}">
                            <thead>
                            <tr>
                                @if ($standardFormat)
                                    <th>Level</th>
                                    <th>Context</th>
                                    <th>Date</th>
                                @else
                                    <th>Line number</th>
                                @endif
                                <th>Content</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($logs as $key => $log)
                                <tr data-display="stack{{{$key}}}">
                                    @if ($standardFormat)
                                        <td class="nowrap text-{{{$log['level_class']}}}" style="vertical-align: top">
                                            {{$log['level']}}
                                        </td>
                                        <td class="text" style="vertical-align: top">{{$log['context']}}</td>
                                    @endif
                                    <td class="date" style="vertical-align: top">{{{$log['date']}}}</td>
                                    <td class="text" style="vertical-align: top">
                                        <div class="d-flex" style="align-items:flex-start">
                                            @if ($log['stack'])
                                                <button type="button"
                                                        class="float-right log-expand expand btn btn-outline-dark btn-sm me-3 btn-gradient-info"
                                                        data-display="stack{{{$key}}}">
                                                    <i class="mdi mdi-information-outline"></i>
                                                </button>
                                            @endif
                                            <p class="m-0" style="white-space: normal;font-size: 12px;">{{{$log['text']}}}</p>
                                        </div>
                                        @if (isset($log['in_file']))
                                            <br/>{{{$log['in_file']}}}
                                        @endif
                                        @if ($log['stack'])
                                            <div class="stack" id="stack{{{$key}}}"
                                                 style="display: none; white-space: pre-wrap; font-size: 12px;">
                                                <pre>{{ trim($log['stack']) }}</pre>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-12 table-container">
            @if($current_file)
                <a class="btn btn-gradient-success btn-fw" href="?dl={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                    <i class="mdi mdi-arrow-down-bold-circle-outline"></i> Download file
                </a>
                <a class="btn btn-gradient-info btn-fw" id="clean-log"
                   href="?clean={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                    <i class="mdi mdi-delete-sweep"></i> Clean file
                </a>
                <a class="btn btn-gradient-warning btn-fw" id="delete-log"
                   href="?del={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                    <i class="mdi mdi-delete"></i> Delete file
                </a>
                @if(count($files) > 1)
                    <a class="btn btn-gradient-danger btn-fw" id="delete-all-log"
                       href="?delall=true{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                        <i class="mdi mdi-delete-forever"></i> Delete all files
                    </a>
                @endif
            @endif
        </div>
    </div>

@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tableLog = document.getElementById('table-log');

            if (!tableLog) return;

            tableLog.addEventListener('click', (event) => {
                const button = event.target.closest('.log-expand');
                if (!button) return;

                const displayId = button.dataset.display;
                const target = document.getElementById(displayId);

                if (target) {
                    target.style.display = target.style.display === 'none' ? '' : 'none';
                }
            });
        });
    </script>
@endsection
