/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/admin/ajax.js":
/*!************************************!*\
  !*** ./resources/js/admin/ajax.js ***!
  \************************************/
/***/ (() => {

document.addEventListener('DOMContentLoaded', function () {
  // При клике на .get_slug из вышестоящего input транситирируется текст, в data-src="title" указать input в котором name="title"
  $('.get_slug').click(function () {
    var self = $(this),
        url = self.data('url'),
        src = self.data('src'),
        slug = self.closest('form').find('input[name=' + src + ']').val();
    $.ajax({
      type: 'POST',
      url: url,
      data: {
        _token: _token,
        slug: slug
      },
      success: function success(response) {
        self.closest('.input-group').find('input').val(response);
      }
    });
  }); // При клике на #key_to_enter создаётся новый ключ, отправляют письма все админам

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
}, false);

/***/ }),

/***/ "./resources/js/admin/components.js":
/*!******************************************!*\
  !*** ./resources/js/admin/components.js ***!
  \******************************************/
/***/ (() => {

document.addEventListener('DOMContentLoaded', function () {
  // Кроме страницы входа
  if (!document.querySelector('.login-page')) {
    // Select2
    $('.select2').select2({
      language: 'ru' //theme: 'bootstrap4'

    });
    $('.select2_one').select2({
      language: 'ru',
      maximumSelectionLength: 1
    });
    $('.select2_img').select2({
      language: 'ru',
      templateResult: function templateResult(option) {
        return $('<span><img src="' + document.location.origin + option.text + '" class="img-size-32"> ' + option.text + '</span>');
      },
      templateSelection: function templateSelection(option) {
        return $('<span><img src="' + document.location.origin + option.text + '" class="img-size-32" style="margin-top: -7px;"> ' + option.text + '</span>');
      }
    }); // Bootstrap Switch

    $('[data-toggle=switch]').bootstrapSwitch();
  } // Codemirror


  var codemirror = document.querySelector('.codemirror');

  if (codemirror) {
    editor = CodeMirror.fromTextArea(codemirror, {
      tabMode: 'indent',
      lineNumbers: true,
      lineWrapping: true,
      matchBrackets: true,
      indentUnit: 4
    });
    editor.setSize('auto', 'auto');
  } // Ckeditor


  var ckeditor = document.querySelector('.ckeditor');

  if (ckeditor) {
    CKEDITOR.config.height = '600px'; //CKEDITOR.config.filebrowserImageBrowseUrl = '/file-manager/ckeditor'
  }
}, false);

/***/ }),

/***/ "./resources/js/admin/confirm.js":
/*!***************************************!*\
  !*** ./resources/js/admin/confirm.js ***!
  \***************************************/
/***/ (() => {

document.addEventListener('DOMContentLoaded', function () {
  // При отправки формы с .confirm-form будет подтвержение отправки
  $(document).on('submit', '.confirm_form', function (e) {
    e.preventDefault();
    var modal = $('#confirm_modal'),
        btnOk = modal.find('.btn[data-btn=ok]'); // Открыть модальное окно

    modal.modal(); // Отлеживаем клик по кнопке Ок

    btnOk.click(function () {
      // Закрыть модальное окно
      modal.modal('hide'); // Отправить форму

      e.target.submit();
    }.bind(e));
  }); // При клике по ссылке .confirm-link будет подтвержение отправки (добавить атрибуты data-toggle="modal" data-target="#confirm_modal")

  $(document).on('click', '.confirm_link', function (e) {
    e.preventDefault();
    var modal = $('#confirm_modal'),
        btnOk = modal.find('.btn[data-btn=ok]'),
        href = $(this).attr('href'); // Открыть модальное окно

    modal.modal(); // Отлеживаем клик по кнопке Ок

    btnOk.click(function () {
      // Закрыть модальное окно
      modal.modal('hide'); // Переход по ссылке

      document.location.href = href;
    });
  });
}, false);

/***/ }),

/***/ "./resources/js/admin/pulse.js":
/*!*************************************!*\
  !*** ./resources/js/admin/pulse.js ***!
  \*************************************/
