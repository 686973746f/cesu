<div class="form-group">
    <label for="Outcome"><span class="text-danger font-weight-bold">*</span>Outcome</label>
    <select class="form-control" name="Outcome" id="Outcome" required>
        <option value="" disabled {{(is_null(old('Outcome'))) ? 'selected' : ''}}>Choose...</option>
        <option value="A" {{(old('Outcome') == 'A') ? 'selected' : ''}}>Alive</option>
        <option value="HAMA" {{(old('Outcome') == 'HAMA') ? 'selected' : ''}}>Home Against Medical Advice (HAMA)</option>
        <option value="D" {{(old('Outcome') == 'D') ? 'selected' : ''}}>Died</option>
    </select>
</div>
<div id="died_div" class="d-none">
    <div class="form-group">
        <label for="DateDied"><b class="text-danger">*</b>Date Died</label>
        <input type="date" class="form-control" name="DateDied" id="DateDied" value="{{old('DateDied')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}">
    </div>
</div>

<script>
    $('#Outcome').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'D') {
            $('#died_div').removeClass('d-none');
            $("#DateDied").prop('required', true);
        }
        else {
            $('#died_div').addClass('d-none');
            $("#DateDied").prop('required', false);
        }
    }).trigger('change');
</script>