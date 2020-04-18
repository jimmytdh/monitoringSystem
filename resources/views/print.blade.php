@extends('app')

@section('css')
    <style>
        .bg-gray {
            background: #cccccc;
        }
        table {
            margin-bottom: 20px;
        }
        table td,table th {
            padding: 2px 5px;
        }
        table th {
            text-align: center;
        }
        .editable {
            text-decoration: none;
            border-bottom: dashed 1px #0357cc;
            color: #0357cc;
        }
        .tdh_logo {
            width: 120px;
            position: absolute;
            left: 90px;
        }
        .doh_logo {
            width: 110px;
            position: absolute;
            right: 90px;
        }
        .photo {
            border-top:dashed 1px #313131;
            margin-top: 30px;
            padding-top: 30px;
            position: relative;
        }
        .patient_info2 {
            position: absolute;
            top:40px;
            left:100px;
            font-size: 1.2em;
            z-index: 99999;
        }

        .signature {
            position: relative;
        }
        .patient_info {
            position: absolute;
            top:150px;
            left:100px;
            font-size: 1.2em;
            z-index: 99999;
        }

        .table-signature td{
            vertical-align: top;
        }
        @media print {
            body * {
                visibility: hidden;
            }
            #print, #print * {
                visibility: visible;
            }
            #print {
                margin-top: -300px;
            }
        }
    </style>
@endsection