/***/ (() => {

document.addEventListener('DOMContentLoaded', function () {
  // При кликена на .pulse эффект пульса
  $('.pulse').click(function (e) {
    var div = document.createElement('div'),
        style = div.style,
        max = Math.max(e.target.offsetWidth, e.target.offsetHeight),
        rect = e.target.getBoundingClientRect(),
        px = 'px',
        color = window.getComputedStyle(e.target).backgroundColor,
        timeDeleteDiv = 300,
        self = $(this); // Сформируем нужный div

    div.classList.add('pulse_js');
    style.width = style.height = max + px;
    style.left = e.clientX - rect.left - max / 2 + px;
    style.top = e.clientY - rect.top - max / 2 + px;
    style.backgroundColor = color;
    style.opacity = .4; // Вставим div

    self.append(div); // Удалим div

    setTimeout(function () {
      self.children('.pulse_js').remove();
    }, timeDeleteDiv);
  });
}, false);

/***/ }),

/***/ "./resources/js/admin/scripts.js":
/*!***************************************!*\
  !*** ./resources/js/admin/scripts.js ***!
  \***************************************/
/***/ (() => {

document.addEventListener('DOMContentLoaded', function () {
  /*
   * При выборе файла, подставим его имя в input file.
   * В класс .img_replace заменить картинку на загруженную (если он есть в теге img).
   */
  $('input[type=file]').change(function (e) {
    var name = e.target.files[0] ? e.target.files[0].name : null;

    if (name) {
      $(this).siblings('label').text(name);
      var path = URL.createObjectURL(e.target.files[0]);

      if (path && $('img').hasClass('img_replace')) {
        $('.img_replace').attr('src', path); //$(this).closest('.row').find('.this_img_replace').attr('src', path)
      }
    }
  }); // При выборе файлов, подставим их имена в input file

  $('#files').change(function (e) {
    var files = e.target.files,
        text = ''; // В цикле соберём названия файлов

    for (var key in files) {
      if (files.hasOwnProperty(key)) {
        text += files[key].name + ', ';
      }
    }

    if (text.length) {
      // Возьмём строку без последних 2 символов
      text = text.substring(0, text.length - 2); // Вставим имена в label

      $(this).siblings('label').text(text);
    }
  });
}, false);

/***/ }),

/***/ "./resources/js/admin/scroll.js":
/*!**************************************!*\
  !*** ./resources/js/admin/scroll.js ***!
  \**************************************/
/***/ (() => {

document.addEventListener('DOMContentLoaded', function () {
  var btn_up = $('#btn_up'); // При клике поднимаем к верху страницы

  btn_up.click(function () {
    $('html, body').animate({
      scrollTop: 0
    }, '400');
  }); // Код со скроллом

  $(window).on('scroll', function () {
    var scrollTop = scrollTop = $(window).scrollTop();

    if (scrollTop < 200) {
      // Кнопка вверх
      btn_up.removeClass('scale-in').addClass('scale-out');
    } else {
      // Кнопка вверх
      btn_up.addClass('scale-in').removeClass('scale-out');
    }
  });
}, false);

/***/ }),

/***/ "./resources/js/admin/temlate.js":
/*!***************************************!*\
  !*** ./resources/js/admin/temlate.js ***!
  \***************************************/
