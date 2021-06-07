<?php


namespace App\Support\Admin;

use App\Support\Func;

class Form
{
    /*
     * Возвращает input для формы.
     * $name - передать название, перевод будет взять из /resources/lang/en/s.php.
     * $value - передать значение, необязательный параметр.
     * $required - если input необязательный, то передайте null, необязательный параметр.
     * $type - тип input, по-умолчанию text, необязательный параметр.
     * $label - если он нужен, то передать true, необязательный параметр.
     * $placeholder - если нужен другой текст, то передать его, необязательный параметр.
     * $class - передайте свой класс, необязательный параметр.
     * $attrs - передайте необходимые параметры в массиве ['id' => 'test', 'data-id' => 'dataTest', 'novalidate' => ''], необязательный параметр.
     * $classInput - передайте свой класс для input, необязательный параметр.
     * $id - Передайте свой id, необязательный параметр.
     * $idForm - если используется форма несколько раз на странице, то передайте id формы, чтобы у id у чекбоксова были оригинальные id.
     * $appendAfterInput - вставить код после input, например иконку input-group-append.
     */
    public static function input($name, $value = null, $required = true, $type = null, $label = true, $placeholder = null, $class = null, $attrs = [], $classInput = null, $id = null, $idForm = null, $appendAfterInput = null)
    {
        $title = $placeholder ?: Func::__($name, 'a');
        $id = $idForm ? "{$idForm}_{$id}" : $id;
        $id = $id ?: $name;

        $required = $required ? 'required' : null;
        $type = $type && $type !== true ? $type : 'text';
        $star = $required ? '<sup>*</sup>' : null;

        // Проверим значение на 0
        if ($value === '0' || $value === 0) {
            $value = '0';
        } elseif (empty($value)) {
            $value = old($name);
        }

        $placeholderStar = !$label && $required ? '*' : null;
        $placeholder = $title . $placeholderStar;
        $label = $label ? null : 'class="sr-only"';
        $label = "<label for='{$id}' {$label}>$title $star</label>";
        $labelBefore = $appendAfterInput ? $label : null;
        $labelAfter = $appendAfterInput ? null : $label;
        $inputGroup = $appendAfterInput ? 'input-group' : 'form-group';

        $part = '';
        if ($attrs && is_array($attrs)) {
            foreach ($attrs as $k => $v) {
                $part .= "{$k}='{$v}' ";
            }
        } else {
            $part = is_string($attrs) ? $attrs : null;
        }

        return <<<S
$labelBefore
<div class="{$inputGroup} {$class}">
    $labelAfter
    <input type="{$type}" name="{$name}" id="{$id}" class="form-control {$classInput}" aria-describedby="{$name}" placeholder="{$placeholder}" value="{$value}" $part {$required}>
    $appendAfterInput
</div>
S;
    }


