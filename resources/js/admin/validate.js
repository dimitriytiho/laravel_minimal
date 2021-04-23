

document.addEventListener('DOMContentLoaded', function() {


    // Маска для телефона
    $('input[type=tel]').inputmask('+7(999)999-99-99')


    // Настройки по-умолчанию
    $.validator.setDefaults({

        // Правила валидации
        rules: {
            title: {
                required: true
            },
            slug: {
                required: true
            },
            /*value: {
                required: true
            },*/
            'role_ids[]': {
                required: true
            },
            name: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            tel: {
                //required: true,
                checkTel: true
            },
            password: {
                required: true,
                minlength: 6
            },
            password_confirmation : {
                required: true,
                minlength : 6,
                equalTo : '#password'
            },
            accept: {
                required: true
            },
        },


        // Свои сообщения валидации
        /*messages: {
            email: {
                required: 'Please enter a email address',
                email: 'Please enter a vaild email address'
            },
        },*/


        // При удаление фокуса с input
        onfocusout: function(element) {
            this.element(element)
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback')
            element.closest('.form-group').append(error)
            element.closest('.input-group').append(error)
            element.closest('.bootstrap-switch').addClass('border-danger')
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid')
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid')
        },


        //beforeSubmit: function () {},


        // Действия после успешного ввода
        submitHandler: function (form) {

            // Заблокируем кнопку, включим спиннер
            $(form)
                .find('[type=submit]')
                .attr('disabled', true)
                .prepend(spinnerBtn)

            return true
        }
    });


    // Добаляем валидатор для номера телефона
    $.validator.methods.checkTel = function(value, element) {
        return this.optional(element) || /^[\+\(\)\- 0-9]+$/.test( value)
    }


    // Запуск валидации
    $('.validate').validate()


    // Дополнительные правила для всех форм валидации
    /*$('input[type=email]').rules('add', {
        email: true
    })*/


}, false)
