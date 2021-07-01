<?php


namespace App\Services\Form;

use App\Support\Func;

class Form implements FormInterface
{
    // Название файла для переводов из resources/lang/en, без .php
    protected static $langFile = 's';


    use FormTrait;


    /**
     *
     * Разметка для input
     * @return string
     *
     * Переводы из языкового файла a.php.
     * Name, id, placeholder, если нужно изменить, то передайте в массив $attrs.
     *
     * @param string $name - название.
     * @param array $attrs - Параметры передать в массиве, например ['data-url' => '/test'], по-умолчанию пустой массив, необязательный параметр.
     * @param string|null $value - значение для input, по-умолчанию null, необязательный параметр.
     * @param bool|null $required - атрибут required (обязательно для заполнения) по-умолчанию true, необязательный параметр.
     * @param bool|null $label - передать true если он нужен или передать фразу для перевода, или же передать null, тогда label не будет показан, по-умолчанию null, необязательный параметр.
     * @param string|null $class - класс для группы, если нужен класс для input, то передайте в массив $attrs, по-умолчанию null, необязательный параметр.
     */
    public static function input($name, array $attrs = [], $value = null, $required = true, $label = null, $class = null)
    {
        if ($name) {

            // Id
            $id = self::getElementFromAttrs('id', $attrs) ?: $name;

            // Обновим $attrs удалив из него лишнии элемены
            $attrs = self::updateAttr($attrs, ['id']);

            // Placeholder
            $placeholder = self::getPlaceholder($name, $label);

            // Label
            $label = self::getLabel($id, $required, $label, $placeholder, $label ? null : 'sr-only');

            // input
            $input = html()->text($name, $value)->id($id)->class('form-control')->attributes($attrs)->placeholder($placeholder . ($required ? '*' : null));

            // Required
            $input = self::getRequired($input, $required);

            // Wrap div
            return self::getWrap($label . $input, 'form-group ' . $class);
        }
        return null;
    }


    /**
     *
     * Разметка для textarea
     * @return string
     *
     * Переводы из языкового файла a.php.
     * Name, id, placeholder, если нужно изменить, то передайте в массив $attrs.
     *
     * @param string $name - название.
     * @param array $attrs - Параметры передать в массиве, например ['data-url' => '/test'], по-умолчанию пустой массив, необязательный параметр.
     * @param string|null $value - значение для input, по-умолчанию null, необязательный параметр.
     * @param bool|null $required - атрибут required (обязательно для заполнения) по-умолчанию false, необязательный параметр.
     * @param bool|null $label - передать true если он нужен или передать фразу для перевода, или же передать null, тогда label не будет показан, по-умолчанию null, необязательный параметр.
     * @param string|null $class - класс для группы, если нужен класс для input, то передайте в массив $attrs, по-умолчанию null, необязательный параметр.
     */
    public static function textarea($name, array $attrs = [], $value = null, $required = false, $label = null, $class = null)
    {
        if ($name) {

            // Id
            $id = self::getElementFromAttrs('id', $attrs) ?: $name;

            // Обновим $attrs удалив из него лишнии элемены
            $attrs = self::updateAttr($attrs, ['id']);

            // Placeholder
            $placeholder = self::getPlaceholder($name, $label);

            // Label
            $label = self::getLabel($id, $required, $label, $placeholder, $label ? null : 'sr-only');

            // Textarea
            $input = html()->textarea($name, $value)->id($id)->class('form-control')->attributes($attrs)->placeholder($placeholder . ($required ? '*' : null));

            // Required
            $input = self::getRequired($input, $required);

            // Wrap div
            return self::getWrap($label . $input, 'form-group ' . $class);
        }
        return null;
    }


