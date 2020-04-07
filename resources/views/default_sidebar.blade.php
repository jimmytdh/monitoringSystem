@extends('app')

@section('css')

@endsection

@section('body')
    <h2 class="text-success title-header">Designation <small class="text-muted">Panel</small></h2>
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">New Designation</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" method="post" action="{{ url('/library/designation/save') }}">
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="form-group">
                                <label for="code">Designation Abbr.</label>
                                <input type="text" class="form-control" id="code" required name="code" placeholder="e.g. CMT II" autofocus>
                            </div>
                            <div class="form-group">
                                <label for="name">Designation Name</label>
                                <input type="text" class="form-control" id="name" required name="name" placeholder="e.g. Computer Maintenance Technologist II">
                            </div>
                            <div class="form-group">
                                <label for="sg">Salary Grade</label>
                                <input type="number" min="1" max="33" value="1" class="form-control" id="sg" name="sg" placeholder="">
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" class="btn btn-success btn-block">Save</button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
            <div class="col-md-8">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="pull-right">
                            <div class="input-group input-group-sm mb-0">
                                <input type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                                <div class="input-group-append">
                                    <button class="btn btn-info" type="button">Go!</button>
                                </div>
                            </div>
                        </div>
                        <h3 class="box-title">Designation List</h3>
                    </div>

                    <div class="box-body">
                        @if(count($data)>0)

                        @else
                            <img src="{{ url("/images/no_result_found.gif") }}" class="img-thumbnail" alt="">
                        @endif
                    </div>
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
@endsection

@section('js')

@endsection
