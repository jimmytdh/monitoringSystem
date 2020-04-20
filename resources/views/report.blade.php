@extends('app')

@section('css')
<link rel="stylesheet" href="{{ url('/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" />
<style>
    .table { font-size: 0.8em; }
    table tr td { white-space: nowrap; }
</style>
@endsection

@section('body')
    <h2 class="text-success title-header">
        Generate Report <small class="text-muted"></small>
        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#filterReport">
            <i class="fa fa-filter"></i> Filter
            <i class="fa fa-bars"></i>
        </button>
        <a href="{{ url('/report/export') }}" target="_blank" class="btn btn-success btn-sm">
            <i class="fa fa-file-excel-o"></i> Download
        </a>

    </h2>
    <section class="content">
    @if(count($data) > 0)
    <div class="table-responsive">
        <table class="table table-sm table-bordered">
        <tr>
            <th rowspan="2">Patient No.</th>
            <th rowspan="2">Full Name<br><small>(Family Name, First Name, M.I.)</small></th>
            <th rowspan="2">Demographics</th>
            <th rowspan="2">Complete Address<br><small>(Purok/Street, Barangay, Municipality, Province)</small></th>
            <th rowspan="2">Date of Consultation<br><small>mm/dd/yyyy</small></th>
            <th colspan="2">Disposition</th>
            <th rowspan="2">Signs and Symptoms</th>
            <th rowspan="2">Travel History<br><small>Y/N specify</small></th>
            <th rowspan="2">Contact Information</th>
        </tr>
        <tr>
            <td>Co-Morbid</td>
            <td>Home<br>Isolation</td>
        </tr>
        @foreach($data as $row)
        <tr>
            <td>{{ $row->pat_code }}</td>
            <td>{{ "$row->lname, $row->fname" }} {{ (isset($row->mname)) ? $row->mname[0]."." : "" }}</td>
            <td style="white-space: nowrap;">Age: {{ \App\Http\Controllers\LibraryCtrl::getAge($row->dob) }}
                <br>Sex: {{ ($row->sex=='M') ? 'Male':'Female' }}
                <br>Date of Birth: {{ date('m/d/Y',strtotime($row->dob)) }}
            </td>
            <td style="white-space: normal;">
                @if($row->purok)
                    {{ $row->purok }},
                @endif
                {{ \App\Http\Controllers\LocationCtrl::getBrgy($row->brgy) }},
                {{ \App\Http\Controllers\LocationCtrl::getMuncity($row->muncity) }},
                {{ \App\Http\Controllers\LocationCtrl::getProvince($row->province) }}
            </td>
            <td>{{ date('m/d/Y',strtotime($row->date_consultation)) }}</td>
            <td style="white-space: normal;">
                @if($row->comorbid=='Y')
                Yes <br><br>

                {{ \App\Http\Controllers\ReportCtrl::getPatMorbidity($row->pat_id,$row->date_consultation) }}

                @if($row->comorbid_details)
                <br>
                <br>
                Others: <br>
                {{ $row->comorbid_details }}
                @endif
                @else
                No
                @endif
            </td>
            <td>{{ ($row->home_isolation=='Y') ? 'Yes': 'No' }}</td>
            <td style="white-space: nowrap">
                @if($row->fever)
                    <strong>Fever</strong> ({{ date('m/d/Y',strtotime($row->date_fever)) }})
                    <br>
                @endif

                @if($row->cough)
                    <strong>Cough</strong> ({{ date('m/d/Y',strtotime($row->date_cough)) }})
                    <br>
                @endif

                @if($row->colds)
                    <strong>Colds</strong> ({{ date('m/d/Y',strtotime($row->date_colds)) }})
                    <br>
                @endif

                @if($row->sorethroat)
                    <strong>Sore Throat</strong> ({{ date('m/d/Y',strtotime($row->date_sorethroat)) }})
                    <br>
                @endif

                @if($row->diarrhea)
                    <strong>Diarrhea</strong> ({{ date('m/d/Y',strtotime($row->date_diarrhea)) }})
                    <br>
                @endif

                @if($row->bd)
                    <strong>Breathing Difficulty</strong> ({{ date('m/d/Y',strtotime($row->date_dob)) }})
                    <br>
                @endif
            </td>
            <td class="text-center">
                @if($row->travel=='Y')
                    Yes, {{ $row->travel_address }}
                @else
                    No
                @endif
            </td>
            <td>
                Parents: {{ $row->parents }}
                <br>
                <br>
                Contact Number: {{ $row->contact_num }}
                <br>
                <br>
                No. of Person of Household: {{ $row->no_household }}
            </td>
        </tr>
        @endforeach
    </table>
    {{ $data->links() }}
    </div>
    @else
    <div class="alert alert-warning">
        <i class="fa fa-exclamation-triangle"></i> No data to generate!
    </div>
    @endif
    </section>
@endsection

@section('modal')
    @include('modal.report')
@endsection

@section('js')
    <script src="{{ url('/plugins/bootstrap-daterangepicker/moment.min.js') }}"></script>
    <script src="{{ url('/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script>
        $(document).ready(function(){
            $("#daterange").daterangepicker();
        });

        $("body").on('change', '.province', function(){
            var prov_code = $(this).val();
            filterMuncity(prov_code);
        });

        $("body").on('change','.muncity', function(){
            var mun_code = $(this).val();
            filterBrgy(mun_code);
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
    </script>
@endsection
