
if (recaptchaKey && recaptchaV === 3) {
    grecaptcha.ready(function() {
        grecaptcha.execute(recaptchaKey, {action: 'submit'}).then(function(token) {
            // localStorage.setItem('recaptcha', token)
            const inputs = document.querySelectorAll('input[name="g-recaptcha-response"]')
            if (inputs.length) {
                inputs.forEach(function (input) {
                    input.value = token
                })
            }
        })
    })
}
