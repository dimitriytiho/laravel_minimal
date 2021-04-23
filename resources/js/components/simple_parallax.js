document.addEventListener('DOMContentLoaded', function() {

    var parallax1 = document.getElementsByClassName('parallax1'),
        parallax2 = document.getElementsByClassName('parallax2'),
        parallax3 = document.getElementsByClassName('parallax3')


    new simpleParallax(parallax1, {
        delay: .6,
        transition: 'cubic-bezier(0,0,0,1)'
    })
    new simpleParallax(parallax2, {
        delay: 1.2,
        transition: 'cubic-bezier(0,0,0,1)'
    })
    new simpleParallax(parallax3, {
        delay: 2.4,
        transition: 'cubic-bezier(0,0,0,1)'
    })

}, false)
