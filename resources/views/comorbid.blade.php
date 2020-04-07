@extends('app')

@section('css')

@endsection

@section('body')
    <h2 class="text-success title-header">Co-Morbidity <small class="text-muted">Panel</small></h2>
    <section class="content">
        <div class="row">
            <div class="col-md-4">
                <!-- general form elements -->
                <div class="box box-primary">
                    @if(!$edit)
                        <div class="box-header with-border">
                            <h3 class="box-title">New Co-Morbidity</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form role="form" method="post" action="{{ url('/library/comorbid/save') }}">
                            {{ csrf_field() }}
                            <div class="box-body">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="name" required name="name" placeholder="Input Name..." autofocus>
                                </div>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
                                <button type="submit" class="btn btn-success btn-block">Save</button>
                            </div>
                        </form>

                    @else

                        <div class="box-header with-border">
                            <h3 class="box-title">Update Co-Morbidity</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form role="form" method="post" action="{{ url('/library/comorbid/'.$info->id) }}">
                            {{ csrf_field() }}
                            <div class="box-body">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="name" value="{{ $info->name }}" required name="name" placeholder="Input Name..." autofocus>
                                </div>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
                                <button type="submit" class="btn btn-info btn-block">Update</button>
                                <a href="{{ url('/library/comorbid/delete/'.$info->id) }}" class="btn btn-danger btn-block btn-delete">Delete</a>
                                <a href="{{ url('/library/comorbid') }}" class="btn btn-default btn-block">Back</a>
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
                        <i class="fa fa-exclamation"></i> Successfully Deleted!
                    </div>
                @endif
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="pull-right">
                            <form action="{{ url('/library/comorbid/search') }}" method="post">
                                {{ csrf_field() }}
                                <div class="input-group input-group-sm mb-0">
                                    <input type="text" class="form-control" name="keyword" value="{{ Session::get('morbidKeyword') }}" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                                    <div class="input-group-append">
                                        <button class="btn btn-info" type="submit"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <h3 class="box-title">Co-Morbidity List</h3>
                    </div>

                    <div class="box-body">
                        @if(count($data)>0)
                            <table class="table table-sm">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $row)
                                    <tr>
                                        <td><a href="{{ url('/library/comorbid/'.$row->id) }}" class="editable-click">{{ str_pad($row->id,3,0,STR_PAD_LEFT) }}</a></td>
                                        <td>{{ $row->name }}</td>
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
            var r = confirm('Are you sure you want to delete this co-morbidity?');
            if(r==true)
                window.location = url;
        });
    </script>
@endsection
