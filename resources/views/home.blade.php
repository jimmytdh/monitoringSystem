<?php $user = \Illuminate\Support\Facades\Session::get('user'); ?>
@extends('app')

@section('css')
    <link href="{{ url("/plugins/chart.js/dist/Chart.min.css") }}" rel="stylesheet">
    <style>
        #loader-wrapper { visibility: visible; }
    </style>
@endsection

@section('body')
    <h2 class="text-success title-header">Welcome <small class="text-muted">, {{ $user->name }}</small></h2>

    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6">
                <!-- AREA CHART -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Activity Last 7 Days</h3>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <canvas id="areaChart" style="height:250px"></canvas>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col (LEFT) -->
            <div class="col-md-6">
                <!-- DONUT CHART -->
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Symptoms Chart</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="donutChart" style="height:250px"></canvas>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col (RIGHT) -->
            <div class="clearfix"></div>
        </div>
    <!-- /.row -->
    </div>
@endsection

@section('js')
    <script src="{{ url('/plugins/chart.js/dist/Chart.js') }}"></script>
    <script src="{{ url('/plugins/chart.js/dist/utils.js') }}"></script>
    <script>
    </script>
    @include('data.chart')
@endsection
