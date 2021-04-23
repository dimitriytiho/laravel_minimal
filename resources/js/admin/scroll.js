
document.addEventListener('DOMContentLoaded', function() {

    var btn_up = $('#btn_up')


    // При клике поднимаем к верху страницы
    btn_up.click(function () {
        $('html, body').animate({scrollTop: 0}, '400')
    })


    // Код со скролом
    $(window).on('scroll', function () {
        var scrollTop = scrollTop = $(window).scrollTop()


        if (scrollTop < 200) {

            // Кнопка вверх
            btn_up.removeClass('scale-in').addClass('scale-out')

        } else {

            // Кнопка вверх
            btn_up.addClass('scale-in').removeClass('scale-out')

        }
    })

}, false)
