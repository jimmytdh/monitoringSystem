<form action="{{ url('/patients/'.$info->id) }}" method="post">
    {{ csrf_field() }}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <fieldset id="personalInfo">
                    <legend>Personal Information</legend>
                    <div class="form-group">
                        <input type="text" name="fname" id="fname" value="{{ $info->fname }}" class="form-control-sm form-control" placeholder="First Name" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="mname" id="mname" value="{{ $info->mname }}" class="form-control-sm form-control" placeholder="Middle Name">
                    </div>
                    <div class="form-group">
                        <input type="text" name="lname" id="lname" value="{{ $info->lname }}" class="form-control-sm form-control" placeholder="Family Name" required>
                    </div>
                    <div class="form-group">
                        <label for="male" style="font-size: 0.9em; font-weight: normal;">
                            <input type="radio" name="sex" id="male" value="M" @if($info->sex=='M') checked @endif> Male
                        </label>
                        &nbsp;&nbsp;
                        <label for="female" style="font-size: 0.9em; font-weight: normal;">
                            <input type="radio" name="sex" id="female" value="F" @if($info->sex=='F') checked @endif> Female
                        </label>
                    </div>
                    <div class="form-group">
                        <input type="text" name="dob" id="dob" value="{{ date('m/d/Y',strtotime($info->dob)) }}" class="datepickerUpdate form-control-sm form-control">
                    </div>
                </fieldset>
                <fieldset id="address">
                    <legend>Address</legend>
                    <div class="form-group">
                        <select name="province" class="province form-control form-control-sm" required>
                            <option value="">Select Province...</option>
                            @foreach($province as $p)
                                <option value="{{ $p->code }}" @if($info->province==$p->code) selected @endif>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="muncity" class="muncity form-control form-control-sm" required>
                            <option value="">Select Municipality/City...</option>
                            @foreach($muncity as $m)
                                <option value="{{ $m->code }}" @if($info->muncity==$m->code) selected @endif>{{ $m->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="brgy" class="brgy form-control form-control-sm" required>
                            <option value="">Select Barangay...</option>
                            @foreach($brgy as $b)
                                <option value="{{ $b->code }}" @if($info->brgy==$b->code) selected @endif>{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" name="purok" value="{{ $info->purok }}" class="form-control-sm form-control" placeholder="Household No./Purok/Sitio">
                    </div>
                </fieldset>
                <fieldset id="contactInfo">
                    <legend>Contact Information</legend>
                    <div class="form-group">
                        <input type="text" name="parents" value="{{ $info->parents }}" class="form-control form-control-sm" placeholder="Parents" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="contact_num" value="{{ $info->contact_num }}" class="form-control form-control-sm" placeholder="Contact Number" required>
                    </div>
                    <div class="form-group">
                        <input type="number" name="no_household" value="{{ $info->no_household }}" class="form-control form-control-sm" placeholder="No. of person of Household" required>
                    </div>
                </fieldset>
            </div>
            <div class="col-md-6">
                <fieldset>
                    <legend>Consultation</legend>
                    <div class="form-group">
                        <input type="text" name="date_consultation" id="date_consultation" value="{{ date('m/d/Y',strtotime($consultation->date_consultation)) }}" class="datepickerUpdate form-control-sm form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Co-Morbid?</label>
                        &nbsp;&nbsp;
                        <label for="comorbidyes" style="font-size: 0.9em; font-weight: normal;">
                            <input type="radio" name="comorbid" class="confirm_comorbid" id="comorbidyes" value="Y" @if($consultation->comorbid=='Y') checked @endif> Yes
                        </label>
                        &nbsp;&nbsp;
                        <label for="comorbidno" style="font-size: 0.9em; font-weight: normal;">
                            <input type="radio" name="comorbid" class="confirm_comorbid" id="comorbidno" value="N" @if($consultation->comorbid!='Y') checked @endif> No
                        </label>
                        <fieldset class="comorbid" style="display: {{ ($consultation->comorbid=='Y') ? 'block':'none' }};border-color: darkred;">
                            <legend style="color: darkred;">Co-Morbidity</legend>
                            @foreach($comorbid as $c)
                                <?php $check = \App\Http\Controllers\ComorbidCtrl::isPatMorbid($info->id,$c->id,$consultation->date_consultation) ?>
                                <label>
                                    <input type="checkbox" @if($check) checked @endif name="comorbidities[]" value="{{ $c->id }}"> {{ $c->name }}
                                </label>
                                <br>
                            @endforeach
                            <textarea name="comorbid_details" class="form-control" rows="3" style="resize: none;" placeholder="Others, please specify!">{{ $consultation->comorbid_details }}</textarea>
                        </fieldset>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="consultation_id" value="{{ $consultation->id }}" />
                        <label for="">Home Isolation?</label>
                        &nbsp;&nbsp;
                        <label for="homeisoyes" style="font-size: 0.9em; font-weight: normal;">
                            <input type="radio" name="home_isolation" id="homeisoyes" value="Y" @if($consultation->home_isolation=='Y') checked @endif> Yes
                        </label>
                        <label for="homeisono" style="font-size: 0.9em; font-weight: normal;">
                            <input type="radio" name="home_isolation" id="homeisono" value="N" @if($consultation->home_isolation!='Y') checked @endif> No
                        </label>
                    </div>
                    <table class="table table-sm">
                        <tr>
                            <td width="50%">
                                <label>
                                    <input type="checkbox" name="fever" value="Y" @if($consultation->fever=='Y') checked @endif>
                                    Fever
                                </label>
                            </td>
                            <td><input type="text" name="date_fever" id="" class="form-control form-control-sm datepickerUpdate" value="{{ date('m/d/Y',strtotime($consultation->date_fever)) }}"></td>
                        </tr>
                        <tr>
                            <td>
                                <label>
                                    <input type="checkbox" name="cough" value="Y" @if($consultation->cough=='Y') checked @endif>
                                    Cough
                                </label>
                            </td>
                            <td><input type="text" name="date_cough" id="" class="form-control form-control-sm datepickerUpdate" value="{{ date('m/d/Y',strtotime($consultation->date_cough)) }}"></td>
                        </tr>
                        <tr>
                            <td>
                                <label>
                                    <input type="checkbox" name="colds" value="Y" @if($consultation->colds=='Y') checked @endif>
                                    Colds
                                </label>
                            </td>
                            <td><input type="text" name="date_colds" id="" class="form-control form-control-sm datepickerUpdate" value="{{ date('m/d/Y',strtotime($consultation->date_colds)) }}"></td>
                        </tr>
                        <tr>
                            <td>
                                <label>
                                    <input type="checkbox" name="sorethroat" value="Y" @if($consultation->sorethroat=='Y') checked @endif>
                                    Sore Throat
                                </label>
                            </td>
                            <td><input type="text" name="date_sorethroat" id="" class="form-control form-control-sm datepickerUpdate" value="{{ date('m/d/Y',strtotime($consultation->date_sorethroat)) }}"></td>
                        </tr>
                        <tr>
                            <td>
                                <label>
                                    <input type="checkbox" name="diarrhea" value="Y" @if($consultation->diarrhea=='Y') checked @endif>
                                    Diarrhea
                                </label>
                            </td>
                            <td><input type="text" name="date_diarrhea" id="" class="form-control form-control-sm datepickerUpdate" value="{{ date('m/d/Y',strtotime($consultation->date_diarrhea)) }}"></td>
                        </tr>
                        <tr>
                            <td>
                                <label>
                                    <input type="checkbox" name="bd" value="Y" @if($consultation->dob=='Y') checked @endif>
                                    Breathing Difficulty
                                </label>
                            </td>
                            <td><input type="text" name="date_bd" id="" class="form-control form-control-sm datepickerUpdate" value="{{ date('m/d/Y',strtotime($consultation->date_dob)) }}"></td>
                        </tr>
                    </table>
                </fieldset>
                <fieldset>
                    <legend>Travel History</legend>
                    <div class="form-group">
                        <label for="">Travel History?</label>
                        &nbsp;&nbsp;
                        <label for="travelyes" style="font-size: 0.9em; font-weight: normal;">
                            <input type="radio" name="travel" id="travelyes" value="Y" @if($consultation->travel=='Y') checked @endif> Yes
                        </label>
                        <label for="travelno" style="font-size: 0.9em; font-weight: normal;">
                            <input type="radio" name="travel" id="travelno" value="N" @if($consultation->travel!='Y') checked @endif> No
                        </label>
                        <textarea name="travel_address" class="form-control" rows="2" style="resize: none;" placeholder="if Yes, please specify!">{{ $consultation->travel_address }}</textarea>
                        <input type="text" name="date_travel" value="@if($consultation->travel=='Y'){{ date('m/d/Y',strtotime($consultation->date_travel)) }}@endif" class="datepickerUpdate form-control form-control-sm">
                    </div>
                </fieldset>
                <fieldset>
                    <legend>Status</legend>
                    <div class="form-group">
                        <textarea name="outcome" class="form-control mb-2" rows="2" style="resize: none;" placeholder="Outcome">{{ $info->outcome }}</textarea>
                        <label for="">Patient Died?</label>&nbsp;&nbsp;
                        <label for="died" style="font-size: 0.9em; font-weight: normal;">
                            <input type="checkbox" name="died" id="died" value="Y" @if($info->died=='Y') checked @endif> Yes
                        </label>
                        <input type="text" value="@if($info->died=='Y'){{ date('m/d/Y',strtotime($info->date_died)) }}@endif" name="date_died"  class="datepickerUpdate form-control form-control-sm">
                    </div>
                </fieldset>
            </div>
            <div class="col-md-12">

            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-info">Update</button>
    </div>
</form>
