@if(!empty($tree) && !empty($values))
    @php

        if (empty($belongItem)) {
            $belongItem = 'parent_id';
        }

    @endphp
    <div class="form-group">
        <label for="{{ $belongItem }}">@lang('a.' . $belongItem)</label>
        <select class="form-control select2" name="{{ $belongItem }}" id="{{ $belongItem }}" aria-invalid="false">
            <option value="0">@lang('a.' . $belongItem)</option>
            @php

                // Функция формируем из дерева необходимый вид
                $traverse = function ($tree, $tab = '-') use (&$traverse, $values, $belongItem) {
                    foreach ($tree as $item) {

                        $selected = !empty($values) && $values->$belongItem == $item->id ? 'selected' : null;
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
