
export default {

    /*
     * Функция для вывода сообщений Bootstrap Alert, вставляет сообщение в <div id="get_alert_js"></div>.
     * text - текст сообщения.
     * alertClass - название цвета Bootstrap окна сообщения, необязательный параметр, по-умолчанию danger.
     * ms - время показа сообщения в милисекундах, необязательный параметр, по-умолчанию 3 секунды.
     */
    get(text, alertClass = 'danger', ms = 3000) {
        var el = document.getElementById('get_alert_js'),
            html = '<div class="container alert alert-'

        if (el) {
            html = html + alertClass + '">' + text + '</div>'

            // Вставляем сообщение в html код на сайте
            el.innerHTML = html

            // Удаляем сообщение из html через ms
            setTimeout(function () {
                el.innerHTML = ''
            }, ms)
        }
    }
}
