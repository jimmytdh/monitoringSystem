<style>
    table tr td {
        vertical-align: middle;
    }
</style>
<form action="{{ url('admitted/services/'.$id) }}" method="post">
    {{ csrf_field() }}
   <div class="modal-body">
       <label>Date</label>:
       <input type="text" class="form-control form-control-sm" id="datetimepicker" name="date_given" />
       <hr>
       <table class="table table-sm table-bordered table-striped">
           <tr>
               <th>Services</th>
               <th>Amount</th>
               <th>Qty</th>
           </tr>
           @foreach($data as $row)
           <tr>
               <td>
                   <label class="ml-2">
                       <input type="checkbox" name="services[]" value="{{ $row->id }}" />
                       {{ $row->name }}
                   </label>
               </td>
               <td width="25%">
                   <input type="text" name="amount[{{ $row->id }}]" value="{{ $row->amount }}" class="form-control form-control-sm">
               </td>
               <td width="20%">
                   <input type="number" name="qty[{{ $row->id }}]" min="1" value="1" class="form-control form-control-sm">
               </td>
           </tr>
           @endforeach
       </table>
   </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success">Submit</button>
    </div>
</form>
