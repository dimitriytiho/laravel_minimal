<?php


namespace App\Helpers;


class Html
{
    /*
     * Возвращает input для формы.
     * $name - передать название, перевод будет взять из /resources/lang/en/s.php.
     * $idForm - если используется несколько форм на странице, то передайте id формы, чтобы id оригинальные.
     * $required - если input необязательный, то передайте null, необязательный параметр.
     * $type - тип input, по-умолчанию text, необязательный параметр.
     * $value - передать значение, необязательный параметр.
     * $label - если он нужен, то передать true, необязательный параметр.
     * $placeholder - если нужен другой текст, то передать его, необязательный параметр.
     * $class - передайте свой класс, необязательный параметр.
     * $attrs - передайте атрибуты строкой или в массиве ['id' => 'test', 'data-id' => 'dataTest', 'novalidate' => ''], необязательный параметр.
     */
    public static function input($name, $idForm = false, $required = true, $type = false, $value = false, $label = false, $placeholder = false, $class = false, $attrs = false)
    {
        $title = $placeholder ?: Func::__($name, 's');
        $id = $idForm ? "{$idForm}_{$name}" : $name;

        $required = $required ? 'required' : null;
        $type = $type && $type !== true ? $type : 'text';
        $star = $required ? '<sup>*</sup>' : null;
        $value = $value ?: old($name);

        $placeholderStar = !$label && $required ? '*' : null;
        $placeholder = $title . $placeholderStar;
        $label = $label ? null : 'class="sr-only"';

        $part = '';
        if ($attrs && is_array($attrs)) {
            foreach ($attrs as $k => $v) {
                $part .= "{$k}='{$v}' ";
            }
        } else {
            $part = is_string($attrs) ? $attrs : null;
        }

        return <<<S
<div class="form-group {$class}">
    <label for="{$id}" {$label}>$title $star</label>
    <input type="{$type}" name="{$name}" id="{$id}" class="form-control" placeholder="{$placeholder}" value="{$value}" $part {$required}>
</div>
S;
    }


    /*
     * Возвращает textarea для формы.
     * $name - передать название, перевод будет взять из /resources/lang/en/s.php.
     * $idForm - если используется несколько форм на странице, то передайте id формы, чтобы id оригинальные.
     * $required - если input необязательный, то передайте null, необязательный параметр.
     * $value - передать значение, необязательный параметр.
     * $label - если он нужен, то передать true, необязательный параметр.
     * $placeholder - если нужен другой текст, то передать его, необязательный параметр.
     * $class - передайте свой класс, необязательный параметр.
     * $attrs - передайте атрибуты строкой или в массиве ['id' => 'test', 'data-id' => 'dataTest', 'novalidate' => ''], необязательный параметр.
     * $rows - кол-во рядов, по-умолчанию 3, необязательный параметр.
     * $htmlspecialchars - $value обёртываем в функцию htmlspecialchars, передайте false, если не надо.
     */
    public static function textarea($name, $idForm = false, $required = true, $value = false, $label = false, $placeholder = false, $class = false, $attrs = false, $rows = 3, $htmlspecialchars = true)
    {
        $title = $placeholder ?: Func::__($name, 's');
        $id = $idForm ? "{$idForm}_{$name}" : $name;
        $required = $required ? 'required' : null;
        $star = $required ? '<sup>*</sup>' : null;
        $value = $value ?: old($name);
        $value = $htmlspecialchars ? e($value) : $value;

        $placeholderStar = !$label && $required ? '*' : null;
        $placeholder = $title . $placeholderStar;

        $label = $label ? null : 'class="sr-only"';
        $rows = (int)$rows;

        $part = '';
        if ($attrs && is_array($attrs)) {
            foreach ($attrs as $k => $v) {
                $part .= "{$k}='{$v}' ";
            }
        } else {
            $part = is_string($attrs) ? $attrs : null;
        }

        return <<<S
<div class="form-group">
    <label for="{$id}" {$label}>$title $star</label>
    <textarea name="{$name}" id="{$id}" class="form-control {$class}" placeholder="{$placeholder}" rows="{$rows}" $part {$required}>{$value}</textarea>
</div>
S;
    }


