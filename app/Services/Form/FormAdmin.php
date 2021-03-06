<?php


namespace App\Services\Form;

use App\Support\Func;

class FormAdmin implements FormInterface
{
    // Название файла для переводов из resources/lang/en, без .php
    protected static $langFile = 'a';


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
     * @param bool|null $label - передать фразу для перевода, по-умолчанию label показывается, если надо не показывать передать в $attrs ['label' => 'false'] false строкой, необязательный параметр.
     * @param string|null $class - класс для группы, если нужен класс для input, то передайте в массив $attrs, по-умолчанию null, необязательный параметр.
     */
    public static function input($name, array $attrs = [], $value = null, $required = true, $label = true, $class = null)
    {
        if ($name) {

            // Id
            $id = self::getElementFromAttrs('id', $attrs) ?: $name;

            // Обновим $attrs удалив из него лишние элементы
            $attrs = self::updateAttr($attrs, ['id']);

            // Placeholder
            $placeholder = self::getPlaceholder($name, $label);

            // Label
            $label = self::getLabel($id, $required, $placeholder, !empty($attrs['label']) && $attrs['label'] === 'false' ? 'sr-only' : null);

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
     * @param bool|null $label - передать фразу для перевода, по-умолчанию label показывается, если надо не показывать передать в $attrs ['label' => 'false'] false строкой, необязательный параметр.
     * @param string|null $class - класс для группы, если нужен класс для input, то передайте в массив $attrs, по-умолчанию null, необязательный параметр.
     */
    public static function textarea($name, array $attrs = [], $value = null, $required = false, $label = true, $class = null)
    {
        if ($name) {

            // Id
            $id = self::getElementFromAttrs('id', $attrs) ?: $name;

            // Обновим $attrs удалив из него лишние элементы
            $attrs = self::updateAttr($attrs, ['id']);

            // Placeholder
            $placeholder = self::getPlaceholder($name, $label);

            // Label
            $label = self::getLabel($id, $required, $placeholder, !empty($attrs['label']) && $attrs['label'] === 'false' ? 'sr-only' : null);

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
     * Дополнительно:
     * 'label' => 'false' - чтобы не показывать лэйбл,
     * 'values' => 'path' - передаём название ключа в объекте $value, чтобы установить атрибут selected,
     * 'value_null' => 'path' - передаём название ключа в объекте значений, чтобы установить удалить значение.
     * 'lang' => 'false' - чтобы не переводить options,
     * 'name-id' => 'true' - чтобы добавить к названию id.
     *
     * @param bool|null $required - атрибут required (обязательно для заполнения) по-умолчанию false, необязательный параметр.
     * @param bool|null $label - передать фразу для перевода, по-умолчанию label показывается, если надо не показывать передать в $attrs ['label' => 'false'] false строкой, необязательный параметр.
     * @param string|null $class - класс для группы, если нужен класс для input, то передайте в массив $attrs, по-умолчанию null, необязательный параметр.
     * @param string|null $disabled - передать значения, для которого установить атрибут disabled.
     * @param bool $optionValueFromId - передайте true, если передаёте массив или объект $options, в котором ключи это id для вывода как значения для option, необязательный параметр.
     */
    public static function select($name, $options, array $attrs = [], $value = null, $required = false, $label = true, $class = null, $disabled = null, $optionValueFromId = false)
    {
        if ($name) {

            // Id
            $id = self::getElementFromAttrs('id', $attrs) ?: $name;

            // Обновим $attrs удалив из него лишние элементы
            $attrs = self::updateAttr($attrs, ['id']);

            // Placeholder
            $placeholder = self::getPlaceholder($name, $label);

            // Label
            $label = self::getLabel($id, $required, $placeholder, !empty($attrs['label']) && $attrs['label'] === 'false' ? 'sr-only' : null);

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
                            $option = Func::__($option, 'a');
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

            // Обновим $attrs удалив из него лишние элементы
            $attrs = self::updateAttr($attrs, ['id']);

            // Placeholder
            $placeholder = self::getPlaceholder($name, $label);

            // input
            $input = html()->checkbox($name, $checked, $value)->id($id)->class('custom-control-input')->attributes($attrs);

            // Required
            $input = self::getRequired($input, $required);

            // Label
            $label = self::getLabel($id, $required, $placeholder, 'custom-control-label');

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
     * Переводы из языкового файла a.php.
     * Name, id, placeholder, если нужно изменить, то передайте в массив $attrs.
     *
     * @param string $name - название.
     * @param array $attrs - Параметры передать в массиве, например ['data-url' => '/test'], по-умолчанию пустой массив, необязательный параметр.
     * Дополнительные атрибуты (эти атрибуты по-умолчанию):
     * data-on-color="primary" - цвет, когда включен,
     * data-off-color="default" - цвет, когда выключен,
     * data-on-text="on" - текст, когда выключен,
     * data-off-text="off" - текст, когда выключен,
     *
     * @param bool $checked - если checkbox должен быть нажат, то передайте true, необязательный параметр.
     * @param string|null $value - значение для input, по-умолчанию null, необязательный параметр.
     * @param bool|null $required - атрибут required (обязательно для заполнения) по-умолчанию true, необязательный параметр.
     * @param bool|null $label - передать true если он нужен или передать фразу для перевода, или же передать null, тогда label не будет показан, по-умолчанию null, необязательный параметр.
     * @param string|null $class - класс для группы, если нужен класс для input, то передайте в массив $attrs, по-умолчанию null, необязательный параметр.
     */
    public static function toggle($name, array $attrs = [], $checked = false, $value = null, $required = false, $label = null, $class = null)
    {
        if ($name) {

            // Id
            $id = self::getElementFromAttrs('id', $attrs) ?: $name;

            // Обновим $attrs удалив из него лишние элементы
            $attrs = self::updateAttr($attrs, ['id']);

            // Placeholder
            $placeholder = self::getPlaceholder($name, $label);

            // Дата атрибуты по-умолчанию
            if (!isset($attrs['data-on-color'])) {
                $attrs['data-on-color'] = 'primary';
            }
            if (!isset($attrs['data-off-color'])) {
                $attrs['data-off-color'] = 'default';
            }
            if (isset($attrs['data-on-text'])) {
                $attrs['data-on-text'] = Func::__($attrs['data-on-text'], self::$langFile);
            } else {
                $attrs['data-on-text'] = Func::__('on', self::$langFile);
            }
            if (isset($attrs['data-off-text'])) {
                $attrs['data-off-text'] = Func::__($attrs['data-off-text'], self::$langFile);
            } else {
                $attrs['data-off-text'] = Func::__('off', self::$langFile);
            }

            // input
            $input = html()->checkbox($name, $checked, $value)->id($id)->class('custom-control-input')->attributes($attrs)->attribute('data-toggle', 'switch');

            // Required
            $input = self::getRequired($input, $required);

            // Label
            $label = self::getLabel($id, $required, $placeholder, 'bootstrap-switch-label mt-2');

            // Wrap div
            return self::getWrap($label . $input, 'mb-3 ' . $class);
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
            $input = html()->radio($name, $checked, $value)->id($id)->class('custom-control-input')->attributes($attrs);

            // Required
            $input = self::getRequired($input, $required);

            // Label
            $label = self::getLabel($id, $required, $placeholder, 'custom-control-label');

            // Wrap div
            return self::getWrap($input . $label, 'mb-3 custom-control custom-radio ' . $class);
        }
        return null;
    }


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
     * @param string|null $append - html код, который нужно вывести после input, по-умолчанию null, необязательный параметр.
     */
    public static function inputGroup($name, array $attrs = [], $value = null, $required = true, $label = true, $class = null, $append = null)
    {
        if ($name) {

            // Id
            $id = self::getElementFromAttrs('id', $attrs) ?: $name;

            // Обновим $attrs удалив из него лишние элементы
            $attrs = self::updateAttr($attrs, ['id']);

            // Placeholder
            $placeholder = self::getPlaceholder($name, $label);

            // Label
            $label = self::getLabel($id, $required, $placeholder, $label ? null : 'sr-only');

            // input
            $input = html()->text($name, $value)->id($id)->class('form-control')->attributes($attrs)->placeholder($placeholder . ($required ? '*' : null));

            // Required
            $input = self::getRequired($input, $required);

            // Input-group
            $div = self::getWrap($input . $append, 'input-group');

            // Wrap div
            return self::getWrap($label . $div, $class);
        }
        return null;
    }



    /**
     * Разметка для данных Json, чтобы формировать массив из input.
     * @return string
     *
     * @param string $dataId - название колонки из БД.
     * @param object|null $values - данные элемента, объект модели, необязательный параметр.
     */
    public static function jsonData($dataId, $values = null)
    {
        $view = 'admin.inc.json_data';
        if ($dataId && view()->exists($view)) {
            return view($view, compact('dataId', 'values'))->render();
        }
        return null;
    }
}
