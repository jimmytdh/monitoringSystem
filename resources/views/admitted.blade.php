@extends('app')

@section('css')
    <link rel="stylesheet" href="{{ url('/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" />
    <style>
        .table-data { text-transform: uppercase; }
        a:hover { text-decoration: none; }
        .table-data { font-size: 0.8em; }
        table tr td { white-space: nowrap; }
    </style>
@endsection

@section('body')
    <div class="pull-right mb-2 col-lg-4 col-xs-12">
        <form action="{{ url('/admitted/search') }}" method="post">
            {{ csrf_field() }}
            <div class="input-group input-group-sm mb-2">
                <input type="text" class="form-control" name="keyword" value="{{ Session::get('admitSearch') }}" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                <div class="input-group-append">
                    <button class="btn btn-info" type="submit"><i class="fa fa-search"></i></button>
                </div>
            </div>

        </form>
    </div>
    <h2 class="text-success title-header">In-Patients <small class="text-danger"></small><font style="font-size: 0.5em" class="text-danger">[Result: {{ number_format($data->total()) }}]</font></h2>



    <section class="content">
        <div class="table-responsive">
            @if(session('status')=='admitted')
                <div class="alert alert-info">
                    <i class="fa fa-check"></i> Successfully Admitted!
                </div>
            @endif


            @if(session('status')=='updated')
                <div class="alert alert-success">
                    <i class="fa fa-check"></i> Successfully Updated!
                </div>
            @endif

            @if(session('status')=='deleted')
                <div class="alert alert-warning">
                    <i class="fa fa-check"></i> Successfully Deleted!
                </div>
            @endif

            @if(count($data)>0)
                <table class="table table-sm table-bordered table-data table-hover">
                    <thead class="bg-info text-white">
                    <tr>
                        <th>Patient No.</th>
                        <th>Date Admitted</th>
                        <th>Full Name</th>
                        <th>Sex</th>
                        <th>Age</th>
                        <th>Complete Address</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $row)
                        <?php
                            $date = 'None';
                        ?>
                        <tr>
                            <td style="font-weight: bold;">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ $row->pat_id }} </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#historyPatient" data-toggle="modal" data-id="{{ $row->id }}">History</a>
                                    <div role="separator" class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ url('/admitted/services/'.$row->id) }}">Services</a>
                                    <a class="dropdown-item" href="{{ url('/soa/'.$row->id) }}">Update SOA</a>
                                    <a class="dropdown-item" href="{{ url('/soa/print/'.$row->id) }}">Print SOA</a>
                                    <div role="separator" class="dropdown-divider"></div>
                                    <a class="dropdown-item dischargePatient text-danger" href="{{ url('/admitted/discharge/'.$row->id) }}">Discharge Patient</a>
                                </div>
                            </td>
                            <td class="{{ ($row->status!='ADM') ? 'text-danger':'' }}"><strong><small>{{ date('F d, Y',strtotime($row->date_admitted)) }}</small></strong></td>
                            <td>{{ "$row->lname, $row->fname $row->mname" }}</td>
                            <td>{{ ($row->sex=='M') ? "Male":"Female" }}</td>
                            <td>{{ \App\Http\Controllers\LibraryCtrl::getAge($row->dob) }}</td>

                            <td>
                                {{ \App\Http\Controllers\LocationCtrl::getBrgy($row->brgy) }},
                                {{ \App\Http\Controllers\LocationCtrl::getMuncity($row->muncity) }},
                                {{ \App\Http\Controllers\LocationCtrl::getProvince($row->province) }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                {{ $data->links() }}
            @else
                <div class="alert alert-warning">
                    <i class="fa fa-exclamation-triangle"></i> No data found!
                </div>
            @endif
        </div>
    </section>
@endsection

@section('modal')
    @include('modal.history')
@endsection

@section('js')
    <script src="{{ url('/plugins/bootstrap-daterangepicker/moment.min.js') }}"></script>
    <script src="{{ url('/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script>
        $(document).ready(function () {
            var loading = "{{ url('/library/loading') }}";

            $("a[href='#historyPatient']").on('click',function(){
                var id = $(this).data('id');
                var url = "{{ url('/patients/history') }}/"+id;

                setTimeout(function(){
                    $('.history_content').load(url);
                },500);
            });

            $('#historyPatient').on('hidden.bs.modal', function () {
                $('.history_content').load(loading);
            });

        });
    </script>
@endsection
