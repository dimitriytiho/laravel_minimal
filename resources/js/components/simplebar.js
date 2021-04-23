document.addEventListener('DOMContentLoaded', function() {

    var simplebar = document.querySelectorAll('.simplebar')


    if (simplebar[0]) {
        simplebar.forEach(function (el) {
            new SimpleBar(el, {
                autoHide: false,
                scrollbarMinSize: 40,
                scrollbarMaxSize: 50
            })
        })
    }

}, false)
