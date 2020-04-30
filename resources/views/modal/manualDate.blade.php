<div class="modal" tabindex="-1" role="dialog" id="manualDate">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title">Add Consultation Date</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ url('/patients/manual/') }}" method="post" id="manualDateForm">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label>Date and Time</label>
                        <input type="date" class="form-control" name="date_consultation" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
