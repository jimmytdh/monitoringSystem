@extends('app')

@section('css')

@endsection

@section('body')
    <h2 class="text-success title-header">Charges <small class="text-muted">Panel</small></h2>
    <section class="content">
        <div class="row">
            <div class="col-md-4">
                <!-- general form elements -->
                <div class="box box-primary">
                    @if(!$edit)
                        <div class="box-header with-border">
                            <h3 class="box-title">New Charge</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form role="form" method="post" action="{{ url('/library/charges/save') }}">
                            {{ csrf_field() }}
                            <div class="box-body">
                                <div class="form-group">
                                    <select name="section" id="" class="form-control">
                                    @foreach($section as $s)
                                            <option value="{{ $s->section }}">{{ strtoupper($s->section) }}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="name" required name="name" placeholder="Enter Charge Name...">
                                </div>
                                <div class="form-group">
                                    <select name="type" id="" class="form-control">
                                        <option value="">Select Per Usage...</option>
                                        <option value="">None</option>
                                        <option value="day">/day</option>
                                        <option value="use">/use</option>
                                        <option value="pc">/pc</option>
                                        <option value="hr">/hr</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="number" class="form-control" required name="amount" placeholder="Enter Amount...">
                                </div>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
                                <button type="submit" class="btn btn-success btn-block">Save</button>
                            </div>
                        </form>

                    @else

                        <div class="box-header with-border">
                            <h3 class="box-title">Update Charge</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form role="form" method="post" action="{{ url('/library/charges/'.$info->id) }}">
                            {{ csrf_field() }}
                            <div class="box-body">
                                <div class="form-group">
                                    <select name="section" id="" class="form-control">
                                        @foreach($section as $s)
                                            <option value="{{ $s->section }}" @if($info->section==$s->section) selected @endif>{{ strtoupper($s->section) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" value="{{ $info->name }}" required name="name" placeholder="Enter Charge Name...">
                                </div>
                                <div class="form-group">
                                    <select name="type" id="" class="form-control">
                                        <option value="">None</option>
                                        <option value="day" @if($info->type=='day') selected @endif>/day</option>
                                        <option value="use" @if($info->type=='use') selected @endif>/use</option>
                                        <option value="pc" @if($info->type=='pc') selected @endif>/pc</option>
                                        <option value="hr" @if($info->type=='hr') selected @endif>/hr</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="number" class="form-control" value="{{ $info->amount }}" required name="amount" placeholder="Enter Amount...">
                                </div>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
                                <button type="submit" class="btn btn-info btn-block">Update</button>
                                <a href="{{ url('/library/charges/delete/'.$info->id) }}" class="btn btn-danger btn-block btn-delete">Delete</a>
                                <a href="{{ url('/library/charges') }}" class="btn btn-default btn-block">Back</a>
                            </div>
                        </form>
                    @endif
                </div>
                <!-- /.box -->
            </div>
            <div class="col-md-8">
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

                @if(session('status')=='deleted')
                    <div class="alert alert-warning">
                        <i class="fa fa-exclamation-triangle"></i> Successfully Deleted!
                    </div>
                @endif
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="pull-right">
                            <form action="{{ url('/library/charges/search') }}" method="post">
                                {{ csrf_field() }}
                                <div class="input-group input-group-sm mb-0">
                                    <input type="text" class="form-control" name="keyword" value="{{ Session::get('chargeKeyword') }}" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                                    <div class="input-group-append">
                                        <button class="btn btn-info" type="submit"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <h3 class="box-title">Charges List</h3>
                    </div>

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
                                        <td><a href="{{ url('/library/charges/'.$row->id) }}" class="editable-click">{{ str_pad($row->id,3,0,STR_PAD_LEFT) }}</a></td>
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
    <script>
        $('.btn-delete').on('click',function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var r = confirm('Are you sure you want to delete this charge?');
            if(r==true)
                window.location = url;
        });
    </script>
@endsection
