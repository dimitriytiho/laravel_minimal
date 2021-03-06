
// Скрипты для Форм

/*
 * При клике на ссылку или кнопку с классом one_click
 * Отключается кнопка (добавляется класс disabled).
 */
$(document).on('click', '.one_click', function() {
    $(this)
        .attr('disabled', true)
        .addClass('disabled')
})


/*
 * При клике на ссылку или кнопку с классом spinner_click
 * Отключается кнопка (добавляется класс disabled) и включается спиннер.
 */
$(document).on('click', '.spinner_click', function() {
    $(this)
        .attr('disabled', true)
        .addClass('disabled')
        .prepend(spinnerBtn)
})


/*
 * При отправке формы с классом spinner_submit
 * Отключается кнопка и включается спиннер в кнопке отправки.
 * Внимание, спиннер будет крутиться до перезагрузки страницы.
 */
$(document).on('submit', '.spinner_submit', function() {
    $(this)
        .find('[type=submit]')
        .attr('disabled', true)
        .addClass('disabled')
        .prepend(spinnerBtn)
})


// При изменении формы отправить её
$(document).on('change', '.change_submit', function() {
    $(this).submit()
})
