$(function () {

    // Слайдер по всему сайту
    $('.slider_simple').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        //autoplay: true,
        //autoplaySpeed: 5000,
        infinite: false,
        dots: false,
        arrows: true,
        nextArrow: '<i class="fas fa-arrow-right right"></i>',
        prevArrow: '<i class="fas fa-arrow-left left"></i>',
        //pauseOnHover: true,
        //variableWidth: true,
        //lazyLoad: 'ondemand', // ondemand progressive <img data-lazy="img/name.png">
        //draggable: false,
        //centerMode: true,
        //centerPadding: '0', // default 50px
        responsive: [
            {
                breakpoint: xl,
                settings: {
                    slidesToShow: 3,
                }
            },
            {
                breakpoint: lg,
                settings: {
                    slidesToShow: 2,
                    arrows: false,
                    dots: true
                }
            },
            {
                breakpoint: sm,
                settings: {
                    slidesToShow: 1,
                    arrows: false,
                    dots: true
                }
            }
        ]
    })


    // Слайдер на главной странице
    $('.slider_main').slick({
        autoplay: true,
        autoplaySpeed: 5000,
        dots: true,
        arrows: true,
        nextArrow: '<i class="fas fa-arrow-right right"></i>',
        prevArrow: '<i class="fas fa-arrow-left left"></i>',
        pauseOnHover: true,
        lazyLoad: 'ondemand', // ondemand progressive <img data-lazy="img/name.png">
    })


    // Слайдер в карточке товара для эскизов
    $('.slick_show_gallery').slick({
        slidesToShow: 6,
        slidesToScroll: 1,
        infinite: false,
        dots: false,
        arrows: true,
        nextArrow: '<i class="fas fa-arrow-right right"></i>',
        prevArrow: '<i class="fas fa-arrow-left left"></i>'
    })

    // Убираем мелькание картинок при загруки слайдера, т.е. после загрузки js показываем слайдер
    $('.slick-slider').show()


    // Кол-во слайдов в слайдере, работает после инициализации слайдера
    //console.log(sliderSimple.slick('getSlick').slideCount)


    // Убираем мелькание на заголовке
    /*setTimeout(function () {
        $('.slider_simple__a--title').show()
    }, 100)*/

})
