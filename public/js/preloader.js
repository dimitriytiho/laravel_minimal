/*
 * В тегах head до подключения preloader.min.js добавить метатег:
 * <meta name="theme-color" content="#777">
 */
document.write('<div id="preloader"></div>')
var preloader = document.getElementById('preloader'),
    color = document.querySelector('meta[name="theme-color"]') && document.querySelector('meta[name="theme-color"]').getAttribute('content') || '#555'

if (preloader) {
    preloader.style.position = 'absolute'
    preloader.style.zIndex = '999'
    preloader.style.height = '3px'
    preloader.style.width = '1%'
    preloader.style.backgroundColor = color
    preloader.style.transitionProperty = 'width'
    preloader.style.transitionDuration = '500ms'

    // Сразу загружаем 33%
    setTimeout(function () {
        preloader.style.width = '33%'
    }, 50)

    // После загрузки Dom дерева загружаем 66%
    document.addEventListener('DOMContentLoaded', () => {
        preloader.style.width = '66%'
    })

    // После загрузки всего загружаем 100%
    window.onload = function() {
        preloader.style.width = '100%'

        // Скрываем preloader через секунду
        setTimeout(function () {
            preloader.style.display = 'none'
        }, 1000)
    }
}