@section('body')
    <div class="mb-3">
        <a href="{{ url('soa/'.$id) }}" class="btn btn-space btn-secondary"><i class="s7-back-2"></i> Update Charges</a>
        <a href="{{ url('patients') }}" class="btn btn-space btn-secondary"><i class="s7-users"></i> Patients</a>
        <a href="#" onclick="window.print()" class="btn btn-space btn-secondary"><i class="s7-print"></i> Print</a>
    </div>
    <hr>
    <section class="content">
        <div class="panel panel-default" id="print">
            <div class="panel-heading text-center panel-heading-divider">
                <img src="{{ url('images/logo.png') }}" class="tdh_logo">
                <img src="{{ url('images/doh.png') }}" class="doh_logo">
                <div style="visibility: visible" class="header">
                    Republic of the Philippines <br />
                    Department of Health - Regional Office No. VII <br />
                    <strong>TALISAY DISTRICT HOSPITAL</strong> <br />
                    San Isidro, Talisay City, Cebu<br /><br />
                </div>

                <strong>SUMMARY OF CHARGES</strong>

            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <table style="width:100%">
                            <tr>
                                <td width="30%">Hospital Number : <strong>{{ $patient->pat_id }}</strong></td>
                                <td class="text-right" width="50%" colspan="2">Date : <strong>{{ date('F d, Y') }}</strong></td>
                            </tr>

                            <tr>
                                <td width="37%">Patient Name : <strong>{{ $patient->lname }}, {{ $patient->fname }}</strong></td>
                                <td>Age : <strong>{{ \App\Http\Controllers\LibraryCtrl::getAge($patient->dob) }}</strong></td>
                                <td class="text-right">Sex : <strong>{{ ($patient->sex=='F') ? 'Female':'Male' }}</strong></td>

                            </tr>
                        </table>
                        <table border="1" width="100%">
                            {{-- fixed price --}}
                            @if(count($fixed) > 0)
                                <tr class="bg-gray">
                                    <th width="40%">FIXED CHARGES</th>
                                    <th width="20%">COST</th>
                                    <th width="20%" class="text-center">QTY</th>
                                    <th></th>
                                </tr>
                                @foreach($fixed as $row)
                                    <tr>
                                        <td>{{ $row->name }}</td>
                                        <td class="text-right">{{ number_format($row->amount,2) }}@if(strlen($row->type)>1)/{{ $row->type }} @endif</td>
                                        <td class="text-center">1</td>
                                        <td class="text-right">{{ number_format($row->amount,2) }}@if(strlen($row->type)>1)/{{ $row->type }} @endif</td>
                                        <?php $total += $row->amount; ?>
                                    </tr>
                                @endforeach
                            @endif

                            {{-- room price --}}
                            @if(count($room) > 0)
                                <tr class="bg-gray">
                                    <th width="40%">ROOM & BOARD</th>
                                    <th width="20%">COST</th>
                                    <th width="20%" class="text-center">QTY</th>
                                    <th></th>
                                </tr>
                                @foreach($room as $row)
                                    <tr>
                                        <td>{{ $row->name }}</td>
                                        <td class="text-right">{{ number_format($row->amount,2) }}@if(strlen($row->type)>1)/{{ $row->type }} @endif</td>
                                        <td class="text-center">{{ $row->final_qty }}</td>
                                        <td class="text-right">{{ number_format(($row->amount * $row->final_qty),2) }}</td>
                                        <?php $total += ($row->amount * $row->final_qty); ?>
                                    </tr>
                                @endforeach
                            @endif
                            {{--procedure--}}
                            @if(count($procedure) > 0)
                                <tr class="bg-gray">
                                    <th width="40%">PROCEDURES</th>
                                    <th width="20%">COST</th>
                                    <th width="20%">QTY</th>
                                    <th></th>
                                </tr>
                                @foreach($procedure as $row)
                                    <tr>
                                        <td>{{ $row->name }}</td>
                                        <td class="text-right">{{ number_format($row->amount,2) }}@if(strlen($row->type)>1)/{{ $row->type }} @endif</td>
                                        <td class="text-center">{{ $row->final_qty }}</td>
                                        <td class="text-right">{{ number_format(($row->amount * $row->final_qty),2) }}</td>
                                        <?php $total += ($row->amount * $row->final_qty); ?>
                                    </tr>
                                @endforeach
                            @endif

                            {{--equipment--}}
                            @if(count($equipment) > 0)
                                <tr class="bg-gray">
                                    <th width="40%">EQUIPMENT USE</th>
                                    <th width="20%">UNIT PRICE</th>
                                    <th width="20%">QTY</th>
                                    <th></th>
                                </tr>
                                @foreach($equipment as $row)
                                    <tr>
                                        <td>{{ $row->name }}</td>
                                        <td class="text-right">{{ number_format($row->amount,2) }}@if(strlen($row->type)>1)/{{ $row->type }} @endif</td>
                                        <td class="text-center">{{ $row->final_qty }}</td>
                                        <td class="text-right">{{ number_format(($row->amount * $row->final_qty),2) }}</td>
                                        <?php $total += ($row->amount * $row->final_qty); ?>
                                    </tr>
                                @endforeach
                            @endif

                            {{--GAS--}}
                            @if(count($gas) > 0)
                                <tr class="bg-gray">
                                    <th colspan="2">MEDICAL GAS</th>
                                    <th>PSI RATE</th>
                                    <th></th>
                                </tr>
                                @foreach($gas as $row)
                                    <tr>
                                        <td colspan="2">{{ $row->name }}</td>
                                        <td class="text-center">{{ $row->final_qty }}</td>
                                        <td class="text-right">{{ number_format((.50 * $row->final_qty),2) }}</td>
                                        <?php $total += (.50 * $row->final_qty); ?>
                                    </tr>
                                @endforeach
                            @endif

                            {{--outsource--}}
                            @if(count($outsource) > 0)
                                <tr class="bg-gray">
                                    <th width="40%">OUTSOURCED PROCEDURE</th>
                                    <th width="20%">COST</th>
                                    <th width="20%" class="text-center">QTY</th>
                                    <th></th>
                                </tr>
                                @foreach($outsource as $row)
                                    <tr>
                                        <td>{{ $row->name }}</td>
                                        <td class="text-right">{{ number_format($row->final_qty,2) }}</td>
                                        <td class="text-center">1</td>
                                        <td class="text-right">{{ number_format($row->final_qty,2) }}</td>
                                        <?php $total += $row->final_qty; ?>
                                    </tr>
                                @endforeach
                            @endif

                            @if(count($others) > 0)
                                <tr class="bg-gray">
                                    <th width="40%">OTHERS</th>
                                    <th width="20%">COST</th>
                                    <th width="20%" class="text-center">QTY</th>
                                    <th></th>
                                </tr>
                                @foreach($others as $row)
                                    <tr>
                                        <td>{{ $row->name }}</td>
                                        <td class="text-right">{{ number_format($row->final_qty,2) }}</td>
                                        <td class="text-center">1</td>
                                        <td class="text-right">{{ number_format($row->final_qty,2) }}</td>
                                        <?php $total += $row->final_qty; ?>
                                    </tr>
                                @endforeach
                            @endif

                            {{--ancillary--}}
                            @if(count($ancillary) > 0)
                                <tr class="bg-gray">
                                    <th width="40%">OTHER CHARGES</th>
                                    <th width="20%">COST</th>
                                    <th width="20%" class="text-center">QTY</th>
                                    <th></th>
                                </tr>
                                @foreach($ancillary as $row)
                                    <tr>
                                        <td>{{ $row->name }}</td>
                                        <td class="text-right">{{ number_format($row->final_qty,2) }}</td>
                                        <td class="text-center">1</td>
                                        <td class="text-right">{{ number_format($row->final_qty,2) }}</td>
                                        <?php $total += $row->final_qty; ?>
                                    </tr>
                                @endforeach
                            @endif



                            <tr class="bg-gray">
                                <td colspan="3" class="text-right" style="font-size: 1.2em;"><strong>TOTAL BILL</strong></td>
                                <td class="text-right" style="font-size: 1.2em;"><strong>{{ number_format($total,2) }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="clearfix"></div>
                {{--<div class="mb-4"></div>--}}
                {{--<div class="signature">--}}
                {{--<div class="patient_info">--}}
                {{--<strong>Jimmy Lomocso</strong>--}}
                {{--</div>--}}
                {{--<div class="row">--}}
                {{--<div class="col-md-1"></div>--}}
                {{--<div class="col-md-10">--}}
                {{--<img src="{{ url('img/signature.jpg') }}" width="100%" />--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</div>--}}

                {{--<div class="row mt-5">--}}
                {{--<div class="col-md-1"></div>--}}
                {{--<div class="col-md-10 text-right">--}}
                {{--NSD-FM-19 REV.1<br />--}}
                {{--21 January 2019--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--<div class="photo">--}}
                {{--<div class="patient_info2">--}}
                {{--<strong>Jimmy Lomocso</strong>--}}
                {{--</div>--}}
                {{--<div class="row">--}}

                {{--<div class="col-md-1"></div>--}}
                {{--<div class="col-md-10">--}}
                {{--<img src="{{ url('img/signature2.jpg') }}" width="100%" />--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</div>--}}
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <table width="100%" class="table-signature">
                            <tr>
                                <td width="50%">
                                    Prepared By:<br />
                                    <br />

                                    ___________________________________<br />
                                    NOD NAME & SIGNATURE<br />
                                    Date & Time Prepared: <u>{{ date('m/d/Y h:i') }}</u><br />
                                    <br />
                                    <strong>ACKNOWLEDGEMENT</strong><br />
                                    <br />
                                    _______________________________________________<br />
                                    Name & Signature of Patient/Representative<br />
                                    Date & Time: ____________________<br />
                                    OR Number: ____________________
                                </td>
                                <td>
                                    <strong>DOH MEDICAL ASSISTANCE PROGRAM</strong><br />
                                    <br />
                                    Interview & Assessment Conducted by:<br />
                                    <br />
                                    __________________________________ <br />
                                    Date & Time: _________________________ <br />
                                    <br />
                                    [&nbsp;&nbsp;&nbsp;] Qualified<br />
                                    [&nbsp;&nbsp;&nbsp;] If Not, Qualified patient may avail discounts thru QFS<br />
                                    <br />
                                    ____________________________________________ <br />
                                    Medical Social Worker on Duty<br />
                                    <br />
                                    _____________________________________________________________ <br />
                                    Name & Signature of Authorized Officer for MAP Approval<br />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-right">
                                    <br />
                                    <br />
                                    NSD-FM-19 REV.1<br />
                                    21 January 2019
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row photo">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <table width="100%" class="table-signature">
                            <tr>
                                <td width="50%">
                                    <strong>ACKNOWLEDGEMENT</strong><br />
                                    <br />
                                    _______________________________________________<br />
                                    Name & Signature of Patient/Representative<br /><br />

                                    Hospital Number: <u>{{ $patient->pat_id }}</u><br />
                                    Patient Name: <u>{{ $patient->fname }} {{ $patient->lname }}</u><br />
                                    Age: <u>{{ \App\Http\Controllers\LibraryCtrl::getAge($patient->dob) }}</u><br />
                                    Sex: <u>{{ ($patient->sex=='F' ? 'Female':'Male') }}</u><br />
                                    <br />
                                    <br />
                                    Date & Time: _______________________<br />
                                    OR Number: _______________________
                                </td>
                                <td>
                                    <strong>DOH MEDICAL ASSISTANCE PROGRAM</strong><br />
                                    <br />
                                    Interview & Assessment Conducted by:<br />
                                    <br />
                                    __________________________________ <br />
                                    Date & Time: _________________________ <br />
                                    <br />
                                    [&nbsp;&nbsp;&nbsp;] Qualified<br />
                                    [&nbsp;&nbsp;&nbsp;] If Not, Qualified patient may avail discounts thru QFS<br />
                                    <br />
                                    ____________________________________________ <br />
                                    Medical Social Worker on Duty<br />
                                    <br />
                                    _____________________________________________________________ <br />
                                    Name & Signature of Authorized Officer for MAP Approval<br />
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')

@endsection
