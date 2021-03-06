
/*
 * При изменении select с классом select_change делается Get запрос.
 * Записать в data-url="" url на который отправлять запрос.
 * Записать в data-key="" ключ для url запроса.
 * Отправится значение из select.
 */
$('.select_change').change(function () {
    var self = $(this),
        url = self.data('url') || '',
        key = self.data('key') || '',
        val = self.val() || ''

    if (url && key) {
        window.location = url + '?token=' + _token + '&key=' + key + '&val=' + val
    }
})


/*
 * При изменении select с классом .select_change_get делается Get запрос.
 * Записать в data-url="" url на который отправлять запрос или будет отправлен на текущий.
 * Записать в data-key="" ключ для url запроса.
 * Отправится значение из select.
 */
$('.select_change_get').change(function (e) {
    e.preventDefault()
    var self = $(this),
        url = self.data('url') || location.pathname,
        key = self.data('key') || '',
        val = self.val() || ''

    if (key) {
        window.location = url + '?' + key + '=' + val
    }
})


// При клике на класс link_click делается Get запрос
$('.link_click').click(function (e) {
    e.preventDefault()
    var self = $(this),
        url = self.data('url') || '',
        val = self.data('val') || '',
        id = self.data('id') || ''

    if (id) {
        id = '&id=' + id
    }

    if (url && val) {
        window.location = url + '?token=' + _token + '&val=' + val + id
    }
})


// При клике на класс get_disabled добавиться атрибут disabled
$('.get_disabled').click(function () {
    setTimeout(function () {
        $(this)
            .attr('disabled', true)
            .addClass('disabled')
    }.bind(this),10)
})


// При клике на класс get_disabled_spinner добавиться атрибут disabled и включится spinner
$('.get_disabled_spinner').click(function () {
    setTimeout(function () {
        $(this)
            .attr('disabled', true)
            .addClass('disabled')
            .prepend(spinnerBtn)
    }.bind(this),10)
})


/*
 * Открыть модальное окно по клику на класс modal_show, при этом нужно указать здесь же атрибут data-modal-id="" и в него вписать id модального окна.
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


// При клике на класс click_submit отправляем форму-родителя
$('.click_submit').click(function () {
    $(this).closest('form').submit()
})
