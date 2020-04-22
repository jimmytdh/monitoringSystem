<table border="1">
    <thead>
    <tr>
        <th>Municipality of DRU</th>
        <th>Type of DRU</th>
        <th>Address of DRU/Facility</th>
        <th>Name of DRU/ Facility</th>
        <th>Name of Patient (FULL NAME)</th>
        <th>Patients First Name</th>
        <th>Patients Last Name</th>
        <th>Age in Years</th>
        <th>Sex</th>
        <th>Date of Birth</th>
        <th>Province (Patients Address)</th>
        <th>Municipalities/Cities (Patients Address)</th>
        <th>Barangay (Patients Address)</th>
        <th>Household No./Purok/Sitio (Patients Address)</th>
        <th>Admitted?</th>
        <th>Date of Admission/ Consultation</th>
        <th>Date of Onset</th>
        <th>Name of Disease Surveillance Coordinator (Reported by)</th>
        <th>DSC Contact Number</th>
        <th>Influenza-like- illness (ILI)/ Severe Acute Respiratory Infection (SARI) Category</th>
        <th>IF Category 2 or Category 3: indicate the Date of Specimen Collection</th>
        <th>Admitting diagnosis/ Final Diagnosis</th>
        <th>W/ FEVER</th>
        <th>W/ COLDS</th>
        <th>W/ COUGH</th>
        <th>W/ SORE THROAT</th>
        <th>W/ DIARRHEA</th>
        <th>W/ DIFFICULTY OF BREATHING</th>
        <th>Travel History within 14 days (pls specify) write NA if not applicable</th>
        <th>Parent Name/ Contact Person (FULL NAME)</th>
        <th>No. of Person Living in the household</th>
        <th>Outcome</th>
        <th>Date Died</th>

    </tr>
    </thead>
    <tbody>
    @foreach($data as $row)
    <tr>
        <th>Talisay City</th>
        <th>Government Hospital</th>
        <th>San Isidro, Talisay City, Cebu</th>
        <th>Talisay District Hospital</th>
        <th>{{ $row->fname }} {{ $row->mname }} {{ $row->lname }}</th>
        <th>{{ $row->fname }}</th>
        <th>{{ $row->lname }}</th>
        <th>{{ \App\Http\Controllers\LibraryCtrl::getAge($row->dob) }}</th>
        <th>{{ ($row->sex=='M') ? 'Male':'Female' }}</th>
        <th>{{ date('m/d/Y',strtotime($row->dob)) }}</th>
        <th>{{ \App\Http\Controllers\LocationCtrl::getProvince($row->province) }}</th>
        <th>{{ \App\Http\Controllers\LocationCtrl::getMuncity($row->muncity) }}</th>
        <th>{{ \App\Http\Controllers\LocationCtrl::getBrgy($row->brgy) }}</th>
        <th>{{ $row->purok }}</th>
        <th>{{ ($row->status=='ADM') ? 'Yes' : 'No' }}</th>
        <th>
            @if($row->status=='ADM')
                <?php
                    $admit = \App\Admit::where('pat_id',$row->id)->orderBy('date_admitted','desc')->first();
                ?>
                {{ date('m/d/Y',strtotime($admit->date_admitted)) }}
            @else
                {{ date('m/d/Y',strtotime($row->date_consultation)) }}
            @endif
        </th>
        <th>
            <?php
                $onset = array();
                if($row->fever)
                    $onset[] = $row->date_fever;
                if($row->cough)
                    $onset[] = $row->date_cough;
                if($row->colds)
                    $onset[] = $row->date_colds;
                if($row->sorethroat)
                    $onset[] = $row->date_sorethroat;
                if($row->diarrhea)
                    $onset[] = $row->date_diarrhea;
                if($row->bd)
                    $onset[] = $row->date_dob;

                sort($onset);
            ?>
            @if(count($onset) > 0)
                {{ date('m/d/Y',strtotime($onset[0])) }}
            @endif
        </th>
        <th>Sanbert Marie Garcia / June Mark Alferez</th>
        <th>0923 636 4937 / 0929 481 0766</th>
        <th></th>
        <th></th>
        <th>
            @if($row->status=='ADM')
                {{ $admit->status }}
            @endif
        </th>
        <th>{{ ($row->fever) ? 'Yes' : 'No' }}</th>
        <th>{{ ($row->colds) ? 'Yes' : 'No' }}</th>
        <th>{{ ($row->cough) ? 'Yes' : 'No' }}</th>
        <th>{{ ($row->sorethroat) ? 'Yes' : 'No' }}</th>
        <th>{{ ($row->diarrhea) ? 'Yes' : 'No' }}</th>
        <th>{{ ($row->bd) ? 'Yes' : 'No' }}</th>
        <th>
            <?php
                $str = 'N/A';
                $tmp_date = \Carbon\Carbon::parse()->addDay(-14);
                if($row->travel=='Y' && $row->date_travel>=$tmp_date)
                {
                    $str = $row->travel_address.", (".date('M d, Y',strtotime($row->date_travel)).')';
                }
            ?>
            {{ $str }}
        </th>
        <th>{{ $row->parents }}</th>
        <th>{{ $row->no_household }}</th>
        <th>{{ $row->outcome }}</th>
        <th>
            @if($row->died=='Y')
                {{ date('m/d/Y',strtotime($row->date_died)) }}
            @endif
        </th>
    </tr>
    @endforeach
    </tbody>
</table>