    /*
     * Возвращает select для формы.
     * $name - передать название, перевод будет взять из /resources/lang/en/s.php.
     * $options - передать options, строкой, массивом или объектом (если $value будет равна одму из значений этого массива, то этот option будет selected).
     * $idForm - если используется несколько форм на странице, то передайте id формы, чтобы id оригинальные.
     * $value - передать значение, необязательный параметр.
     * $label - если он нужен, то передать true, необязательный параметр.
     * $class - передайте свой класс, необязательный параметр.
     * $attrs - передайте атрибуты строкой или в массиве ['id' => 'test', 'data-id' => 'dataTest', 'novalidate' => ''], необязательный параметр.
     * $disabledValue - передать значения, для которого установить атрибут disabled.
     * $option_id_value - передайте true, если передаёте массив $options, в котором ключи это id для вывода как значения для option, необязательный параметр.
     * $langFile - название файла из /resources/lang/en/t.php (этот файл по-умолчанию), необязательный параметр.
     * $classSelect - передайте свой класс в select, необязательный параметр.
    , $classSelect = null
     */
    public static function select($name, $options, $idForm = null, $value = null, $label = false, $class = null, $attrs = false, $disabledValue = null, $option_id_value = null, $langFile = 't', $classSelect = null)
    {
        $title = Func::__($name, 's');
        $id = $idForm ? "{$idForm}_{$name}" : $name;
        $value = $value ?: old($name);
        $label = $label ? null : 'class="sr-only"';

        // Принимает в объекте 2 параметра, первый - value для option, второй название для option
        $opts = '';
        if (is_array($options)) {
            foreach ($options as $k => $v) {
                $v = $option_id_value ? $k : $v;
                $selected = $value === $v ? ' selected' : null;
                $disabled = $disabledValue && $k == $disabledValue ? ' disabled' : null;
                $t = l($v, $langFile);
                $opts .= "<option value='{$v}' {$selected}{$disabled}>{$t}</option>\n";

            }
        } else {
            $opts = $options;
        }

        $part = '';
        if ($attrs && is_array($attrs)) {
            foreach ($attrs as $k => $v) {
                $part .= "{$k}='{$v}' ";
            }
        } else {
            $part = is_string($attrs) ? $attrs : null;
        }

        return <<<S
<div class="form-group {$class}">
    <label for="{$id}" {$label}>{$title}</label>
    <select class="form-control {$classSelect}" name="{$name}" id="{$id}" {$part}>
        $opts
    </select>
</div>
S;
    }


    /*
     * Возвращает checkbox для формы.
     * $name - передать название, перевод будет взять из /resources/lang/en/s.php.
     * $idForm - если используется несколько форм на странице, то передайте id формы, чтобы id оригинальные.
     * $required - если необязательный, то передайте null, необязательный параметр.
     * $checked - Если checkbox должен быть нажат, то передайте true, необязательный параметр.
     * $class - Передайте свой класс, необязательный параметр.
     * $title - Можно передать свой заголовок, например с ссылкой, необязательный параметр.
     * $value - значение элемента, необязательный параметр.
     * $attrs - передайте атрибуты строкой или в массиве ['id' => 'test', 'data-id' => 'dataTest', 'novalidate' => ''], необязательный параметр.
     */
    public static function checkbox($name, $idForm = false, $required = true, $checked = false, $class = false, $title = false, $value = false, $attrs = false)
    {
        $_title = Func::__($name, 's');
        $title = $title ?: $_title;
        $id = $idForm ? "{$idForm}_{$name}" : $name;
        $value = $value ? "value=\"{$value}\"" : null;

        $checked = $checked || old($name) ? 'checked' : null;
        $required = $required ? 'required' : null;

        $part = '';
        if ($attrs && is_array($attrs)) {
            foreach ($attrs as $k => $v) {
                $part .= "{$k}='{$v}' ";
            }
        } else {
            $part = is_string($attrs) ? $attrs : null;
        }

        return <<<S
<div class="{$class}">
    <div class="custom-control custom-checkbox my-3">
        <input type="checkbox" class="custom-control-input" name="{$name}" id="{$id}" $value $part $checked {$required}>
        <label class="custom-control-label" for="{$id}">{$title}</label>
    </div>
</div>
S;
    }

