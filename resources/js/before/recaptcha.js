
if (recaptchaKey && recaptchaV === 3) {
    grecaptcha.ready(function() {
        grecaptcha.execute(recaptchaKey, {action: 'submit'}).then(function(token) {
            grecaptchaIds = document.querySelectorAll('input[name="g-recaptcha-response"]')
            if (grecaptchaIds) {
                grecaptchaIds.forEach(function (el) {
                    el.value = token
                })
            }
        })
    })
}
