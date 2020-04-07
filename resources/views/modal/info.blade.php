<div class="modal" tabindex="-1" role="dialog" id="infoPatient">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title">
                    Update Patient
                    <br><label><input type="checkbox" value="Y" name="revisit" id="revisit"> Revisit?</label>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="info_content">
                <div class="text-center" style="padding:20px">
                    <img src="{{ url('images/loading.gif') }}" /><br />
                    <small class="text-muted">Loading...Please wait...</small>
                </div>
            </div>
        </div>
    </div>
</div>
