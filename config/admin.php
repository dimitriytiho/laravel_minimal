<?php

return [

    // Кол-во элементов на странице для пагинации
    'pagination' => [10, 25, 50, 100],
    'pagination_default' => 25, // По-умолчанию кол-во пагинации

    // Список используемых локалей
    'locales' => [
        'ru',
        'en',
    ],

    // Выбор редактора для контента
    'editor' => 'codemirror', // codemirror, ckeditor,

    // Формат даты
    'date_format' => 'd.m.Y H:i', // d.m.Y H:i dd.MM.y HH:mm

    // Типы настроек
    'setting_type' => [
        'string', // Первое значение по-умолчанию
        'checkbox', // Второй должен быть checkbox
    ],


    // Картинки Webp качество до 100
    'webpQuality' => 80,

    'images_ext' => [

        //cut, width, height, text
        [false, '800', '800', 'one_side'], // Первый по-умолчанию
        [false, '1280', '1280', 'one_side'],
        [false, '1920', '1920', 'one_side'],
        [false, '500', '500', 'one_side'],
        [false, '100', '100', 'square'],
        ['not_cut'],
    ],

    // Разрешённые для загрузки картинки
    'acceptedImagesExt' => [
        'jpg',
        'jpeg',
        'png',
        'gif',
    ],
];
