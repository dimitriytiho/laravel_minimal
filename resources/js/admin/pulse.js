
document.addEventListener('DOMContentLoaded', function() {

    // При кликена на .pulse эффект пульса
    $('.pulse').click(function (e) {
        var div = document.createElement('div'),
            style = div.style,
            max = Math.max(e.target.offsetWidth, e.target.offsetHeight),
            rect = e.target.getBoundingClientRect(),
            px = 'px',
            color = window.getComputedStyle(e.target).backgroundColor,
            timeDeleteDiv = 300,
            self = $(this)

        // Сформируем нужный div
        div.classList.add('pulse_js')
        style.width = style.height = max + px
        style.left = e.clientX - rect.left - (max / 2) + px
        style.top = e.clientY - rect.top - (max / 2) + px
        style.backgroundColor = color
        style.opacity = .4

        // Вставим div
        self.append(div)

        // Удалим div
        setTimeout(function () {
            self.children('.pulse_js').remove()
        }, timeDeleteDiv)
    })

}, false)
