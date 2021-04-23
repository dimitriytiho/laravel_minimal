
document.addEventListener('DOMContentLoaded', function() {

    /*
     * При выборе файла, подставим его имя в input file.
     * В класс .img_replace заменить картинку на загруженную.
     */
    $('input[name=img]').change(function (e) {
        var name = e.target.files[0] ? e.target.files[0].name : null
        if (name) {
            $(this).siblings('label').text(name)

            var path = URL.createObjectURL(e.target.files[0])
            if (path) {
                $('.img_replace').attr('src', path)
                //$(this).closest('.row').find('.this_img_replace').attr('src', path)
            }
        }
    })


    // При выборе файлов, подставим их имена в input file
    $('#files').change(function (e) {
        var files = e.target.files,
            text = ''

        // В цикле соберём названия файлов
        for (var key in files) {
            if (files.hasOwnProperty(key)) {
                text += files[key].name + ', '
            }
        }

        if (text.length) {

            // Возьмём строку без последних 2 символов
            text = text.substring(0, text.length - 2)

            // Вставим имена в label
            $(this).siblings('label').text(text)
        }
    })

}, false)