    /*
     * Возвращает textarea для формы.
     * $name - передать название, перевод будет взять из /resources/lang/en/s.php.
     * $value - передать значение, необязательный параметр.
     * $required - если input необязательный, то передайте null, необязательный параметр.
     * $label - если он нужен, то передать true, необязательный параметр.
     * $placeholder - если нужен другой текст, то передать его, необязательный параметр.
     * $class - передайте свой класс, необязательный параметр.
     * $attrs - передайте необходимые параметры в массиве ['id' => 'test', 'data-id' => 'dataTest', 'novalidate' => ''], необязательный параметр.
     * $rows - кол-во рядов, по-умолчанию 3, необязательный параметр.
     * $id - Передайте свой id, необязательный параметр.
     * $idForm - если используется форма несколько раз на странице, то передайте id формы, чтобы у id у чекбоксова были оригинальные id.
     * $htmlspecialchars - $value обёртываем в функцию htmlspecialchars, передайте false, если не надо.
     */
    public static function textarea($name, $value = null, $required = true, $label = true, $placeholder = null, $class = null, $attrs = [], $rows = 3, $id = null, $idForm = null, $htmlspecialchars = true)
    {
        $title = $placeholder ?: Func::__($name, 'a');
        $id = $idForm ? "{$idForm}_{$id}" : $id;
        $id = $id ?: $name;

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
     * $options - передать в массиве options (если $value будет равна одму из значений этого массива, то этот option будет selected).
     * $value - передать значение, может быть массив или объект, необязательный параметр.
     * $label - если он нужен, то передать true, необязательный параметр.
     * $class - передайте свой класс, необязательный параметр.
     * $attrs - передайте необходимые параметры в массиве ['id' => 'test', 'data-id' => 'dataTest'], необязательный параметр.
     * $option_id_value - передайте true, если передаёте массив $options, в котором ключи это id для вывода как значения для option, необязательный параметр.
     * $disabledValue - передать значения, для которого установить атрибут disabled, может быть массив или объект, необязательный параметр.
     * $multiple - для множественного select передать true, необязательный параметр.
     * $classSelect - класс в тег select, необязательный параметр.
     * $prependOption - передать option строкой, который будет перед другими, необязательный параметр.
     * $noTranslate - передать null, если не нужно переводить, необязательный параметр.
     * $id - Передайте свой id, необязательный параметр.
     * $idForm - если используется форма несколько раз на странице, то передайте id формы, чтобы у id у чекбоксова были оригинальные id, необязательный параметр.
     */
    public static function select($name, $options, $value = null, $label = true, $class = null, $attrs = null, $option_id_value = null, $disabledValue = null, $multiple = null, $classSelect = null, $prependOption = null, $noTranslate = true, $id = null, $idForm = null)
    {
        $title = Func::__($name, 'a');
        $id = $idForm ? "{$idForm}_{$id}" : $id;
        $id = $id ?: $name;
        $value = $value ?: old($name) ?: null;
        $multiple = $multiple ? ' multiple="multiple"' : null;
        $name = $multiple ? "{$name}[]" : $name;

        if (is_string($label)) {
            $labelTitle = Func::__($label, 'a');
            $labelClass = null;
        } elseif ($label) {
            $labelClass = null;
        } else {
            $labelClass = 'class="sr-only"';
        }
        $label = empty($labelTitle) ? $title : $labelTitle;

        // Принимает в объекте 2 параметра, первый - value для option, второй название для option
        $opts = '';
        if ($options) {
            foreach ($options as $k => $v) {
                $t = $noTranslate ? Func::__($v, 'a') : $v;
                $v = $option_id_value ? $k : $v;

                if (is_array($value)) {
                    $selected = in_array($v, $value) ? ' selected' : null;
                } elseif (is_object($value)) {
                    $selected = $value->contains($v) ? ' selected' : null;

                } else {
                    $selected = $value === $v ? ' selected' : null;
                }

                if (is_array($disabledValue)) {
                    $disabled = in_array($v, $disabledValue) ? ' disabled' : null;

                } elseif (is_object($disabledValue)) {
                    $disabled = $disabledValue->contains($v) ? ' selected' : null;

                } else {
                    $disabled = $disabledValue && $k == $disabledValue ? ' disabled' : null;
                }

                $opts .= "<option value='{$v}' {$selected}{$disabled}>{$t}</option>\n";

            }
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
<div class="form-group $class">
    <label for="{$id}" {$labelClass}>{$label}</label>
    <select class="form-control {$classSelect}" name="{$name}" id="{$id}" $multiple {$part}>
        $prependOption
        $opts
    </select>
</div>
S;
    }


    /*
     * Иконка для input.
     * $icon - классы иконок fontawesome.
     * $classMain - класс для основного блока, необязательный параметр.
     * $classText - класс для вложенного блока, необязательный параметр.
     * $classIcon - класс для иконки, необязательный параметр.
     * $attrs - передайте необходимые параметры строкой или в массиве ['id' => 'test', 'data-id' => 'dataTest'], необязательный параметр.
     */
    public static function inputGroupAppend($icon, $classMain = null, $classText = null, $classIcon = null, $attrs = null)
    {
        $part = '';
        if ($attrs && is_array($attrs)) {
            foreach ($attrs as $k => $v) {
                $part .= "{$k}='{$v}' ";
            }
        } else {
            $part = is_string($attrs) ? $attrs : null;
        }
        return <<<S
<div class="input-group-append {$classMain}" {$part}>
    <span class="input-group-text {$classText}">
        <i class="{$icon} {$classIcon}"></i>
    </span>
</div>
S;
    }


    /*
     * Возвращает скрытый input для формы.
     * $name - Передать имя input.
     * $value - Значение.
     * $attrs - передайте атрибуты строкой или в массиве ['id' => 'test', 'data-id' => 'dataTest', 'novalidate' => ''], необязательный параметр.
     */
    public static function hidden($name, $value, $attrs = null)
    {
        $part = '';
        if ($attrs && is_array($attrs)) {
            foreach ($attrs as $k => $v) {
                $part .= "{$k}='{$v}' ";
            }
        } else {
            $part = is_string($attrs) ? $attrs : null;
        }
        return "<input type=\"hidden\" name=\"{$name}\" value='{$value}' {$part}>";
    }


    /*
     * Возвращает checkbox для формы.
     * Использует плагин Bootstrap switch: https://github.com/Bttstrp/bootstrap-switch
     *
     * $name - передать название, перевод будет взять из /resources/lang/en/s.php.
     * $value - значение элемента, необязательный параметр.
     * $required - если обязательный, то передайте true, необязательный параметр.
     * $checked - Если checkbox должен быть нажат, то передайте true, необязательный параметр.
     * $class - Передайте свой класс, необязательный параметр.
     * $title - Можно передать свой заголовок, например с ссылкой, необязательный параметр.
     *
     * $onText - передать текс, по-умолчанию on, необязательный параметр.
     * $offText - передать текс, по-умолчанию off, необязательный параметр.
     * $onColor - передать класс цвета Bootstrap, по-умолчанию primary, необязательный параметр.
     * $offColor - передать класс цвета Bootstrap, по-умолчанию default, необязательный параметр.
     * $attrs - передайте атрибуты строкой или в массиве ['id' => 'test', 'data-id' => 'dataTest', 'novalidate' => ''], необязательный параметр.
     * $idForm - если используется форма несколько раз на странице, то передайте id формы, чтобы у id у чекбоксова были оригинальные id.
     *
     * data-size="mini"
     * data-handle-width="100"
     */
    public static function checkbox($name, $value = null, $required = null, $checked = null, $class = null, $title = null, $onText = 'on', $offText = 'off', $onColor = 'primary', $offColor = 'default', $attrs = null, $idForm = null)
    {
        $_title = Func::__($name, 'a');
        $title = $title ?: $_title;
        $id = $idForm ? "{$idForm}_{$name}" : $name;
        $value = $value ? "value=\"{$value}\"" : null;

        $checked = $checked || $value || old($name) ? 'checked' : null;
        $required = $required ? 'required' : null;
        $onText = Func::__($onText, 'a');
        $offText = Func::__($offText, 'a');

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
    <label for="{$id}" class="bootstrap-switch-label mt-2">{$title}</label>
    <input type="checkbox" name="{$name}" id="{$id}" $value $checked $required data-toggle="switch" data-on-color="{$onColor}" data-off-color="{$offColor}" data-on-text="{$onText}" data-off-text="{$offText}" {$part}>
</div>
S;
    }
}