    public static function checkboxSimple($name, $idForm = false, $required = true, $checked = false, $class = false, $title = false, $value = false, $attrs = false)
    {
        $_title = Func::__($name, 's');
        $title = $title ?: $_title;
        $id = $idForm ? "{$idForm}_{$name}" : $name;
        $value = $value ? "value=\"{$value}\"" : null;

        $checked = $checked || old($name) ? 'checked' : null;
        $required = $required ? 'required' : null;

        $part = '';
        if ($attrs && is_array($attrs)) {
            foreach ($attrs as $k => $v) {
                $part .= "{$k}='{$v}' ";
            }
        } else {
            $part = is_string($attrs) ? $attrs : null;
        }

        return <<<S
<div class="form-group {$class}">
    <div class="form-check mt-3 mb-2">
        <input class="form-check-input" type="checkbox" name="{$name}" id="{$id}" $value $part $checked {$required}>
        <label class="form-check-label" for="{$id}">{$title}</label>
    </div>
</div>
S;
    }

    public static function checkboxSwitch($name, $idForm = false, $required = true, $checked = false, $class = false, $title = false, $value = false, $attrs = false)
    {
        $_title = Func::__($name, 's');
        $title = $title ?: $_title;
        $id = $idForm ? "{$idForm}_{$name}" : $name;
        $value = $value ? "value=\"{$value}\"" : null;

        $checked = $checked || old($name) ? 'checked' : null;
        $required = $required ? 'required' : null;

        $part = '';
        if ($attrs && is_array($attrs)) {
            foreach ($attrs as $k => $v) {
                $part .= "{$k}='{$v}' ";
            }
        } else {
            $part = is_string($attrs) ? $attrs : null;
        }

        return <<<S
<div class="{$class}">
    <div class="custom-control custom-switch my-3">
        <input type="checkbox" class="custom-control-input" name="{$name}" id="{$id}" $value $part $checked {$required}>
        <label class="custom-control-label" for="{$id}">{$title}</label>
    </div>
</div>
S;
    }


    /*
     * Возвращает checkbox для формы.
     * $name - передать название, перевод будет взять из /resources/lang/en/s.php.
     * $value - значение radio элемента.
     * $idForm - если используется несколько форм на странице, то передайте id формы, чтобы id оригинальные.
     * $required - если необязательный, то передайте null, необязательный параметр.
     * $checked - Если checkbox должен быть нажат, то передайте true, необязательный параметр.
     * $class - Передайте свой класс, необязательный параметр.
     * $title - Можно передать свой заголовок, например с ссылкой, необязательный параметр.
     * $attrs - передайте атрибуты строкой или в массиве ['id' => 'test', 'data-id' => 'dataTest', 'novalidate' => ''], необязательный параметр.
     */
    public static function radio($name, $value, $idForm = false, $required = true, $checked = false, $class = false, $title = false, $attrs = false)
    {
        $_title = Func::__($name, 's');
        $title = $title ?: $_title;
        $id = $idForm ? "{$idForm}_{$name}_{$value}" : $name;

        $checked = $checked || old($name) ? 'checked' : null;
        $required = $required ? 'required' : null;

        $part = '';
        if ($attrs && is_array($attrs)) {
            foreach ($attrs as $k => $v) {
                $part .= "{$k}='{$v}' ";
            }
        } else {
            $part = is_string($attrs) ? $attrs : null;
        }

        return <<<S
<div class="custom-control custom-radio {$class}">
    <input type="radio" class="custom-control-input" id="{$id}" name="{$name}" value="{$value}" $part $checked {$required}>
    <label class="custom-control-label" for="{$id}">{$title}</label>
</div>
S;
    }


