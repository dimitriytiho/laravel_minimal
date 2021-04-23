@if(!empty($tree) && !empty($values))
    <div class="form-group">
        <label for="parent_id">@lang('a.parent_id')</label>
        <select class="form-control select2" name="parent_id" id="parent_id" aria-invalid="false">
            <option value="0">@lang('a.parent_id')</option>
            @php

                // Функция формируем из дерева необходимый вид
                $traverse = function ($tree, $tab = '-') use (&$traverse, $values) {
                    foreach ($tree as $item) {

                        $selected = !empty($values) && $values->parent_id == $item->id ? 'selected' : null;
                        $disabled = $item->id == request()->segment(3) ? 'disabled' : null;
                        echo "<option value='{$item->id}' $selected {$disabled}>{$tab} {$item['title']} {$item->id}</option>";

                        $traverse($item->children, $tab . $tab);
                    }
                };

                // Запускаем функцию
                $traverse($tree);

            @endphp
        </select>
    </div>
@endif