/***/ (() => {

document.addEventListener('DOMContentLoaded', function () {
  /*
   * При изменении select с классом .select_change делается Get запрос.
   * Записать в data-url="" url на который отправлять запрос.
   * Записать в data-key="" ключ для url запроса.
   * Отправится значение из select.
   */
  $('.select_change').change(function () {
    var self = $(this),
        url = self.data('url'),
        key = self.data('key'),
        val = self.val() || '';

    if (url && key) {
      window.location = url + '?token=' + _token + '&key=' + key + '&val=' + val;
    }
  }); // При клике на .link_click делается Get запрос

  $('.link_click').click(function (e) {
    e.preventDefault();
    var self = $(this),
        url = self.data('url'),
        val = self.data('val'),
        id = self.data('id') || '';

    if (id) {
      id = '&id=' + id;
    }

    if (url && val) {
      window.location = url + '?token=' + _token + '&val=' + val + id;
    }
  }); // При клике на .get_disabled добавиться атрибут disabled

  $('.get_disabled').click(function () {
    setTimeout(function () {
      $(this).attr('disabled', true).addClass('disabled');
    }.bind(this), 10);
  }); // При клике на .get_disabled_spinner добавиться атрибут disabled и включится spinner

  $('.get_disabled_spinner').click(function () {
    setTimeout(function () {
      $(this).attr('disabled', true).addClass('disabled').prepend(spinnerBtn);
    }.bind(this), 10);
  });
  /*
   * Открыть модальное окно по клику на класс .modal_show, при этом нужно указать здесь же атрибут data-modal-id="" и в него вписать id модального окна.
   * Можно задать data-modal-title="" и в него вписать заголовок модального окна.
   */

  document.addEventListener('click', function (e) {
    var modalShowClass = 'modal_show',
        block = e.target.classList.contains(modalShowClass) || e.target.closest('.' + modalShowClass) && e.target.closest('.' + modalShowClass).classList.contains(modalShowClass);

    if (block) {
      var modalId = e.target.dataset.modalId || e.target.closest('.' + modalShowClass).dataset.modalId,
          modalTitle = e.target.dataset.modalTitle || e.target.closest('.' + modalShowClass).dataset.modalTitle;

      if (modalId) {
        e.preventDefault();

        if (modalTitle) {
          $('#' + modalId + ' .modal-title').text(modalTitle);
        }

        $('#' + modalId).modal('show');
      }
    }
  }); // При клике на класс .click_submit отправляем форму-родителя

  $('.click_submit').click(function () {
    $(this).closest('form').submit();
  });
}, false);

/***/ }),

/***/ "./resources/js/admin/validate.js":
/*!****************************************!*\
  !*** ./resources/js/admin/validate.js ***!
  \****************************************/
/***/ (() => {

document.addEventListener('DOMContentLoaded', function () {
  // Маска для телефона
  $('input[type=tel]').inputmask('+9(999)999-99-99'); // Настройки по-умолчанию

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
      password_confirmation: {
        required: true,
        minlength: 6,
        equalTo: '#password'
      },
      accept: {
        required: true
      }
    },
    // Свои сообщения валидации

    /*messages: {
        email: {
            required: 'Please enter a email address',
            email: 'Please enter a vaild email address'
        },
    },*/
    // При удаление фокуса с input
    onfocusout: function onfocusout(element) {
      this.element(element);
    },
    errorElement: 'span',
    errorPlacement: function errorPlacement(error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
      element.closest('.input-group').append(error);
      element.closest('.bootstrap-switch').addClass('border-danger');
    },
    highlight: function highlight(element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function unhighlight(element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    },
    //beforeSubmit: function () {},
    // Действия после успешного ввода
    submitHandler: function submitHandler(form) {
      // Заблокируем кнопку, включим спиннер
      $(form).find('[type=submit]').attr('disabled', true).prepend(spinnerBtn);
      return true;
    }
  }); // Добаляем валидатор для номера телефона

  $.validator.methods.checkTel = function (value, element) {
    return this.optional(element) || /^[\+\(\)\- 0-9]+$/.test(value);
  }; // Запуск валидации


  $('.validate').validate(); // Дополнительные правила для всех форм валидации

  /*$('input[type=email]').rules('add', {
      email: true
  })*/
}, false);

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
/*!*************************************!*\
  !*** ./resources/js/admin/index.js ***!
  \*************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _confirm__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./confirm */ "./resources/js/admin/confirm.js");
/* harmony import */ var _confirm__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_confirm__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./components */ "./resources/js/admin/components.js");
/* harmony import */ var _components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_components__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _ajax__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ajax */ "./resources/js/admin/ajax.js");
/* harmony import */ var _ajax__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_ajax__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _scroll__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./scroll */ "./resources/js/admin/scroll.js");
/* harmony import */ var _scroll__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_scroll__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _pulse__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./pulse */ "./resources/js/admin/pulse.js");
/* harmony import */ var _pulse__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_pulse__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _validate__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./validate */ "./resources/js/admin/validate.js");
/* harmony import */ var _validate__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_validate__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _temlate__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./temlate */ "./resources/js/admin/temlate.js");
/* harmony import */ var _temlate__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_temlate__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _scripts__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./scripts */ "./resources/js/admin/scripts.js");
/* harmony import */ var _scripts__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_scripts__WEBPACK_IMPORTED_MODULE_7__);








})();

/******/ })()
;