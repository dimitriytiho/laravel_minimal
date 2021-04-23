
document.addEventListener('DOMContentLoaded', function() {

    // При клике на кнопку Вверх, движение вверх
    $('#btn_up').click(function() {
        $('html, body').animate({scrollTop: 0}, '400')
    })


    /*
     * Плавная прокрутка страницы до якоря.
     * Добавить класс anchor и в href="#name_anchor" написать название якоря.
     */
    $(document).on('click', '.anchor', function(e) {
        e.preventDefault()
        var anchor = $(this).attr('href'),
            margin = 70,
            offset = $(anchor).offset()

        if (offset) {
            $('html, body').stop().animate({
                scrollTop: offset.top - margin
            }, 400)
        }
    })


    // Добавляем класс .active к выбранной радиокнопке, меняем c id = name от радиокнопки_title: заголовок и add
    /*$('.radio_btn').change(function () {
        var self = $(this),
            name = self.find('input').attr('name'),
            title = self.find('span').text(),
            add = self.data('add')

        self.parent().find('.radio_btn').removeClass('active')
        self.addClass('active')
        $('#' + name + '_title').text(title)
        $('#' + name + '_add').text(add)
    })*/

}, false)
