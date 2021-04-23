
import alert from './alert';


export default {

    /*
    * Записать куку через Action php, после записи куки будет перезагрузка старницы.
    * name - имя cookie.
    * value - значение cookie.
    * reloading - после записи куки будет перезагрузка старницы, если это не нужно, то передайте false.
    */
    setCookiePhp: function (name, value, reloading = true) {
        if (_token && cookieUrl && name && value) {

            $.ajax({
                type: 'POST',
                url: cookieUrl,
                data: {_token, name, value},
                success: function(res) {
                    if (reloading && res == 1) {

                        // Перезагрузка страницы
                        document.location.href = document.location.href
                    }
                },
                error: function() {
                    alert.get(translations['something_went_wrong'])
                }
            })
        }
        return false
    },


    // Возвращает куки с указанным name, или false, если ничего не найдено.
    getCookie: function (name) {
        let matches = document.cookie.match(new RegExp(
            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
        ))
        return matches ? decodeURIComponent(matches[1]) : false
    },


    /*
    * Устанавливает куку.
    * name - имя куки.
    * value - значение куки.
    * options - опции, например setCookie('user', 'John', {secure: true}), необязательный параметр.
    */
    setCookie: function (name, value, options = {}) {
        let isCookie = navigator.cookieEnabled,
            date = new Date(),
            dateCookie = new Date(date.getTime() + cookieTime)

        if (isCookie) {
            options = {
                path: '/',
                expires: dateCookie
                // При необходимости добавьте другие значения по-умолчанию
            }

            if (options.expires.toUTCString) {
                options.expires = options.expires.toUTCString()
            }

            let updatedCookie = encodeURIComponent(name) + "=" + encodeURIComponent(value)

            for (let optionKey in options) {
                updatedCookie += "; " + optionKey
                let optionValue = options[optionKey]
                if (optionValue !== true) {
                    updatedCookie += "=" + optionValue
                }
            }

            document.cookie = updatedCookie
        }
    },


    // Удалить куку с указанным name.
    deleteCookie: function (name) {
        setCookie(name, '', {
            'max-age': -1
        })
    }
}
