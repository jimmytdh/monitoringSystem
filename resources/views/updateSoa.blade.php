<?php
    use App\Http\Controllers\SoaCtrl as Item;
    $sub_fixed = 0;
    $sub_room = 0;
    $sub_procedure = 0;
    $sub_equipment = 0;
    $sub_gas = 0;
    $sub_outsource = 0;
    $sub_ancillary = 0;
?>
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
        thead th {
            text-align: center;
        }
        .editable {
            text-decoration: none;
            border-bottom: dashed 1px #0357cc;
            color: #0357cc;
        }
        .custom-control {
            margin-bottom: 0px !important;
            margin-top: 2px;
        }
    </style>
@endsection

@section('body')
    <form method="POST" action="{{ url('soa/update/'.$id) }}" id="formSubmit">
    {{ csrf_field() }}
    <h2 class="text-success title-header">
        {{ "$patient->lname, $patient->fname" }} <small class="text-muted">Statement of Account</small>
        <br>
        <small class="text-danger">[ TOTAL: <span class="total">0</span>]</small>

        <div class="pull-right">
            <div class="form-group form-inline">
                <input placeholder="Search items here..." autofocus type="text" id="search" class="form-control form-control-sm mr-2" />
                <button type="submit" class="btn btn-info btn-sm">
                    <i class="fa fa-save"></i> Update SOA
                </button>
                <a href="{{ url('soa/print/'.$id) }}" class="btn btn-default btn-sm ml-2"><i class="fa fa-print"></i>Print</a>
            </div>
        </div>
    </h2>
    <section class="content">
        @if(session('status')=='updated')
            <div class="alert alert-success">
                <i class="fa fa-check"></i> Statement of account successfully updated!
            </div>
        @endif
        <div class="row">
            <div class="col-md-6">
                <table border="1" width="100%">
                    <thead class="bg-gray">
                    <tr>
                        <th width="40%">FIXED CHARGES</th>
                        <th width="20%">COST</th>
                        <th width="20%">QTY/FR<br>EQ</th>
                        <th width="20%">AMT</th>
                    </tr>
                    </thead>
                    <tbody id="fixed_data">
                    @foreach($fixed as $row)
                        <?php
                        $check = Item::checkItem($id,$row->id);
                        $amount = 0;
                        $qty = 0;
                        if($check){
                            $amount = $row->amount * $check;
                            $sub_fixed += $amount;
                            $qty = $check;
                        }
                        ?>
                        <tr class="search_item">
                            <td>{{ $row->name }}</td>
                            <td>{{ number_format($row->amount,2) }}@if(strlen($row->type)>1)/{{ $row->type }} @endif</td>
                            <td>
                                <input type="number"
                                       class="fixed-select form-control form-control-sm"
                                       value="{{ $qty }}" min="0"
                                       name="items[{{ $row->id }}]"
                                       data-amount="{{ $row->amount }}"
                                       data-id="{{ $row->id }}"
                                       style="width:100%" />
                            </td>
                            <td class="fixed-{{ $row->id }}">{{ number_format($amount,2) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th class="text-right" colspan="3">SUBTOTAL</th>
                        <th class="fixed-sub">{{ number_format($sub_fixed,2) }}</th>
                    </tr>
                    </tbody>
                </table>

                <table border="1" width="100%">
                    <thead class="bg-gray">
                    <tr>
                        <th width="40%">ROOM & BOARD</th>
                        <th width="20%">COST</th>
                        <th width="20%">QTY/FR<br>EQ</th>
                        <th width="20%">AMT</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($room as $row)
                        <?php
                        $check = Item::checkItem($id,$row->id);
                        $amount = 0;
                        $qty = 0;
                        if($check){
                            $amount = $row->amount * $check;
                            $sub_room += $amount;
                            $qty = $check;
                        }
                        ?>
                        <tr class="search_item">
                            <td>{{ $row->name }}</td>
                            <td>{{ number_format($row->amount,2) }}@if(strlen($row->type)>1)/{{ $row->type }} @endif</td>
                            <td>
                                <input type="number"
                                       class="room-select form-control form-control-sm"
                                       value="{{ $qty }}" min="0"
                                       name="items[{{ $row->id }}]"
                                       data-amount="{{ $row->amount }}"
                                       data-id="{{ $row->id }}"
                                       style="width:100%" />
                            </td>
                            <td class="room-{{ $row->id }}">{{ number_format($amount,2) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th class="text-right" colspan="3">SUBTOTAL</th>
                        <th class="room-sub">{{ number_format($sub_room,2) }}</th>
                    </tr>
                    </tbody>
                </table>

                <table border="1" width="100%">
                    <thead class="bg-gray">
                    <tr>
                        <th width="40%">PROCEDURES</th>
                        <th width="20%">COST</th>
                        <th width="20%">QTY/FR<br>EQ</th>
                        <th>AMT</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($procedure as $row)
                        <?php
                        $check = Item::checkItem($id,$row->id);
                        $amount = 0;
                        $qty = 0;
                        if($check){
                            $amount = $row->amount * $check;
                            $sub_procedure += $amount;
                            $qty = $check;
                        }
                        ?>
                        <tr class="search_item">
                            <td>{{ $row->name }}</td>
                            <td>{{ number_format($row->amount,2) }}@if(strlen($row->type)>1)/{{ $row->type }} @endif</td>
                            <td>
                                <input type="number"
                                       class="procedure-select form-control form-control-sm"
                                       value="{{ $qty }}" min="0"
                                       name="items[{{ $row->id }}]"
                                       data-amount="{{ $row->amount }}"
                                       data-id="{{ $row->id }}"
                                       style="width:100%" />
                            </td>
                            <td class="procedure-{{ $row->id }}">{{ number_format($amount) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th class="text-right" colspan="3">SUBTOTAL</th>
                        <th class="procedure-sub">{{ number_format($sub_procedure,2) }}</th>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table border="1" width="100%">
                    <thead class="bg-gray">
                    <tr>
                        <th width="40%">EQUIPMENT USE</th>
                        <th width="20%">QTY</th>
                        <th width="20%">UNIT PRICE</th>
                        <th>AMOUNT</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($equipment as $row)
                        <?php
                        $check = Item::checkItem($id,$row->id);
                        $amount = 0;
                        $qty = 0;
                        if($check){
                            $amount = $row->amount * $check;
                            $sub_equipment += $amount;
                            $qty = $check;
                        }
                        ?>
                        <tr class="search_item">
                            <td>{{ $row->name }}</td>
                            <td>{{ number_format($row->amount,2) }}@if(strlen($row->type)>1)/{{ $row->type }} @endif</td>
                            <td>
                                <input type="number"
                                       class="equipment-select form-control form-control-sm"
                                       value="{{ $qty }}" min="0"
                                       name="items[{{ $row->id }}]"
                                       data-amount="{{ $row->amount }}"
                                       data-id="{{ $row->id }}"
                                       style="width:100%" />
                            </td>
                            <td class="equipment-{{ $row->id }}">{{ number_format($amount) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th class="text-right" colspan="3">SUBTOTAL</th>
                        <th class="equipment-sub">{{ number_format($sub_equipment,2) }}</th>
                    </tr>
                    </tbody>
                </table>

                <table border="1" width="100%">
                    <thead class="bg-gray">
                    <tr>
                        <th width="60%">MEDICAL GAS</th>
                        <th width="20%">PSI RATE</th>
                        <th>AMOUNT</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($gas as $row)
                        <?php
                        $check = Item::checkItem($id,$row->id);
                        $amount = 0;
                        $qty = 0;
                        if($check){
                            $amount = .50 * $check;
                            $sub_gas += $amount;
                            $qty = $check;
                        }
                        ?>
                        <tr class="search_item">
                            <td>{{ $row->name }}</td>
                            <td>
                                <input type="number"
                                       class="gas-select form-control form-control-sm"
                                       value="{{ $qty }}" min="0"
                                       name="items[{{ $row->id }}]"
                                       data-amount=".50"
                                       data-id="{{ $row->id }}"
                                       style="width:100%" />
                            </td>
                            <td class="gas-{{ $row->id }}">{{ number_format($amount,1) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th class="text-right" colspan="2">SUBTOTAL</th>
                        <th class="gas-sub">{{ number_format($sub_gas,2) }}</th>
                    </tr>
                    </tbody>
                </table>

                <table border="1" width="100%">
                    <thead class="bg-gray">
                    <tr>
                        <th width="60%">OUTSOURCED PROCEDURE</th>
                        <th>AMOUNT</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($outsource as $row)
                        <?php
                        $check = Item::checkItem($id,$row->id);
                        $amount = 0;
                        if($check){
                            $amount = $check;
                            $sub_outsource += $amount;
                        }
                        ?>
                        <tr class="search_item">
                            <td>{{ $row->name }}</td>
                            <td>
                                <input type="number"
                                       class="outsource-select form-control form-control-sm"
                                       value="{{ number_format($amount,2) }}" min="0"
                                       name="items[{{ $row->id }}]"
                                       data-id="{{ $row->id }}"
                                       step="any"
                                       style="width:100%" />
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <th class="text-right" colspan="1">SUBTOTAL</th>
                        <th class="outsource-sub">{{ number_format($sub_outsource,2) }}</th>
                    </tr>
                    </tbody>
                </table>
                <table border="1" width="100%">
                    <thead class="bg-gray">
                    <tr>
                        <th width="60%">OTHER CHARGES</th>
                        <th>AMOUNT</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($ancillary as $row)
                        <?php
                        $check = Item::checkItem($id,$row->id);
                        $amount = 0;
                        if($check){
                            $amount = $check;
                            $sub_ancillary += $amount;
                        }
                        ?>
                        <tr class="search_item">
                            <td>{{ $row->name }}</td>
                            <td>
                                <input type="number"
                                       class="others-select form-control form-control-sm"
                                       value="{{ number_format($amount,2) }}" min="0"
                                       name="items[{{ $row->id }}]"
                                       data-id="{{ $row->id }}"
                                       step="any"
                                       style="width:100%" />
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <th class="text-right" colspan="1">SUBTOTAL</th>
                        <th class="others-sub">{{ number_format($sub_ancillary,2) }}</th>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    </form>
@endsection

@section('js')
    <script type="text/javascript">
        var sub_fixed = parseFloat("{{ $sub_fixed }}");
        var sub_room = parseFloat("{{ $sub_room }}");
        var sub_procedure = parseFloat("{{ $sub_procedure }}");
        var sub_equipment = parseFloat("{{ $sub_equipment }}");
        var sub_gas = parseFloat("{{ $sub_gas }}");
        var sub_outsource = parseFloat("{{ $sub_outsource }}");
        var sub_others = parseFloat("{{ $sub_ancillary }}");

        totalAmount();

        var rooms = [];
        var procedure = [];
        var equipment = [];
        var gas = [];
        var outsource = [];
        var others = [];

        $('.fixed-select').on('keyup change',function () {
            sub_fixed = 0;
            getData('fixed');
        });


        $('.room-select').on('keyup change',function () {
            sub_room = 0;
            getData('room');
        });

        $('.procedure-select').on('keyup change',function () {
            sub_procedure = 0;
            getData('procedure');
        });

        $('.equipment-select').on('keyup change',function () {
            sub_equipment = 0;
            getData('equipment');
        });

        $('.gas-select').on('keyup change',function () {
            sub_gas = 0;
            getData('gas');
        });

        $('.outsource-select').on('keyup change',function () {
            sub_outsource = 0;
            getData2('outsource');
        });

        $('.others-select').on('keyup change',function () {
            sub_others = 0;
            getData2('others');
        });

        function totalAmount() {
            var total = sub_fixed + sub_room + sub_procedure + sub_equipment + sub_gas + sub_outsource + sub_others;
            $('.total').html(decimal(total));
        }

        function decimal(number) {
            var parts = number.toString().split(".");
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return parts.join(".");
        }

        function getData(section)
        {
            var loop = "."+section+"-select";
            $(loop).each(function() {
                var qty = $(this).val();
                var amount = $(this).data('amount');
                var total = amount * qty;
                var id = $(this).data('id');

                if(section=='room'){
                    sub_room += total;
                    $('.room-'+id).html(decimal(total));
                    $('.room-sub').html(decimal(sub_room));
                }else if(section=='fixed'){
                    sub_fixed += total;
                    $('.fixed-'+id).html(decimal(total));
                    $('.fixed-sub').html(decimal(sub_fixed));
                }else if(section=='procedure'){
                    sub_procedure += total;
                    $('.procedure-'+id).html(decimal(total));
                    $('.procedure-sub').html(decimal(sub_procedure));
                }else if(section=='equipment'){
                    sub_equipment += total;
                    $('.equipment-'+id).html(decimal(total));
                    $('.equipment-sub').html(decimal(sub_equipment));
                }else if(section=='gas'){
                    sub_gas += total;
                    $('.gas-'+id).html(decimal(total));
                    $('.gas-sub').html(decimal(sub_gas));
                }else if(section=='outsource'){
                    sub_outsource += total;
                    $('.outsource-'+id).html(decimal(total));
                    $('.outsource-sub').html(decimal(sub_outsource));
                }else if(section=='others'){
                    sub_others += total;
                    $('.others-'+id).html(decimal(total));
                    $('.others-sub').html(decimal(sub_others));
                }
            });
            totalAmount();
        }

        function getData2(section){
            var loop = "."+section+"-select";
            $(loop).each(function() {
                var amount = $(this).val();
                var id = $(this).data('id');
                amount = parseFloat(amount);
                if(section=='outsource'){
                    sub_outsource += amount;
                    $('.outsource-'+id).html(decimal(amount));
                    $('.outsource-sub').html(decimal(sub_outsource));
                }else if(section=='others'){
                    sub_others += amount;
                    $('.others-'+id).html(decimal(amount));
                    $('.others-sub').html(decimal(sub_others));
                }
            });
            totalAmount();
        }
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $(window).keydown(function(event){
                if(event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });
        });


        $('#search').on('keyup', function () {
            searchFunction();
        });

        function searchFunction() {
            // Declare variables
            var input, filter, td, tr, a, i;
            input = document.getElementById('search');
            filter = input.value.toUpperCase();
            td = document.getElementsByTagName('td');
            tr = document.getElementsByClassName('search_item');

            for(i=0; i < tr.length; i++){
                a = tr[i].innerHTML;
                if(a.toUpperCase().indexOf(filter) > -1){
                    tr[i].style.display = "";
                }else{
                    tr[i].style.display = "none";
                }
            }

        }
    </script>
@endsection
