import alert from '../default/alert';

window.Dropzone = require('dropzone')
Dropzone.autoDiscover = false


/*
* Задайте JS переменные в основном шаблоне:
- imgMaxSizeHD - максимальное HD разшение картинок,
- imgMaxSize - максимальное среднее разшение картинок,
- imgMaxSizeSM - максимальное маленькое разшение картинок,
- maxFilesOne - кол-во для одиночной загрузки,
- maxFilesMany - кол-во для множественной загрузки,
- imgURL - URL, на который будет отправлять запрос,
- currentClass - название модели, с которой работаем в данный момент,
- table - Таблица в БД, в которую сохранить картинку,
- imgRequestName - начальная часть названия передаваемой картинки,
- defaultImg - картинка по-умолчанию,
- imgUploadID - ID элемента, для которого картинка.
*
* В html используйте для одной картинке: <div id="dzOne" class="dropzone"></div>
* Или для многожественной загрузки: <div id="dzMany" class="dropzone"></div>
*
* Задайте id dropzone-images для div, в который загружать теги img, после успешной загрузки картинки.
*
* Необходимые переводы из массива translations: you_have_reached_maximum_file_upload_allowed, allowed_to_upload_files, upload_success, error_occurred
*/
const dzOneEl = '#dzOne',
    dzOneSelector = document.querySelector(dzOneEl),

    dzManyEl = '#dzMany',
    dzManySelector = document.querySelector(dzManyEl)


// Для загрузки одной картинки
if (dzOneSelector) {

    const dzOne = dropzone(dzOneEl, maxFilesOne, imgMaxSizeSM)
}

// Для множественной загрузки
if (dzManySelector) {

    const dzMany = dropzone(dzManyEl, maxFilesMany, imgMaxSizeHD)
}


/*
* Функция по загрузки файлов.
* dropzoneElement - селектор html области загрузки файлов.
* maxFiles - максимальное кол-во файлов для разовой загрузки, кол-во шт.
* resizeMax - максимальное разрешение в px, которое будем сохранять на сервере.
*/
function dropzone(dropzoneElement, maxFiles, resizeMax) {

    const imgURL = main.url + '/img-upload',
        dzImages = document.getElementById('dropzone-images'),
        dzGallery = document.getElementById('dropzone-gallery'),
        avatar = document.getElementById('avatar')


    return new Dropzone(dropzoneElement, {
        url: imgURL,
        headers: {
            'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
        },

        resizeWidth: resizeMax,
        resizeHeight: resizeMax,
        //resizeQuality: 0.9, // По-умолчанию 0.8
        //resizeMethod: 'contain', // Можно выбрать crop для жесткого обрезания картинки

        maxFiles: maxFiles,
        parallelUploads: maxFiles,

        dictDefaultMessage: `<div class="dz-message">${translations['select_file_or_drag_here']}</div>`,
        dictMaxFilesExceeded: translations['you_have_reached_maximum_file_upload_allowed'] + '{{maxFiles}}',
        acceptedFiles: acceptedImagesExt,
        dictInvalidFileType: translations['allowed_to_upload_files'] + acceptedImagesExt,
        init: function() {

            // Формируем данные, которые передаём в контроллер
            this.on('sending', function(file, xhr, formData) {

                // Передаваемая картинка
                formData.append('file', file)

                // Таблица в БД, в которую сохранить картинку
                formData.append('table', table)

                // Начальная часть названия передаваемой картинки
                formData.append('class', currentClass)

                // ID элемента, для которого картинка
                formData.append('imgUploadID', imgUploadID)

                // Начальная часть названия передаваемой картинки
                formData.append('name', imgRequestName)

                // Если передаём одну картинку, то передать 1. Если передаём множество картинок, то передать цифру больше 1
                formData.append('maxFiles', maxFiles)
            })
        },
        success: function(file, res) {
            // const res = JSON.parse(response)
            const img = file.dataURL, // Картинка времменая из JS
                imgHidden = document.querySelector('input[name=img]')

            //console.log(res.test); return
            if (res.answer === 'success') {

                // Одиночная или множественная загрузка
                if (maxFiles <= 1) {

                    if (imgHidden) {
                        imgHidden.setAttribute('value', res.href)
                    }

                    // Если меняется картинка пользователя, то заменим её в шапке сайта
                    if (currentClass === 'User' && imgUploadID === curID && avatar) {
                        avatar.setAttribute('src', res.href)
                    }

                    // Вставим картинку
                    if (dzImages) {
                        dzImages.innerHTML = `<a href="${res.href}" target="_blank"><i class="fas fa-times img-remove" data-img="${res.href}" data-max-files="${maxFiles}"></i><img src="${res.href}" alt=""></a>`
                    }

                } else {

                    // Вставим картинку
                    if (dzGallery) {
                        const appendImg = document.createElement('a')

                        appendImg.setAttribute('href', res.href)
                        appendImg.setAttribute('target', '_blank')
                        appendImg.innerHTML = `<i class="fas fa-times img-remove" data-img="${res.href}" data-max-files="${maxFiles}"></i><img src="${res.href}" alt="">`

                        dzGallery.appendChild(appendImg)
                    }
                }

                // Сообщение об успехе
                alert.get(translations['upload_success'] + ' ' + res.name, 'success')

            } else {
                alert.get(res.answer)
            }

            // Очистим загрузочную область
            this.removeAllFiles()
        },
        error: function(file, error) {
            alert.get(error)

            // Очистим загрузочную область
            this.removeAllFiles()
        }
    })
}
