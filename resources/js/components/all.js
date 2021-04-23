document.addEventListener('DOMContentLoaded', function() {

    // Lazy
    $('.lazy').lazy()
    $('.lazy_delay').lazy({ // Появление картинки с задержкой
        delay: 100
    })


    // Fancybox
    $('.fancybox').fancybox()
    $('.fancybox_thumbs').fancybox({
        thumbs : {
            autoStart : true // По-умолчанию показываем эскизы
        }
    })

    // При клики на эскиз показываем галерею, начинаю с той картинки, на какую кликнули
    $('.fancybox_click').click(function (e) {
        var el,
            id = $(this).data('gallery')

        if (id) {
            el = $('.fancybox_thumbs[rel=' + id + ']:eq(0)')
            e.preventDefault()
            el.click()
        }
    })

}, false)
