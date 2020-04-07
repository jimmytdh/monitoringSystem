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
        <form action="{{ url('/patients/search') }}" method="post">
            {{ csrf_field() }}
            <div class="input-group input-group-sm mb-2">
                <div class="input-group-prepend mr-1">
                    <button class="btn btn-info" data-toggle="modal" data-target="#addPatient" type="button"><i class="fa fa-plus"></i> Add New</button>
                </div>
                <input type="text" class="form-control" name="keyword" value="{{ Session::get('patientSearch') }}" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                <div class="input-group-append">
                    <button class="btn btn-info" type="submit"><i class="fa fa-search"></i></button>
                </div>
            </div>

        </form>
    </div>
    <h2 class="text-success title-header">Manage Patients <small class="text-danger"></small><font style="font-size: 0.5em" class="text-danger">[Result: {{ number_format($data->total()) }}]</font></h2>



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

        @if(session('status')=='existed')
            <div class="alert alert-warning">
                <i class="fa fa-exclamation-triangle"></i> Oppss! The patient you selected was already admitted.
            </div>
        @endif

        @if(count($data)>0)
        <table class="table table-sm table-bordered table-data table-hover">
            <thead class="bg-info text-white">
                <tr>
                    <th>Patient No.</th>
                    <th>Consultation Date</th>
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
                    $tmp = \App\Consultation::where('pat_id',$row->id)->orderBy('id','desc')->first();
                    if($tmp)
                        $date = \Carbon\Carbon::parse($tmp->date_consultation)->format('M d, Y');
                ?>
                <tr class="{{ ($row->status=='ADM') ? 'bg-yellow':'' }}">
                    <td style="font-weight: bold;">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ $row->pat_id }} </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#infoPatient" data-toggle="modal" data-id="{{ $row->id }}">Update Patient</a>
                            <a class="dropdown-item" href="#admitPatient" data-toggle="modal" data-id="{{ $row->id }}">Admit Patient</a>
                            <a class="dropdown-item" href="#historyPatient" data-toggle="modal" data-id="{{ $row->id }}">History</a>
                            <div role="separator" class="dropdown-divider"></div>
                            <a class="dropdown-item deletePatient text-danger" href="{{ url('/patients/delete/'.$row->id) }}">Delete Patient</a>
                        </div>
                    </td>
                    <td class="{{ ($row->status!='ADM') ? 'text-danger':'' }}"><strong><small>{{ $date }}</small></strong></td>
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
                <i class="fa fa-exclamation"></i> No data found!
            </div>
        @endif
    </div>
    </section>
@endsection

@section('modal')
    @include('modal.patient')
    @include('modal.info')
    @include('modal.admit')
    @include('modal.history')
@endsection

@section('js')
<script src="{{ url('/plugins/bootstrap-daterangepicker/moment.min.js') }}"></script>
<script src="{{ url('/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script>
    $('#revisit').change(function() {
        // this will contain a reference to the checkbox
        if (this.checked) {
            $("#personalInfo").attr('disabled',true);
            $("#address").attr('disabled',true);
            $("#contactInfo").attr('disabled',true);
        } else {
            $("#personalInfo").attr('disabled',false);
            $("#address").attr('disabled',false);
            $("#contactInfo").attr('disabled',false);
        }
    });
</script>
<script>

    $(document).ready(function () {
        $('#datetimepicker').daterangepicker({
            "singleDatePicker": true,
            "timePicker": true,
            "startDate": "{{ date('m/d/Y H:i') }}",
            "locale" : {
                "format" : "MM/DD/YYYY HH:mm"
            }
        }, function(start, end, label) {
            $(this).val(start.format('YYYY-MM-DD HH:mm'));
        });

        $('.datepicker').daterangepicker({
            "singleDatePicker": true,
            "startDate": $(this).data('date'),
            "locale" : {
                "format" : "MM/DD/YYYY"
            }
        });

        var loading = "{{ url('/library/loading') }}";

        $("body").on('change', '.province', function(){
            var prov_code = $(this).val();
            filterMuncity(prov_code);
        });

        $("body").on('change','.muncity', function(){
            var mun_code = $(this).val();
            filterBrgy(mun_code);
        });

        $('.confirm_comorbid').on('click',function () {
            var ans = $(this).val();
            if(ans=='Y'){
                $('.comorbid').show();
            }else{
                $('.comorbid').hide();
            }
        });

        $('.deletePatient').on('click',function(e) {
            e.preventDefault();
            var r = confirm('Are you sure you want to delete this patient? All history will be deleted. Deletion is not recommended!');
            if(r==true)
                window.location.replace($(this).attr('href'));
        });

        $('a[href="#admitPatient"]').on('click',function(){
            var url = "{{ url('/admitted/save') }}/"+$(this).data('id');
            $('#admittedForm').attr('action',url);
        });

        function filterMuncity(prov_code)
        {
            $.ajax({
                url: "{{ url('/library/muncity/list') }}/"+prov_code,
                type: "GET",
                success: function(data){
                    $('.muncity').empty()
                        .append($('<option>', {
                            value: '',
                            text : 'Select Municipality/City...'
                        }));
                    $('.brgy').empty()
                        .append($('<option>', {
                            value: '',
                            text : 'Select Barangay...'
                        }));
                    $.each(data, function (i, item) {
                        $('.muncity').append($('<option>', {
                            value: item.code,
                            text : item.name
                        }));
                    });
                }
            });
        }

        function filterBrgy(mun_code)
        {
            $.ajax({
                url: "{{ url('/library/brgy/list') }}/"+mun_code,
                type: "GET",
                success: function(data){
                    $('.brgy').empty()
                        .append($('<option>', {
                            value: '',
                            text : 'Select Barangay...'
                        }));
                    $.each(data, function (i, item) {
                        $('.brgy').append($('<option>', {
                            value: item.code,
                            text : item.name
                        }));
                    });
                }
            });
        }

        $("a[href='#infoPatient']").on('click',function(){
            var id = $(this).data('id');
            var url = "{{ url('/patients') }}/"+id;

            setTimeout(function(){
                $('.info_content').load(url,function(){
                    $('.datepickerUpdate').daterangepicker({
                        "singleDatePicker": true,
                        "locale" : {
                            "format" : "MM/DD/YYYY"
                        }
                    });
                });
            },500);
        });

        $('#infoPatient').on('hidden.bs.modal', function () {
            $('.info_content').load(loading);
        });

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
