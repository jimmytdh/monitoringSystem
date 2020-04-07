<?php
    use App\Http\Controllers\BrgyCtrl as Info;
?>
@extends('app')

@section('css')

@endsection

@section('body')
    <h2 class="text-success title-header">Barangay <small class="text-muted">Panel</small></h2>
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <!-- general form elements -->
                <div class="box box-primary">
                    @if(!$edit)
                        <div class="box-header with-border">
                            <h3 class="box-title">New Barangay</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form role="form" method="post" action="{{ url('/library/brgy/save') }}">
                            {{ csrf_field() }}
                            <div class="box-body">
                                <div class="form-group">
                                    <label>Province</label>
                                    <select name="province" class="province form-control form-control-sm">
                                        <option value="">Select Province...</option>
                                        @foreach($province as $p)
                                            <option value="{{ $p->code }}">{{ $p->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Municipality / City</label>
                                    <select name="muncity" class="muncity form-control form-control-sm">
                                        <option value="">Select Municipality/City...</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Enter Barangay Name</label>
                                    <input type="text" class="form-control form-control-sm" id="name" required name="name" placeholder="Input Name..." autofocus>
                                </div>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
                                <button type="submit" class="btn btn-success btn-block">Save</button>
                            </div>
                        </form>

                    @else

                        <div class="box-header with-border">
                            <h3 class="box-title">Update Barangay</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form role="form" method="post" action="{{ url('/library/brgy/'.$info->id) }}">
                            {{ csrf_field() }}
                            <div class="box-body">
                                <div class="form-group">
                                    <label>Province</label>
                                    <select name="province" class="province form-control form-control-sm" required>
                                        <option value="">Select Province...</option>
                                        @foreach($province as $p)
                                            <option value="{{ $p->code }}" @if($p->code==$prov_code) selected @endif>{{ $p->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Municipality / City</label>
                                    <select name="muncity" class="muncity form-control form-control-sm" required>
                                        <option value="">Select Municipality/City...</option>
                                        @foreach($muncity as $m)
                                            <option value="{{ $m->code }}" @if($m->code==$info->mun_code) selected @endif>{{ $m->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Enter Barangay Name</label>
                                    <input type="text" class="form-control" id="name" required value="{{ $info->name }}" name="name" placeholder="Input Name..." autofocus>
                                </div>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
                                <button type="submit" class="btn btn-info btn-block">Update</button>
                                <a href="{{ url('/library/brgy/delete/'.$info->id) }}" class="btn btn-danger btn-block btn-delete">Delete</a>
                                <a href="{{ url('/library/brgy') }}" class="btn btn-default btn-block">Back</a>
                            </div>
                        </form>
                    @endif
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

                @if(session('status')=='deleted')
                    <div class="alert alert-warning">
                        <i class="fa fa-exclamation"></i> Successfully Deleted!
                    </div>
                @endif
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="pull-right">
                            <form action="{{ url('/library/brgy/search') }}" method="post">
                                {{ csrf_field() }}
                                <div class="input-group input-group-sm mb-0">
                                    <input type="text" class="form-control" name="keyword" value="{{ Session::get('brgyKeyword') }}" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                                    <div class="input-group-append">
                                        <button class="btn btn-info" type="submit"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <h3 class="box-title">Co-Morbidity List</h3>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">
                        @if(count($data)>0)
                            <table class="table table-sm">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Municipality / City</th>
                                    <th scope="col">Province</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $row)
                                    <?php
                                        $muncity = \App\Http\Controllers\BrgyCtrl::getMuncityName($row->mun_code);
                                        $province = \App\Http\Controllers\BrgyCtrl::getProvinceName($muncity->prov_code);
                                    ?>
                                    <tr>
                                        <td><a href="{{ url('/library/brgy/'.$row->id) }}" class="editable-click">{{ $row->code }}</a></td>
                                        <td>{{ $row->name }}</td>
                                        <td>{{ $muncity->name }}</td>
                                        <td>{{ $province->name }}</td>
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
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            $("body").on('change', '.province', function(){
                var prov_code = $(this).val();
                filterMuncity(prov_code);
            });

            $('.btn-delete').on('click',function (e) {
                e.preventDefault();
                var url = $(this).attr('href');
                var r = confirm('Are you sure you want to delete this barangay?');
                if(r==true)
                    window.location = url;
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

                        $.each(data, function (i, item) {
                            $('.muncity').append($('<option>', {
                                value: item.code,
                                text : item.name
                            }));
                        });
                    }
                });
            }

        });
    </script>

@endsection
