import func from './functions'


// Скрываем элементы с классами .js-hide
$('.js-hide').hide()

// Делаем видимыми элементы с классами .js-none-visible
$('.js-none-visible').show()

// Делаем видимыми элементы с классами .js-none-visible-flex
$('.js-none-visible-flex').css('display', 'flex')


// Отменяем обычное поведение ссылки при клике по класс .prevent-default
$('.prevent-default').click(function() {
    return false
})


/*
 * Открыть модальное окно по клику на класс .modal_show, при этом нужно указать здесь же атрибут data-modal-id="" и в него вписать id модального окна.
 * Можно задать data-modal-title="" и в него вписать заголовок модального окна.
 */
document.addEventListener('click', function(e) {

    var modalShowClass = 'modal_show',
        block = e.target.classList.contains(modalShowClass) || e.target.closest('.' + modalShowClass) && e.target.closest('.' + modalShowClass).classList.contains(modalShowClass)

    if (block) {
        var modalId = e.target.dataset.modalId || e.target.closest('.' + modalShowClass).dataset.modalId,
            modalTitle = e.target.dataset.modalTitle || e.target.closest('.' + modalShowClass).dataset.modalTitle

        if (modalId) {
            e.preventDefault()
            if (modalTitle) {
                $('#' + modalId + ' .modal-title').text(modalTitle)
            }
            $('#' + modalId).modal('show')
        }
    }
})



// При клике добавляем класс .active к родителю
$('.click_add_active').click(function() {
    $(this).parent().addClass('active')
})

// При клике в любом месте убираем класс .active у блока с классом .remove_active
document.body.onclick = function(e) {
    const blockClass = 'remove_active',
        blocks = document.querySelectorAll('.' + blockClass),
        blockClick = e.target.classList.contains(blockClass) || e.target.closest('.' + blockClass)

    if (!blockClick) {
        blocks.forEach(function (el) {
            if (el.classList.contains('active')) {
                el.classList.remove('active')
            }
        })
    }
}


document.addEventListener('DOMContentLoaded', function() {

    // Одинаковая высота блоков, задать класс у элементов .height-math
    //func.getHeight('height-math')


    /*
     * Один col fluid, другой нет.
     * Bootstrap container fluid one side.
     *
     * В колонку Bootstrap добавить класс .col_const_left или .col_const_right.
     */
    /*var clientWidth = document.body.clientWidth,
        container = document.querySelector('.container')
    if (clientWidth && container) {
        var containerWidth = container.offsetWidth,
            mobileVersion = 991

        // Desktop версии
        if (clientWidth > mobileVersion) {

            // Если ширина клиента больше контейнера Bootstrap
            if (clientWidth > containerWidth) {
                var difference = clientWidth - containerWidth

                // Меняем стили
                $('.col_const_left')
                    .css({
                        marginLeft: difference / 2,
                        maxWidth: (containerWidth / 2) + 'px'
                    })
                    .siblings('div').css('paddingRight', 0)

                $('.col_const_right')
                    .css({
                        marginRight: difference / 2,
                        maxWidth: (containerWidth / 2) + 'px'
                    })
                    .siblings('div').css('paddingLeft', 0)
            }

        // Мобильная версии
        } else {

            // Меняем стили
            $('.col_const_left')
                .css({
                    marginLeft: 'auto',
                    marginRight: 'auto',
                    maxWidth: containerWidth + 'px'
                })

            $('.col_const_right')
                .css({
                    marginLeft: 'auto',
                    marginRight: 'auto',
                    maxWidth: containerWidth + 'px'
                })

        }
    }*/


}, false)

