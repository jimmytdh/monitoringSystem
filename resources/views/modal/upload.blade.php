<div class="modal" tabindex="-1" role="dialog" id="upload">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form action="{{ url('/upload') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-header bg-success">
                    <h5 class="modal-title">Upload Payroll Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Month</label>
                        <select name="month" class="form-control" required>
                            @for($i=1; $i<=12; $i++)
                                <option>{{ str_pad($i,2,0,STR_PAD_LEFT) }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Year</label>
                        <select name="year" class="form-control" required>
                            <?php $year = date('Y'); ?>
                            @for($i=0; $i<5; $i++)
                                <option>{{ $year-- }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">CSV File</label>
                        <input type="file" required name="payroll" class="form-control" accept=".xlsx">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success btn-upload">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>