<div class="form-group">
    <label for="CaseClassification"><b class="text-danger">*</b>Case Classification</label>
    <select class="form-control" name="CaseClassification" id="CaseClassification" required>
        <option value="" disabled {{(is_null(old('CaseClassification'))) ? 'selected' : ''}}>Choose...</option>
        <option value="S" {{(old('CaseClassification') == 'S') ? 'selected' : ''}}>Suspected</option>
        <option value="P" {{(old('CaseClassification') == 'P') ? 'selected' : ''}}>Probable</option>
        <option value="C" {{(old('CaseClassification') == 'C') ? 'selected' : ''}}>Confirmed</option>
    </select>
</div>