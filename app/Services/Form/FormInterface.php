<?php


namespace App\Services\Form;


interface FormInterface
{
    /**
     *
     * Разметка для input
     * @return string
     *
     * Переводы из языкового файла s.php.
     * Name, id, placeholder, если нужно изменить, то передайте в массив $attrs.
     *
     * @param string $name - название.
     * @param array $attrs - параметры передать в массиве, например ['data-url' => '/test'], по-умолчанию пустой массив, необязательный параметр.
     * @param string|null $value - значение для input, по-умолчанию null, необязательный параметр.
     * @param bool|null $required - атрибут required (обязательно для заполнения) по-умолчанию true, необязательный параметр.
     * @param bool|null $label - передать true если он нужен или передать фразу для перевода, или же передать null, тогда label не будет показан, по-умолчанию null, необязательный параметр.
     * @param string|null $class - класс для группы, если нужен класс для input, то передайте в массив $attrs, по-умолчанию null, необязательный параметр.
     */
    public static function input($name, array $attrs = [], $value = null, $required = true, $label = null, $class = null);


    /**
     *
     * Разметка для textarea
     * @return string
     *
     * Переводы из языкового файла s.php.
     * Name, id, placeholder, если нужно изменить, то передайте в массив $attrs.
     *
     * @param string $name - название.
     * @param array $attrs - параметры передать в массиве, например ['data-url' => '/test'], по-умолчанию пустой массив, необязательный параметр.
     * @param string|null $value - значение для input, по-умолчанию null, необязательный параметр.
     * @param bool|null $required - атрибут required (обязательно для заполнения) по-умолчанию false, необязательный параметр.
     * @param bool|null $label - передать true если он нужен или передать фразу для перевода, или же передать null, тогда label не будет показан, по-умолчанию null, необязательный параметр.
     * @param string|null $class - класс для группы, если нужен класс для input, то передайте в массив $attrs, по-умолчанию null, необязательный параметр.
     */
    public static function textarea($name, array $attrs = [], $value = null, $required = false, $label = null, $class = null);


    /**
     *
     * Разметка для select
     * @return string
     *
     * Переводы из языкового файла s.php.
     * Name, id, placeholder, если нужно изменить, то передайте в массив $attrs.
     *
     * @param string $name - название.
     * @param string|array|object $options - передать options: строкой, массивом или объектом (если $value будет равно одному из значений $options, то этот option будет selected).
     * @param array $attrs - параметры передать в массиве, например ['data-url' => '/test'], по-умолчанию пустой массив, необязательный параметр.
     * @param string|null $value - значение для input, по-умолчанию null, необязательный параметр.
     * @param bool|null $required - атрибут required (обязательно для заполнения) по-умолчанию false, необязательный параметр.
     * @param bool|null $label - передать true если он нужен или передать фразу для перевода, или же передать null, тогда label не будет показан, по-умолчанию null, необязательный параметр.
     * @param string|null $class - класс для группы, если нужен класс для input, то передайте в массив $attrs, по-умолчанию null, необязательный параметр.
     * @param string|null $disabled - передать значения, для которого установить атрибут disabled.
     * @param bool $optionValueFromId - передайте true, если передаёте массив или объект $options, в котором ключи это id для вывода как значения для option, необязательный параметр.
     */
    public static function select($name, $options, array $attrs = [], $value = null, $required = false, $label = null, $class = null, $disabled = null, $optionValueFromId = false);


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
    public static function checkbox($name, array $attrs = [], $checked = false, $value = null, $required = true, $label = null, $class = null);


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
    public static function toggle($name, array $attrs = [], $checked = false, $value = null, $required = true, $label = null, $class = null);


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
    public static function radio($name, array $attrs = [], $checked = false, $value = null, $required = false, $label = null, $class = null);
}
