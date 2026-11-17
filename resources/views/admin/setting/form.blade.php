<div class="row">

    <div class="col-md-6 mb-3">
        <label class="form-label">Setting Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $setting->name ?? '') }}"
            placeholder="Enter setting name">
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Key</label>
        <input type="text" name="key" class="form-control" value="{{ old('key', $setting->key ?? '') }}"
            placeholder="Enter setting key">
    </div>

    <div class="col-md-12 mb-3">
        <label class="form-label">Value</label>
        <textarea name="value" class="form-control" rows="3"
            placeholder="Enter setting value">{{ old('value', $setting->value ?? '') }}</textarea>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Data Type</label>
        <input type="text" name="dataType" class="form-control" value="{{ old('dataType', $setting->dataType ?? '') }}"
            placeholder="Example: string, integer, boolean, json">
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Category ID</label>
        <input type="number" name="category_id" class="form-control"
            value="{{ old('category_id', $setting->category_id ?? '') }}" placeholder="Enter category ID">
    </div>

    <div class="col-md-12 mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3"
            placeholder="Enter description">{{ old('description', $setting->description ?? '') }}</textarea>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
            <option value="show" {{ old('status', $setting->status ?? 'hide') == 'show' ? 'selected' : '' }}>
                Show
            </option>

            <option value="hide" {{ old('status', $setting->status ?? 'hide') == 'hide' ? 'selected' : '' }}>
                Hide
            </option>
        </select>
    </div>

</div>