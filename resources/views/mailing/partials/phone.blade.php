
<div class="mb-3 js-mailing-create__phone-input-box">
    <div class="input-group">
        <input value="{{ $phoneV }}" type="tel" name="phone[]" class="form-control js-mailing-create__phone-input" placeholder="Phone" aria-label="Phone">
        <button class="btn btn-outline-secondary js-mailing-create__phone-input-btn-add" type="button">Add</button>
        <button class="btn btn-outline-secondary js-mailing-create__phone-input-btn-del" type="button">Delete</button>
    </div>
    @error('phone.' . $phoneK)
    <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
