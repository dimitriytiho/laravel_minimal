
document.addEventListener('DOMContentLoaded', function() {

    // При клике на .get_slug из вышестоящего input транситирируется текст, в data-src="title" указать input в котором name="title"
    $('.get_slug').click(function () {
        var self = $(this),
            url = self.data('url'),
            src = self.data('src'),
            slug = self.closest('form').find('input[name=' + src + ']').val()

        $.ajax({
            type: 'POST',
            url: url,
            data: {_token: _token, slug: slug},
            success: function(response) {
                self.closest('.input-group').find('input').val(response)
            }
        })
    })


    // При клике на #key_to_enter создаётся новый ключ, отправляют письма все админам
    /*$('#key_to_enter').click(function () {
        var self = $(this),
            url = self.data('url'),
            key = self.closest('.input-group').find('input').val()

        $.ajax({
            type: 'POST',
            url: url,
            data: {_token: _token, key: key},
            success: function(response) {

                // Перезагрузим страницу
                document.location.href = requestPath

                // Покажем тост
                $(document).Toasts('create', {
                    class: 'bg-success',
                    //title: 'Toast Title',
                    //subtitle: 'Subtitle',
                    body: response
                })
            }
        })
    })*/

}, false)
