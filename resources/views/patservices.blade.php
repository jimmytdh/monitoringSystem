@extends('app')

@section('css')
    <link rel="stylesheet" href="{{ url('/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" />
    <style>
        .list-menu {
            font-weight: bold;
            color: #0c5460;
        }
    </style>
@endsection

@section('body')
    <h2 class="text-success title-header">{{ $name }} <small class="text-muted"></small></h2>
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="list-group list-menu">
                        <a href="#services" data-service="laboratory" data-toggle="modal" class="list-group-item list-group-item-action">
                            <i class="fa fa-medkit"></i> LABORATORY
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fa fa-heartbeat"></i> RADIOLOGY
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fa fa-h-square"></i> PHARMACY
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fa fa-user-md"></i> COURSE IN THE WARD
                        </a>
                    </div>
                </div>
                <!-- /.box -->
            </div>
            <div class="col-md-9">
                <!-- general form elements -->
                @if(session('status')=='saved')
                    <div class="alert alert-success">
                        <i class="fa fa-check-circle"></i> Successfully Saved!
                    </div>
                @endif

                @if(session('status')=='updated')
                    <div class="alert alert-info">
                        <i class="fa fa-check-circle"></i> Successfully Updated!
                    </div>
                @endif

                <div class="box box-primary">
{{--                    <div class="box-header with-border">--}}
{{--                        <h3 class="box-title"></h3>--}}
{{--                    </div>--}}

                    <div class="box-body">
                        @if(count($data)>0)
                            <table class="table table-sm">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Section</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $row)
                                    <tr>
                                        <td><a href="{{ url('/library/services/'.$row->id) }}" class="editable-click">{{ str_pad($row->id,3,0,STR_PAD_LEFT) }}</a></td>
                                        <td>{{ strtoupper($row->section) }}</td>
                                        <td>{{ $row->name }}</td>
                                        <td>{{ $row->type }}</td>
                                        <td class="text-red">{{ number_format($row->amount,2) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="mt-4"></div>
                            {{ $data->links() }}
                        @else
                            <img src="{{ url("/images/no_result_found.gif") }}" class="img-thumbnail" width="100%" alt="">
                        @endif
                    </div>
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
@endsection

@section('modal')
    @include('modal.services')
@endsection

@section('js')
    <script src="{{ url('/plugins/bootstrap-daterangepicker/moment.min.js') }}"></script>
    <script src="{{ url('/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script>
        var loading = "{{ url('/library/loading') }}";

        $('a[href="#services"]').on('click',function(){
            var service = $(this).data('service');
            $('.service_title').html(service.toUpperCase());
            var url = "{{ url('/admitted/services/'.$id) }}/"+service;
            $('.service_content').load(url,function(){
                $('#datetimepicker').daterangepicker({
                    "singleDatePicker": true,
                    "timePicker": true,
                    "startDate": "{{ date('m/d/Y H:i') }}",
                    "locale" : {
                        "format" : "MM/DD/YYYY HH:mm"
                    }
                });
            });
        });

        $('#services').on('hidden.bs.modal', function () {
            $('.service_title').html('[Title here...]');
            $('.service_content').load(loading);
        });

    </script>
@endsection