    /**
     *
     * Разметка для select
     * @return string
     *
     * Переводы из языкового файла a.php.
     * Name, id, placeholder, если нужно изменить, то передайте в массив $attrs.
     *
     * @param string $name - название.
     * @param string|array|object $options - передать options: строкой, массивом или объектом (если $value будет равно одному из значений $options, то этот option будет selected).
     * @param array $attrs - Параметры передать в массиве, например ['data-url' => '/test'], по-умолчанию пустой массив, необязательный параметр.
     * @param string|null $value - значение для input, по-умолчанию null, необязательный параметр.
     * @param bool|null $required - атрибут required (обязательно для заполнения) по-умолчанию false, необязательный параметр.
     * @param bool|null $label - передать true если он нужен или передать фразу для перевода, или же передать null, тогда label не будет показан, по-умолчанию null, необязательный параметр.
     * @param string|null $class - класс для группы, если нужен класс для input, то передайте в массив $attrs, по-умолчанию null, необязательный параметр.
     * @param string|null $disabled - передать значения, для которого установить атрибут disabled.
     * @param bool $optionValueFromId - передайте true, если передаёте массив или объект $options, в котором ключи это id для вывода как значения для option, необязательный параметр.
     */
    public static function select($name, $options, array $attrs = [], $value = null, $required = false, $label = null, $class = null, $disabled = null, $optionValueFromId = false)
    {
        if ($name) {

            // Id
            $id = self::getElementFromAttrs('id', $attrs) ?: $name;

            // Обновим $attrs удалив из него лишнии элемены
            $attrs = self::updateAttr($attrs, ['id']);

            // Placeholder
            $placeholder = self::getPlaceholder($name, $label);

            // Label
            $label = self::getLabel($id, $required, $label, $placeholder, $label ? null : 'sr-only');

            // Options
            $html = '';
            if ($options) {
                if (is_object($options) || is_array($options)) {
                    foreach ($options as $key => $option) {
                        // Value
                        $val = $optionValueFromId ? $key : $option;
                        // Start option
                        $html .= "<option value='{$val}'";
                        // Selected
                        if (is_object($value)) {
                            $html .= isset($value[$val]) || $value->contains($val) || isset($value[$option]) || $value->contains($option) ? ' selected' : null;
                        } elseif (is_array($value)) {
                            $html .= isset($value[$val]) || in_array($val, $value) || isset($value[$option]) || in_array($val, $option) ? ' selected' : null;
                        } else {
                            $html .= $value == $val ? ' selected' : null;
                        }
                        // Disabled
                        $html .= $disabled == $val ? ' disabled' : null;
                        // Translation
                        $option = Func::__($option, 'a');
                        // End option
                        $html .= ">{$option}</option>";
                    }
                } elseif (is_string($options)) {
                    $html = $options;
                }
            }

            // Select
            $input = html()->select($name, [], $value)->id($id)->class('form-control')->attributes($attrs)->html($html);

            // Required
            $input = self::getRequired($input, $required);

            // Wrap div
            return self::getWrap($label . $input, 'form-group ' . $class);
        }
        return null;
    }


    /**
     *
     * Разметка для checkbox
     * @return string
     *
     * Переводы из языкового файла s.php.
     * Name, id, placeholder, если нужно изменить, то передайте в массив $attrs.
     *
     * @param string $name - название.
     * @param array $attrs - параметры передать в массиве, например ['data-url' => '/test'], по-умолчанию пустой массив, необязательный параметр.
     * @param bool $checked - если checkbox должен быть нажат, то передайте true, необязательный параметр.
     * @param string|null $value - значение для input, по-умолчанию null, необязательный параметр.
     * @param bool|null $required - атрибут required (обязательно для заполнения) по-умолчанию true, необязательный параметр.
     * @param bool|null $label - передать true если он нужен или передать фразу для перевода, или же передать null, тогда label не будет показан, по-умолчанию null, необязательный параметр.
     * @param string|null $class - класс для группы, если нужен класс для input, то передайте в массив $attrs, по-умолчанию null, необязательный параметр.
     */
    public static function checkbox($name, array $attrs = [], $checked = false, $value = null, $required = true, $label = null, $class = null)
    {
        if ($name) {

            // Id
            $id = self::getElementFromAttrs('id', $attrs) ?: $name;

            // Обновим $attrs удалив из него лишнии элемены
            $attrs = self::updateAttr($attrs, ['id']);

            // Placeholder
            $placeholder = self::getPlaceholder($name, $label);

            // input
            $input = html()->checkbox($name, $checked, $value)->id($id)->class('custom-control-input')->attributes($attrs);

            // Required
            $input = self::getRequired($input, $required);

            // Label
            $label = self::getLabel($id, $required, $label, $placeholder, 'custom-control-label');

            // Wrap div
            return self::getWrap($input . $label, 'mb-3 custom-control custom-checkbox ' . $class);
        }
        return null;
    }


