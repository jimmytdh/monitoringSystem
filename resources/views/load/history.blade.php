<style>
    table td { vertical-align: top; }
</style>

<div class="col-sm-12 mt-2 mb-2">
    <div id="accordion">
        @foreach($data as $row)
        <div class="card">
            <div class="card-header card-header-{{ ($row->transaction=='Consultation') ? 'success':'danger' }}" id="headingOne{{ $row->id }}">
                <h5 class="mb-0">
                    <div class="row">
                        <div class="col-sm-6">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne{{ $row->id }}" aria-expanded="true" aria-controls="collapseOne">
                                {{ $row->transaction }}
                            </button>
                        </div>
                        <div class="col-sm-6 text-right">
                            <strong><small class="text-danger">[ {{ date('M d, Y h:i A',strtotime($row->date_transaction)) }} ]</small></strong>
                        </div>
                    </div>


                </h5>
            </div>

            <div id="collapseOne{{ $row->id }}" class="collapse" aria-labelledby="headingOne{{ $row->id }}" data-parent="#accordion">
                <div class="card-body" style="padding:0px 10px 10px 10px;">
                    @if($row->transaction=='Consultation')
                        <?php $c = \App\Consultation::find($row->ref_id); ?>
                            <fieldset disabled>
                                <legend>Disposition</legend>
                                <table class="" width="100%">
                                    <tr>
                                        <td>Home Isolation?</td>
                                        <td>: {{ ($c->home_isolation=='Y') ? 'Yes' : 'No' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Co-Morbid?</td>
                                        <td>: {{ ($c->comorbid=='Y') ? 'Yes' : 'No' }}</td>
                                    </tr>
                                    @if($c->comorbid=='Y')
                                        <tr>
                                            <td>Co-Morbidity</td>
                                            <td style="white-space: normal">: {!!  \App\Http\Controllers\ReportCtrl::getPatMorbidity($c->pat_id,$c->date_consultation)  !!}</td>
                                        </tr>
                                    @endif
                                </table>
                            </fieldset>
                            @if($c->fever || $c->cough || $c->colds || $c->sorethroat || $c->diarrhea || $c->dob)
                                <fieldset disabled>
                                    <legend>Signs and Symptoms</legend>
                                    <table>
                                        @if($c->fever)
                                        <tr>
                                            <td>Fever</td>
                                            <td>: {{ date('M d, Y',strtotime($c->date_fever)) }}</td>
                                        </tr>
                                        @endif

                                        @if($c->cough)
                                            <tr>
                                                <td>Cough</td>
                                                <td>: {{ date('M d, Y',strtotime($c->date_cough)) }}</td>
                                            </tr>
                                        @endif

                                        @if($c->colds)
                                            <tr>
                                                <td>Colds</td>
                                                <td>: {{ date('M d, Y',strtotime($c->date_colds)) }}</td>
                                            </tr>
                                        @endif

                                        @if($c->sorethroat)
                                            <tr>
                                                <td>Sore throat</td>
                                                <td>: {{ date('M d, Y',strtotime($c->date_sorethroat)) }}</td>
                                            </tr>
                                        @endif

                                        @if($c->diarrhea)
                                            <tr>
                                                <td>Diarrhea</td>
                                                <td>: {{ date('M d, Y',strtotime($c->date_diarrhea)) }}</td>
                                            </tr>
                                        @endif

                                        @if($c->dob)
                                            <tr>
                                                <td>Breathing Difficulty</td>
                                                <td>: {{ date('M d, Y',strtotime($c->date_dob)) }}</td>
                                            </tr>
                                        @endif
                                    </table>
                                </fieldset>
                            @endif

                            @if($c->travel=='Y')
                                <fieldset disabled>
                                    <legend>Travel</legend>
                                    {!! $c->travel_address !!}
                                </fieldset>
                            @endif
                    @elseif($row->transaction=='Admitted')
                        <?php $adm = \App\Admit::find($row->ref_id);?>
                        <fieldset disabled>
                            <legend>Diagnosis/Status</legend>
                            {!! $adm->status !!}
                        </fieldset>

                    @elseif($row->transaction=='Discharged')
                        <?php $adm = \App\Admit::find($row->ref_id);?>
                        <fieldset disabled>
                            <legend>Diagnosis/Status</legend>
                            {!! $adm->disposition !!}
                        </fieldset>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
