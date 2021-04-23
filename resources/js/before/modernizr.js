
// Если работает JS в браузере, то удаляем класс .no-js
document.documentElement.classList.remove('no-js')

// Скрипт подменяем картинки с data-webp на backgroundImage
var webpEl = document.querySelectorAll('.webp [data-webp]')
if (webpEl) {
    webpEl.forEach(function (el) {
        var img = el.dataset.webp
        if (img) {
            el.style.backgroundImage = 'url("' + img + '")'
        }
    })
}

// Скрипт подменяем картинки с data-webp-src на data-src
var webpLazy = document.querySelectorAll('.webp [data-webp-src]')
if (webpLazy) {
    webpLazy.forEach(function (el) {
        var img = el.dataset.webpSrc
        if (img) {
            el.src = img
        }
    })
}