    /**
     *
     * Разметка для toggle
     * @return string
     *
     * Переводы из языкового файла s.php.
     * Name, id, placeholder, если нужно изменить, то передайте в массив $attrs.
     *
     * @param string $name - название.
     * @param array $attrs - параметры передать в массиве, например ['data-url' => '/test'], по-умолчанию пустой массив, необязательный параметр.
     * @param bool $checked - если checkbox должен быть нажат, то передайте true, необязательный параметр.
     * @param string|null $value - значение для input, по-умолчанию null, необязательный параметр.
     * @param bool|null $required - атрибут required (обязательно для заполнения) по-умолчанию true, необязательный параметр.
     * @param bool|null $label - передать true если он нужен или передать фразу для перевода, или же передать null, тогда label не будет показан, по-умолчанию null, необязательный параметр.
     * @param string|null $class - класс для группы, если нужен класс для input, то передайте в массив $attrs, по-умолчанию null, необязательный параметр.
     */
    public static function toggle($name, array $attrs = [], $checked = false, $value = null, $required = true, $label = null, $class = null)
    {
        if ($name) {

            // Id
            $id = self::getElementFromAttrs('id', $attrs) ?: $name;

            // Обновим $attrs удалив из него лишнии элемены
            $attrs = self::updateAttr($attrs, ['id']);

            // Placeholder
            $placeholder = self::getPlaceholder($name, $label);

            // input
            $input = html()->checkbox($name, $checked, $value)->id($id)->class('custom-control-input')->attributes($attrs);

            // Required
            $input = self::getRequired($input, $required);

            // Label
            $label = self::getLabel($id, $required, $label, $placeholder, 'custom-control-label');

            // Wrap div
            return self::getWrap($input . $label, 'mb-3 custom-control custom-switch ' . $class);
        }
        return null;
    }


    /**
     *
     * Разметка для radio
     * @return string
     *
     * Переводы из языкового файла s.php.
     * Name, id, placeholder, если нужно изменить, то передайте в массив $attrs.
     *
     * @param string $name - название.
     * @param array $attrs - параметры передать в массиве, например ['data-url' => '/test'], по-умолчанию пустой массив, необязательный параметр.
     * @param bool $checked - если checkbox должен быть нажат, то передайте true, необязательный параметр.
     * @param string|null $value - значение для input, по-умолчанию null, необязательный параметр.
     * @param bool|null $required - атрибут required (обязательно для заполнения) по-умолчанию true, необязательный параметр.
     * @param bool|null $label - передать true если он нужен или передать фразу для перевода, или же передать null, тогда label не будет показан, по-умолчанию null, необязательный параметр.
     * @param string|null $class - класс для группы, если нужен класс для input, то передайте в массив $attrs, по-умолчанию null, необязательный параметр.
     */
    public static function radio($name, array $attrs = [], $checked = false, $value = null, $required = true, $label = null, $class = null)
    {
        if ($name) {

            // Id
            $id = self::getElementFromAttrs('id', $attrs) ?: $name;

            // Обновим $attrs удалив из него лишнии элемены
            $attrs = self::updateAttr($attrs, ['id']);

            // Placeholder
            $placeholder = self::getPlaceholder($name, $label);

            // input
            $input = html()->radio($name, $checked, $value)->id($id)->class('custom-control-input')->attributes($attrs);

            // Required
            $input = self::getRequired($input, $required);

            // Label
            $label = self::getLabel($id, $required, $label, $placeholder, 'custom-control-label');

            // Wrap div
            return self::getWrap($input . $label, 'mb-2 custom-control custom-radio ' . $class);
        }
        return null;
    }
}
