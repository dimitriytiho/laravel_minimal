
var triggerId = 'toggler_btn',
    menuMobileId = 'navbar_toggler',
    trigger = $('#' + triggerId),
    menuMobile = $('#' + menuMobileId),
    backdropId = 'for-backdrop',
    backdrop = $('#' + backdropId),
    backdropClassActive = 'backdrop-kd'


// При клике на триггер показываем мобильное меню
trigger.click(function() {
    setTimeout(function () {

        menuMobile.addClass('active')
        backdrop.addClass(backdropClassActive)

    }, 50)
})


// При клике на Backdrop закроем меню
backdrop.click(function() {
    closeMenu()
})


// При клике в любом месте закрывает меню
/*document.body.onclick = function(e) {
    var blockClass = 'navbar-collapse',
        blockClick = e.target.classList.contains(blockClass) || e.target.closest('.' + blockClass),
        block = document.getElementById('navbar_toggler')

    // Если выдвинуто мобильное меню и при этом клик не по самому меню, то закроем его
    if (block && window.getComputedStyle(block, null).getPropertyValue('left') === '0px' && !blockClick) {
        closeMenu()
    }
}*/


// При клике на любой пункт меню закрываем его
$('#' + menuMobileId + ' .nav-link').click(function() {
    closeMenu()
})


// Закрыть меню при проведение пальцем по экрану телефона
var touchStart,
    touchEnd

document.body.addEventListener('touchstart', function(e) {
    touchStart = e.changedTouches[0].clientX
}, false)
document.body.addEventListener('touchend', function(e) {
    touchEnd = e.changedTouches[0].clientX

    // Если провели пальцем больше 20px слево направо, (touchStart - touchEnd справо налево)
    if (touchStart - touchEnd > 20) {

        // Запускаем функцию
        closeMenu()
    }
}, false)


// Функция закрытия меню
function closeMenu() {
    menuMobile.removeClass('active')
    backdrop.removeClass(backdropClassActive)
}


// Активный родитель для основного меню
/*$('.menu .nav-item.parent .nav-link.active_color')
    .closest('.nav-item.parent')
    .children('.nav-link')
    .addClass('active_color')*/
