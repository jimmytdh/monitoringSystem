<div class="modal" tabindex="-1" role="dialog" id="admitPatient">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title">Admit Patient</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ url('/admitted/save/') }}" method="post" id="admittedForm">
            {{ csrf_field() }}
            <div class="modal-body">
                <div class="form-group">
                    <label>Diagnosis/Status</label>
                    <textarea name="status" rows="3" class="form-control" style="resize: none;" required></textarea>
                </div>
                <div class="form-group">
                    <label>Date and Time</label>
                    <input type="text" id="datetimepicker" class="form-control" name="dateTime" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Admit</button>
            </div>
            </form>
        </div>
    </div>
</div>
