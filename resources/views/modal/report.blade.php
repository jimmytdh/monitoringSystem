<?php
    if(!$search){
        $search = array(
            "fname" => '',
            "lname" => '',
            "sex" => '',
            "age" => '',
            "province" => '',
            "muncity" => '',
            "brgy" => '',
            "comorbid" => '',
            "home_isolation" => '',
            "travel" => '',
            "fever" => '',
            "cough" => '',
            "colds" => '',
            "sorethroat" => '',
            "diarrhea" => '',
            "start" => \Carbon\Carbon::today()->startOfMonth()->format('m/d/Y'),
            "end" => \Carbon\Carbon::today()->endOfMonth()->format('m/d/Y')
        );
    }
?>
<div class="modal" tabindex="-1" role="dialog" id="filterReport">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ url('/report') }}" method="post">
                {{ csrf_field() }}
                <div class="modal-header bg-success">
                    <h5 class="modal-title">Filter Report</h5>
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
                                    <input type="text" name="fname" value="{{ $search['fname'] }}" id="fname" class="form-control-sm form-control" placeholder="First Name">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="lname" value="{{ $search['lname'] }}" id="lname" class="form-control-sm form-control" placeholder="Family Name">
                                </div>
                                <div class="form-group">
                                    <label for="allsex" style="font-size: 0.9em; font-weight: normal;">
                                        <input type="radio" name="sex" id="allsex" value="" @if($search['sex']=='') checked @endif> All
                                    </label>
                                    &nbsp;&nbsp;
                                    <label for="male" style="font-size: 0.9em; font-weight: normal;">
                                        <input type="radio" name="sex" id="male"  value="M" @if($search['sex']=='M') checked @endif> Male
                                    </label>
                                    &nbsp;&nbsp;
                                    <label for="female" style="font-size: 0.9em; font-weight: normal;">
                                        <input type="radio" name="sex" id="female" value="F" @if($search['sex']=='F') checked @endif> Female
                                    </label>
                                </div>
                                <div class="form-group">
                                    <div class="form-group">
                                        <select name="age" class="brgy form-control form-control-sm">
                                            <option value="">Select Age Bracket...</option>
                                            <option @if($search['age']=='0-10') selected @endif>0-10</option>
                                            <option @if($search['age']=='11-20') selected @endif>11-20</option>
                                            <option @if($search['age']=='21-30') selected @endif>21-30</option>
                                            <option @if($search['age']=='31-40') selected @endif>31-40</option>
                                            <option @if($search['age']=='41-50') selected @endif>41-50</option>
                                            <option @if($search['age']=='51-60') selected @endif>51-60</option>
                                            <option @if($search['age']=='61-70') selected @endif>61-70</option>
                                            <option @if($search['age']=='71-80') selected @endif>71-80</option>
                                            <option @if($search['age']=='80+') selected @endif>80+</option>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>Address</legend>
                                <div class="form-group">
                                    <select name="province" class="province form-control form-control-sm">
                                        <option value="">Select Province...</option>
                                        @foreach($province as $p)
                                            <option value="{{ $p->code }}">{{ $p->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select name="muncity" class="muncity form-control form-control-sm">
                                        <option value="">Select Municipality/City...</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select name="brgy" class="brgy form-control form-control-sm">
                                        <option value="">Select Barangay...</option>
                                    </select>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-6">
                            <fieldset>
                                <legend>Consultation</legend>
                                <div class="form-group">
                                    <input type="text" name="daterange" id="daterange" value="{{ \Carbon\Carbon::parse($search['start'])->format('m/d/Y')." - ".\Carbon\Carbon::parse($search['end'])->format('m/d/Y') }}" class="form-control-sm form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Co-Morbid?</label>
                                    <label for="allmorbid" style="font-size: 0.9em; font-weight: normal;">
                                        <input type="radio" name="comorbid" id="allmorbid" value="" @if($search['comorbid']=='') checked @endif> All
                                    </label>
                                    &nbsp;&nbsp;
                                    <label for="comorbidyes" style="font-size: 0.9em; font-weight: normal;">
                                        <input type="radio" name="comorbid" class="confirm_comorbid" id="comorbidyes" value="Y" @if($search['comorbid']=='Y') checked @endif> Yes
                                    </label>
                                    &nbsp;&nbsp;
                                    <label for="comorbidno" style="font-size: 0.9em; font-weight: normal;">
                                        <input type="radio" name="comorbid" class="confirm_comorbid" id="comorbidno" value="N" @if($search['comorbid']=='N') checked @endif> No
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="">Home Isolation?</label>
                                    &nbsp;&nbsp;
                                    <label for="alliso" style="font-size: 0.9em; font-weight: normal;">
                                        <input type="radio" name="home_isolation" id="alliso" value="" @if($search['home_isolation']=='') checked @endif> All
                                    </label>
                                    &nbsp;&nbsp;
                                    <label for="homeisoyes" style="font-size: 0.9em; font-weight: normal;">
                                        <input type="radio" name="home_isolation" id="homeisoyes" value="Y" @if($search['home_isolation']=='Y') checked @endif> Yes
                                    </label>
                                    &nbsp;&nbsp;
                                    <label for="homeisono" style="font-size: 0.9em; font-weight: normal;">
                                        <input type="radio" name="home_isolation" id="homeisono" value="N" @if($search['home_isolation']=='N') checked @endif> No
                                    </label>
                                </div>
                                <table class="table table-sm">
                                    <tr>
                                        <td width="50%">
                                            <label>
                                                <input type="checkbox" name="fever" value="Y" id="" @if($search['fever']=='Y') checked @endif>
                                                Fever
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input type="checkbox" name="cough" value="Y" id="" @if($search['cough']=='Y') checked @endif>
                                                Cough
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>
                                                <input type="checkbox" name="colds" value="Y" id="" @if($search['colds']=='Y') checked @endif>
                                                Colds
                                            </label>
                                        </td>
                                        <td>
                                            <label>
                                                <input type="checkbox" name="sorethroat" value="Y" id="" @if($search['sorethroat']=='Y') checked @endif>
                                                Sore Throat
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>
                                                <input type="checkbox" name="diarrhea" value="Y" id="" @if($search['diarrhea']=='Y') checked @endif>
                                                Diarrhea
                                            </label>
                                        </td>
                                        <td></td>
                                    </tr>
                                </table>
                            </fieldset>
                            <fieldset>
                                <legend>Travel History</legend>
                                <div class="form-group">
                                    <label for="">Travel History?</label>
                                    &nbsp;&nbsp;
                                    <label for="alltravel" style="font-size: 0.9em; font-weight: normal;">
                                        <input type="radio" name="travel" id="alltravel" value="" @if($search['travel']=='') checked @endif> All
                                    </label>
                                    &nbsp;&nbsp;
                                    <label for="travelyes" style="font-size: 0.9em; font-weight: normal;">
                                        <input type="radio" name="travel" id="travelyes" value="Y" @if($search['travel']=='Y') checked @endif> Yes
                                    </label>
                                    &nbsp;&nbsp;
                                    <label for="travelno" style="font-size: 0.9em; font-weight: normal;">
                                        <input type="radio" name="travel" id="travelno" value="N" @if($search['travel']=='N') checked @endif> No
                                    </label>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ url('/report/reset') }}" class="btn btn-warning">Reset Filter</a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>
