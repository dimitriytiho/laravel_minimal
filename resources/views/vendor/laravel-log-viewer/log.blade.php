@extends('layouts.admin')
{{--


Вывод контента --}}
@section('content')
    <div class="container-fluid log-viewer">
        <div class="row">
            <div class="col-12 sidebar-log pr-0 pl-0">
                <div class="list-group list-group-horizontal mb-4 div-scroll">
                    @if($folders)
                        @foreach($folders as $folder)
                            <div class="list-group-item">
                                <a href="?f={{ \Illuminate\Support\Facades\Crypt::encrypt($folder) }}">
                                    <span class="fa fa-folder"></span>
                                    <span>{{ $folder }}</span>
                                </a>
                                @if($current_folder == $folder)
                                    <div class="list-group folder">
                                        @foreach($folder_files as $file)
                                            <a href="?l={{ \Illuminate\Support\Facades\Crypt::encrypt($file) }}&f={{ \Illuminate\Support\Facades\Crypt::encrypt($folder) }}" class="btn list-group-item @if($current_file == $file) llv-active @endif">{{ $file }}</a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif

                    @if($files)
                        @foreach($files as $file)
                            <a href="?l={{ \Illuminate\Support\Facades\Crypt::encrypt($file) }}" class="btn list-group-item @if($current_file == $file) llv-active @endif">{{ $file }}</a>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="col-12 table-container pr-0 pl-0">
                @if($logs === null)
                    <div>{{ __('log-viewer.log_file_50m') }}</div>
                @else
                    <table id="table-log" class="table table-striped" data-ordering-index="{{ $standardFormat ? 2 : 0 }}">
                        <thead>
                        <tr>
                            @if($standardFormat)
                                <th class="no-wrap">{{ __('log-viewer.level') }}</th>
                                <th class="no-wrap">{{ __('log-viewer.context') }}</th>
                                <th>{{ __('log-viewer.date') }}</th>
                            @else
                                <th>{{ __('log-viewer.line_number') }}</th>
                            @endif
                            <th>{{ __('log-viewer.content') }}</th>
                        </tr>
                        </thead>
                        <tbody>

                        @if($logs)
                            @foreach($logs as $key => $log)
                                <tr data-display="stack{{{$key}}}">
                                    @if($standardFormat)
                                        <td class="nowrap text-{{{$log['level_class']}}}">
                                            <span class="fa fa-{{{$log['level_img']}}}" aria-hidden="true"></span>&nbsp;&nbsp;{{$log['level']}}
                                        </td>
                                        <td class="text">{{$log['context']}}</td>
                                    @endif
                                    <td class="date">{{{$log['date']}}}</td>
                                    <td class="text">
                                        @if($log['stack'])
                                            <button type="button" class="float-right expand btn btn-outline-dark btn-sm mb-2 ml-2" data-display="stack{{{$key}}}">
                                                <span class="fa fa-search"></span>
                                            </button>
                                        @endif
                                        {{{$log['text']}}}
                                        @if(isset($log['in_file']))
                                            <br>{{{$log['in_file']}}}
                                        @endif
                                        @if($log['stack'])
                                            <div class="stack" id="stack{{{$key}}}"
                                                 style="display: none; white-space: pre-wrap;">{{{ trim($log['stack']) }}}
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                @endif
                <div class="p-3">
                    @if($current_file)
                        <a href="?dl={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}" class="mr-3">
                            <span class="fa fa-download"></span>
                            <span>{{ __('log-viewer.download_file') }}</span>
                        </a>
                        <a id="clean-log"
                           href="?clean={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}" class="mr-3">
                            <span class="fa fa-sync"></span>
                            <span>{{ __('log-viewer.clean_file') }}</span>
                        </a>
                        <a id="delete-log"
                           href="?del={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}" class="mr-3">
                            <span class="fa fa-trash"></span>
                            <span>{{ __('log-viewer.delete_file') }}</span>
                        </a>
                        @if(count($files) > 1)
                            <a id="delete-all-log"
                               href="?delall=true{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                                <span class="fa fa-trash-alt"></span>
                                <span>{{ __('log-viewer.delete_all_files') }}</span>
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{--

        jQuery for Bootstrap --}}
    {{--<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>--}}
    {{--

    FontAwesome --}}
    {{--<script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>--}}
    {{--

    Datatables --}}
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.table-container tr').on('click', function () {
                $('#' + $(this).data('display')).toggle();
            });
            $('#table-log').DataTable({
                "order": [$('#table-log').data('orderingIndex'), 'desc'],
                "stateSave": true,
                "stateSaveCallback": function (settings, data) {
                    window.localStorage.setItem("datatable", JSON.stringify(data));
                },
                "stateLoadCallback": function (settings) {
                    var data = JSON.parse(window.localStorage.getItem("datatable"));
                    if (data) data.start = 0;
                    return data;
                }
            });
            $('#delete-log, #clean-log, #delete-all-log').click(function () {
                return confirm("{{ __('log-viewer.are_you_sure') }}");
            });
        });
    </script>
@endsection
