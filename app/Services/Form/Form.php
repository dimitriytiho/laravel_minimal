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
     * @param array $attrs - Параметры передать в массиве, например ['data-url' => '/test', 'id' => 'test', 'inputClass' => 'test'], по-умолчанию пустой массив, необязательный параметр.
     * @param string|null $value - значение для input, по-умолчанию null, необязательный параметр.
     * @param bool|null $required - атрибут required (обязательно для заполнения) по-умолчанию true, необязательный параметр.
     * @param bool|null $label - передать фразу для перевода, по-умолчанию label не показывается, если показывать передать в $attrs ['label' => 'true'], необязательный параметр.
     * @param string|null $class - класс для группы, если нужен класс для input, то передайте в массив $attrs, по-умолчанию null, необязательный параметр.
     */
    public static function input($name, array $attrs = [], $value = null, $required = true, $label = null, $class = null)
    {
        if ($name) {

            // Id
            $id = self::getElementFromAttrs('id', $attrs) ?: $name;

            // Класс для input
            $inputClass = self::getElementFromAttrs('inputClass', $attrs);

            // Обновим $attrs удалив из него лишние элементы
            $attrs = self::updateAttr($attrs, ['id', 'inputClass']);

            // Placeholder
            $placeholder = self::getPlaceholder($name, $label);

            // Label
            $label = self::getLabel($id, $required, $placeholder, empty($attrs['label']) ? 'sr-visually-hidden' : 'form-label');

            // input
            $input = html()->text($name, $value)->id($id)->class('form-control ' . $inputClass)->attributes($attrs)->placeholder($placeholder . ($required ? '*' : null));

            // Required
            $input = self::getRequired($input, $required);

            // Wrap div
            return self::getWrap($label . $input, 'form-group mb-3 ' . $class);
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
     * @param array $attrs - Параметры передать в массиве, например ['data-url' => '/test', 'id' => 'test', 'inputClass' => 'test'], по-умолчанию пустой массив, необязательный параметр.
     * @param string|null $value - значение для input, по-умолчанию null, необязательный параметр.
     * @param bool|null $required - атрибут required (обязательно для заполнения) по-умолчанию false, необязательный параметр.
     * @param bool|null $label - передать фразу для перевода, по-умолчанию label не показывается, если показывать передать в $attrs ['label' => 'true'], необязательный параметр.
     * @param string|null $class - класс для группы, если нужен класс для input, то передайте в массив $attrs, по-умолчанию null, необязательный параметр.
     */
    public static function textarea($name, array $attrs = [], $value = null, $required = false, $label = null, $class = null)
    {
        if ($name) {

            // Id
            $id = self::getElementFromAttrs('id', $attrs) ?: $name;

            // Класс для input
            $inputClass = self::getElementFromAttrs('inputClass', $attrs);

            // Обновим $attrs удалив из него лишние элементы
            $attrs = self::updateAttr($attrs, ['id', 'inputClass']);

            // Placeholder
            $placeholder = self::getPlaceholder($name, $label);

            // Label
            $label = self::getLabel($id, $required, $placeholder, empty($attrs['label']) ? 'sr-visually-hidden' : 'form-label');

            // Textarea
            $input = html()->textarea($name, $value)->id($id)->class('form-control ' . $inputClass)->attributes($attrs)->placeholder($placeholder . ($required ? '*' : null));

            // Required
            $input = self::getRequired($input, $required);

            // Wrap div
            return self::getWrap($label . $input, 'form-group mb-3 ' . $class);
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
     * @param array $attrs - Параметры передать в массиве, например ['data-url' => '/test', 'id' => 'test', 'inputClass' => 'test'], по-умолчанию пустой массив, необязательный параметр.
     * @param string|null $value - значение для input, по-умолчанию null, необязательный параметр.
     * Дополнительно:
     * 'label' => 'true' - чтобы показывать лэйбл,
     * 'lang' => 'false' - чтобы не переводить options,
     * 'values' => 'path' - передаём название ключа в объекте $value, чтобы установить атрибут selected,
     * 'value_null' => 'path' - передаём название ключа в объекте значений, чтобы установить удалить значение,
     * 'name-id' => 'true' - чтобы добавить к названию id.
     *
     * @param bool|null $required - атрибут required (обязательно для заполнения) по-умолчанию false, необязательный параметр.
     * @param bool|null $label - передать фразу для перевода, по-умолчанию label не показывается, если показывать передать в $attrs ['label' => 'true'], необязательный параметр.
     * @param string|null $class - класс для группы, если нужен класс для input, то передайте в массив $attrs, по-умолчанию null, необязательный параметр.
     * @param string|null $disabled - передать значения, для которого установить атрибут disabled.
     * @param bool $optionValueFromId - передайте true, если передаёте массив или объект $options, в котором ключи это id для вывода как значения для option, необязательный параметр.
     */
    public static function select($name, $options, array $attrs = [], $value = null, $required = false, $label = null, $class = null, $disabled = null, $optionValueFromId = false)
    {
        if ($name) {

            // Id
            $id = self::getElementFromAttrs('id', $attrs) ?: $name;

            // Класс для input
            $inputClass = self::getElementFromAttrs('inputClass', $attrs);

            // Обновим $attrs удалив из него лишние элементы
            $attrs = self::updateAttr($attrs, ['id', 'inputClass']);

            // Placeholder
            $placeholder = self::getPlaceholder($name, $label);

            // Label
            $label = self::getLabel($id, $required, $placeholder, empty($attrs['label']) ? 'sr-visually-hidden' : 'form-label');

            // Options
            $html = '';
            if ($options) {
                if (is_object($options) || is_array($options)) {
                    foreach ($options as $key => $option) {
                        // Value
                        $val = $optionValueFromId ? $key : $option;
                        if (isset($attrs['value_null']) && $val == $attrs['value_null']) {
                            $val = '';
                        }
                        // Start option
                        $html .= "<option value='{$val}'";
                        // Selected
                        if (is_object($value)) {

                            // Если в атрибутах передаём название ключа в объекте $value
                            $selected = empty($attrs['values']) ? $value->contains($val) : $value->contains($attrs['values'], $val);
                            $html .= $selected ? ' selected' : null;

                        } elseif (is_array($value)) {

                            $html .= in_array($val, $value) ? ' selected' : null;

                        } else {
                            $html .= $value == $val ? ' selected' : null;
                        }
                        // Disabled
                        $html .= !is_null($disabled) && $disabled == $val ? ' disabled' : null;
                        // Translation
                        if (empty($attrs['lang'])) {
                            $option = Func::__($option);
                        }
                        // К название добавить id
                        if (!empty($attrs['name-id'])) {
                            $option .= ' - ' . $key;
                        }
                        // End option
                        $html .= ">{$option}</option>";
                    }
                } elseif (is_string($options)) {
                    $html = $options;
                }
            }

            // Select
            $input = html()->select($name, [], $value)->id($id)->class('form-select ' . $inputClass)->attributes($attrs)->html($html);

            // Required
            $input = self::getRequired($input, $required);

            // Wrap div
            return self::getWrap($label . $input, 'form-group mb-3 ' . $class);
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
    public static function checkbox($name, array $attrs = [], $checked = false, $value = null, $required = true, $label = null, $class = null, $classInput = null)
    {
        if ($name) {

            // Id
            $id = self::getElementFromAttrs('id', $attrs) ?: $name;

            // Обновим $attrs удалив из него лишние элементы
            $attrs = self::updateAttr($attrs, ['id']);

            // Placeholder
            $placeholder = self::getPlaceholder($name, $label);

            // input
            $input = html()->checkbox($name, $checked, $value)->id($id)->class('form-check-input')->attributes($attrs);

            // Required
            $input = self::getRequired($input, $required);

            // Label
            $label = self::getLabel($id, $required, $placeholder, 'form-check-label');

            // Wrap div
            return self::getWrap($input . $label, 'mb-3 form-check ' . $class);
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

            // Обновим $attrs удалив из него лишние элементы
            $attrs = self::updateAttr($attrs, ['id']);

            // Placeholder
            $placeholder = self::getPlaceholder($name, $label);

            // input
            $input = html()->radio($name, $checked, $value)->id($id)->class('form-check-input')->attributes($attrs);

            // Required
            $input = self::getRequired($input, $required);

            // Label
            $label = self::getLabel($id, $required, $placeholder, 'form-check-label');

            // Wrap div
            return self::getWrap($input . $label, 'mb-2 form-check ' . $class);
        }
        return null;
    }
}