    /*
     * Возвращает radio в виде иконок для формы.
     * $name - передать название, перевод будет взять из /app/Modules/lang/en/f.php.
     * $value - значение radio элемента.
     * $idForm - если используется несколько форм на странице, то передайте id формы, чтобы id оригинальные.
     * $required - если необязательный, то передайте null, необязательный параметр.
     * $checked - Если checkbox должен быть нажат, то передайте true, необязательный параметр.
     * $class - Передайте свой класс, необязательный параметр.
     * $title - Можно передать свой заголовок, например с ссылкой, необязательный параметр.
     * $icon - Классы для иконки fontawesome, необязательный параметр.
     * $attrs - передайте атрибуты строкой или в массиве ['id' => 'test', 'data-id' => 'dataTest', 'novalidate' => ''], необязательный параметр.
     */
    public static function radioBtn($name, $value, $idForm = false, $required = true, $checked = false, $class = false, $title = false, $icon = false, $attrs = false)
    {
        $_title = Func::__($name, 's');
        $title = $title ?: $_title;
        $id = $idForm ? "{$idForm}_{$name}_{$value}" : $name;

        $checked = $checked || old($name) ? 'checked' : null;
        $checkedClass = $checked ? 'active' : null;
        $required = $required ? 'required' : null;
        $icon = $icon ? "<i class=\"{$icon}\"></i>" : null;

        $part = '';
        if ($attrs && is_array($attrs)) {
            foreach ($attrs as $k => $v) {
                $part .= "{$k}='{$v}' ";
            }
        } else {
            $part = is_string($attrs) ? $attrs : null;
        }

        return <<<S
<div class="custom-control custom-radio custom-control-inline my-1 radio_btn $checkedClass {$class}" {$part}>
    <input type="radio" class="custom-control-input" id="{$id}" name="{$name}" value="{$value}" $checked {$required}>
    <label class="custom-control-label" for="{$id}">
        $icon
        <span>{$title}</span>
    </label>
</div>
S;
    }


    /*
     * Возвращает radio в виде текста для формы.
     * $name - передать название, перевод будет взять из /app/Modules/lang/en/f.php.
     * $value - значение radio элемента.
     * $idForm - если используется несколько форм на странице, то передайте id формы, чтобы id оригинальные.
     * $required - если необязательный, то передайте null, необязательный параметр.
     * $checked - Если checkbox должен быть нажат, то передайте true, необязательный параметр.
     * $class - Передайте свой класс, необязательный параметр.
     * $title - Можно передать свой заголовок, например с ссылкой, необязательный параметр.
     * $text - Дополнительный текст, он выделен жирным, необязательный параметр.
     * $attrs - передайте атрибуты строкой или в массиве ['id' => 'test', 'data-id' => 'dataTest', 'novalidate' => ''], необязательный параметр.
     */
    public static function radioText($name, $value, $idForm = false, $required = true, $checked = false, $class = false, $title = false, $text = false, $attrs = false)
    {
        $_title = Func::__($name, 's');
        $title = $title ?: $_title;
        $id = $idForm ? "{$idForm}_{$name}_{$value}" : $name;

        $checked = $checked || old($name) ? 'checked' : null;
        $checkedClass = $checked ? 'active' : null;
        $required = $required ? 'required' : null;
        $text = $text ? "<b class=\"h5\">{$text}</b>" : null;

        $part = '';
        if ($attrs && is_array($attrs)) {
            foreach ($attrs as $k => $v) {
                $part .= "{$k}='{$v}' ";
            }
        } else {
            $part = is_string($attrs) ? $attrs : null;
        }

        return <<<S
<div class="custom-control custom-radio custom-control-inline my-1 radio_btn $checkedClass {$class}" {$part}>
    <input type="radio" class="custom-control-input" id="{$id}" name="{$name}" value="{$value}" $checked {$required}>
    <label class="custom-control-label" for="{$id}">
        $text
        <span class="text-sm">{$title}</span>
    </label>
</div>
S;
    }


    /*
     * Возвращает скрытый input для формы.
     * $name - Передать имя input.
     * $value - Значение, необязательный параметр.
     * $attrs - передайте атрибуты строкой или в массиве ['id' => 'test', 'data-id' => 'dataTest', 'novalidate' => ''], необязательный параметр.
     */
    public static function hidden($name, $value = false, $attrs = false)
    {
        $part = '';
        if ($attrs && is_array($attrs)) {
            foreach ($attrs as $k => $v) {
                $part .= "{$k}='{$v}' ";
            }
        } else {
            $part = is_string($attrs) ? $attrs : null;
        }
        return "<input type=\"hidden\" name=\"{$name}\" value=\"{$value}\" {$part}>";
    }
}
