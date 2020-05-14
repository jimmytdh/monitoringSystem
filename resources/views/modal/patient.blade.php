<div class="modal" tabindex="-1" role="dialog" id="addPatient">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ url('/patients/save') }}" method="post">
                {{ csrf_field() }}
                <div class="modal-header bg-success">
                    <h5 class="modal-title">Add New Patient</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <fieldset>
                                <legend>Personal Information</legend>
                                <div class="form-group">
                                    <input type="text" name="fname" id="fname" class="form-control-sm form-control" placeholder="First Name" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="mname" id="mname" class="form-control-sm form-control" placeholder="Middle Name">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="lname" id="lname" class="form-control-sm form-control" placeholder="Family Name" required>
                                </div>
                                <div class="form-group">
                                    <label for="male" style="font-size: 0.9em; font-weight: normal;">
                                        <input type="radio" name="sex" id="male" value="M" checked> Male
                                    </label>
                                    &nbsp;&nbsp;
                                    <label for="female" style="font-size: 0.9em; font-weight: normal;">
                                        <input type="radio" name="sex" id="female" value="F"> Female
                                    </label>
                                </div>
                                <div class="form-group">
                                    <input type="date" name="dob" id="dob" value="1990-01-01" class="form-control-sm form-control">
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>Address</legend>
                                <div class="form-group">
                                    <select name="province" class="province form-control form-control-sm" required>
                                        <option value="">Select Province...</option>
                                        @foreach($province as $p)
                                        <option value="{{ $p->code }}">{{ $p->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select name="muncity" class="muncity form-control form-control-sm" required>
                                        <option value="">Select Municipality/City...</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select name="brgy" class="brgy form-control form-control-sm" required>
                                        <option value="">Select Barangay...</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="purok" id="purok" class="form-control-sm form-control" placeholder="Household No./Purok/Sitio">
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>Contact Information</legend>
                                <div class="form-group">
                                    <input type="text" name="parents" class="form-control form-control-sm" placeholder="Parents" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="contact_num" class="form-control form-control-sm" placeholder="Contact Number" required>
                                </div>
                                <div class="form-group">
                                    <input type="number" name="no_household" class="form-control form-control-sm" placeholder="No. of person of Household" required>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-6">
                            <fieldset>
                                <legend>Consultation</legend>
                                <div class="form-group">
                                    <input type="date" name="date_consultation" value="{{ date('Y-m-d') }}" class="form-control-sm form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Co-Morbid?</label>
                                    &nbsp;&nbsp;
                                    <label for="comorbidyes" style="font-size: 0.9em; font-weight: normal;">
                                        <input type="radio" name="comorbid" class="confirm_comorbid" id="comorbidyes" value="Y"> Yes
                                    </label>
                                    <label for="comorbidno" style="font-size: 0.9em; font-weight: normal;">
                                        <input type="radio" name="comorbid" class="confirm_comorbid" id="comorbidno" value="N" checked> No
                                    </label>
                                    <fieldset class="comorbid" style="display: none;border-color: darkred;">
                                        <legend style="color: darkred;">Co-Morbidity</legend>
                                        @foreach($comorbid as $c)
                                            <label>
                                                <input type="checkbox" name="comorbidities[]" value="{{ $c->id }}"> {{ $c->name }}
                                            </label>
                                            <br>
                                        @endforeach
                                        <textarea name="comorbid_details" class="form-control" rows="3" style="resize: none;" placeholder="Others, please specify!"></textarea>
                                    </fieldset>
                                </div>
                                <div class="form-group">
                                    <label for="">Home Isolation?</label>
                                    &nbsp;&nbsp;
                                    <label for="homeisoyes" style="font-size: 0.9em; font-weight: normal;">
                                        <input type="radio" name="home_isolation" id="homeisoyes" value="Y"> Yes
                                    </label>
                                    <label for="homeisono" style="font-size: 0.9em; font-weight: normal;">
                                        <input type="radio" name="home_isolation" id="homeisono" value="N" checked> No
                                    </label>
                                </div>
                                <table class="table table-sm">
                                    <tr>
                                        <td width="50%">
                                            <label>
                                                <input type="checkbox" name="fever" value="Y">
                                                Fever
                                            </label>
                                        </td>
                                        <td><input type="date" name="date_fever" class="form-control form-control-sm"></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>
                                                <input type="checkbox" name="cough" value="Y">
                                                Cough
                                            </label>
                                        </td>
                                        <td><input type="date" name="date_cough" class="form-control form-control-sm"></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>
                                                <input type="checkbox" name="colds" value="Y">
                                                Colds
                                            </label>
                                        </td>
                                        <td><input type="date" name="date_colds" class="form-control form-control-sm"></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>
                                                <input type="checkbox" name="sorethroat" value="Y">
                                                Sore Throat
                                            </label>
                                        </td>
                                        <td><input type="date" name="date_sorethroat" class="form-control form-control-sm"></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>
                                                <input type="checkbox" name="diarrhea" value="Y">
                                                Diarrhea
                                            </label>
                                        </td>
                                        <td><input type="date" name="date_diarrhea" class="form-control form-control-sm"></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>
                                                <input type="checkbox" name="bd" value="Y">
                                                Breathing Difficulty
                                            </label>
                                        </td>
                                        <td><input type="date" name="date_bd" class="form-control form-control-sm"></td>
                                    </tr>
                                </table>
                            </fieldset>
                            <fieldset>
                                <legend>Travel History</legend>
                                <div class="form-group">
                                    <label for="">Travel History?</label>
                                    &nbsp;&nbsp;
                                    <label for="travelyes" style="font-size: 0.9em; font-weight: normal;">
                                        <input type="radio" name="travel" id="travelyes" value="Y"> Yes
                                    </label>
                                    <label for="travelno" style="font-size: 0.9em; font-weight: normal;">
                                        <input type="radio" name="travel" id="travelno" value="N" checked> No
                                    </label>
                                    <textarea name="travel_address" class="form-control" rows="2" style="resize: none;" placeholder="if Yes, please specify!"></textarea>
                                    <input type="date" name="date_travel" class="form-control form-control-sm">
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>Status</legend>
                                <div class="form-group">
                                    <textarea name="outcome" class="form-control mb-2" rows="2" style="resize: none;" placeholder="Outcome"></textarea>
                                    <label for="">Patient Died?</label>&nbsp;&nbsp;
                                    <label for="died" style="font-size: 0.9em; font-weight: normal;">
                                        <input type="checkbox" name="died" id="died" value="Y"> Yes
                                    </label>
                                    <input type="date" name="date_died" class="form-control form-control-sm">
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-12">

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
