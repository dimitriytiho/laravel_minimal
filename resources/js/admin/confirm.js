
document.addEventListener('DOMContentLoaded', function() {

    // При отправки формы с .confirm-form будет подтвержение отправки
    $(document).on('submit', '.confirm_form', function(e) {
        e.preventDefault()
        var modal = $('#confirm_modal'),
            btnOk = modal.find('.btn[data-btn=ok]')

        // Открыть модальное окно
        modal.modal()

        // Отлеживаем клик по кнопке Ок
        btnOk.click(function () {

            // Закрыть модальное окно
            modal.modal('hide')

            // Отправить форму
            e.target.submit()
        }.bind(e))
    })


    // При клике по ссылке .confirm-link будет подтвержение отправки (добавить атрибуты data-toggle="modal" data-target="#confirm_modal")
    $(document).on('click', '.confirm_link', function(e) {
        e.preventDefault()
        var modal = $('#confirm_modal'),
            btnOk = modal.find('.btn[data-btn=ok]'),
            href = $(this).attr('href')


        // Открыть модальное окно
        modal.modal()


        // Отлеживаем клик по кнопке Ок
        btnOk.click(function () {

            // Закрыть модальное окно
            modal.modal('hide')

            // Переход по ссылке
            document.location.href = href
        })
    })

}, false)
