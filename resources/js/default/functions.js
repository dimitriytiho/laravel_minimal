export default {

    // Функция задаёт одинаковую высоту блоков html, передать название класса
    getHeight: function ($class) {
        const els = document.querySelectorAll('.' + $class)
        let arr = []
        els.forEach(function (el) {
            arr.push(el.offsetHeight)
        })
        if (arr) {
            const maxHeight = Math.max(...arr) + 'px';
            els.forEach(function (el) {
                el.style.height = maxHeight
            })
        }
    },

    // Функция показывает селектор, у которого в css прописано display: none
    showJS: function ($selector) {
        const el = document.querySelector($selector)
        if (el) el.style.display = 'block'
    },


    // Прокрутить к верху страницы
    scrollUp: function () {
        if (typeof window === 'undefined') return
        window.scrollTo(0, 0)
        // $('html, body').animate({scrollTop: 0}, '400')
    },


    // Сравнить 2 массива
    diffArr: function (arr1, arr2) {
        return JSON.stringify(arr1) === JSON.stringify(arr2)
    },


    // Строку к snake-case
    snake: function (string) {
        return string.replace(/([a-z])([A-Z])/g, "$1-$2")
            .replace(/\s+/g, '-')
            .toLowerCase();
    },


    // Получить значение формы в объект (передать уникальный идефикатор формы)
    serialize: function (form) {
        let obj = {}
        form = document.querySelector(form)

        if (form) {
            let inputs = form.querySelectorAll('input, textarea')
            if (inputs) {
                inputs.forEach(function (el) {
                    let name = el.name,
                        value = el.value

                    if (name && value) {
                        obj[name] = value
                    }
                })
            }
        }
        return obj
    },


    strReplace: function (search, replace, str) {
        if (str) {
            return str.replace(search, replace)
        }
        return false
    },


    // Первая буква заглавная
    ucFirst: function (str) {
        return str.substr(0,1).toUpperCase() + str.substr(1)
    },


    // Заменяет в строке все _ на -
    snake_case: function (str) {
        return str.replace(/-/g, '_')
    },
    

    // Возвращает случайное значение из массива, принимает массив
    array_rand: function (arr) {
        if (arr) {
            return arr[Math.floor(Math.random() * arr.length)]
        }
        return false
    }

}
