
var btnUp = $('#btn_up')


// При клике поднимаем к верху страницы
btnUp.click(function () {
    $('html, body').animate({scrollTop: 0}, '400')
})


// Скролл
$(window).on('scroll', function () {
    var scrollTop = $(window).scrollTop()


    if (scrollTop < 200) {

        // Кнопка вверх
        btnUp.removeClass('scale-in').addClass('scale-out')

    } else {

        // Кнопка вверх
        btnUp.addClass('scale-in').removeClass('scale-out')

    }
})
