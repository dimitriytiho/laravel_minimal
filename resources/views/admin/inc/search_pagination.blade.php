@if(config('admin.pagination'))
    <div class="col-sm-2 col-xl-1 ml-0 ml-md-auto mt-2">
        <div class="dataTables_length">
            <label>
                <select aria-controls="quantity_pagination" class="custom-select custom-select-sm select_change" data-url="{{ route('admin.get-session') }}" data-key="pagination">
                    @foreach(config('admin.pagination') as $qty)
                        <option value="{{ $qty }}" {{ $qty == (session('pagination') ?: config('admin.pagination_default')) ? 'selected' : null }}>{{ $qty }}</option>
                    @endforeach
                </select>
            </label>
        </div>
    </div>
@endif
