@isset($dataId)
    {{--



    Json data --}}
    {{ html()->hidden($dataId, $values->$dataId ?? '{}') }}
    <div class="{{ $dataId }}_json_wrap">
        @isset($values->$dataId)
            @foreach(json_decode($values->$dataId, true) as $key => $value)
                <div class="row {{ $dataId }}_json_block">
                    <div class="col-md-2">
                        {{ $form::input($dataId . '_json_key', ['id' => $dataId . '_json_key_' . $key], $key, false, 'key') }}
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-11">
                                {{ $form::input($dataId . '_json_value', ['id' => $dataId . '_json_value_' . $key], $value, false, 'value') }}
                            </div>
                            <div class="col-1 pt-3">
                                <button type="button" class="btn btn-outline-danger mt-4 {{ $dataId }}_json_btn_remove">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endisset
        <div class="{{ $dataId }}_json_append">
            <div class="row {{ $dataId }}_json_block {{ $dataId }}_json_add">
                <div class="col-md-2">
                    {{ $form::input($dataId . '_json_key', [], null, false, 'key') }}
                </div>
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-11">
                            {{ $form::input($dataId . '_json_value', [], null, false, 'value') }}
                        </div>
                        <div class="col-1 pt-3">
                            <button type="button" class="btn btn-outline-danger mt-4 {{ $dataId }}_json_btn_remove js-none">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <button type="button" class="btn btn-outline-info pulse mt-2 mb-3 {{ $dataId }}_json_btn_add">@lang('a.add')</button>
                </div>
            </div>
        </div>
    </div>
    {{--



    Этот код будет выведен после всех скриптов --}}
    @push('js')
        <script>
            var form = $('.{{ $dataId }}_json_wrap'),
                formHtml = $('.{{ $dataId }}_json_append').html(),
                inputsBlock = '.{{ $dataId }}_json_block:not(.{{ $dataId }}_json_add)'


            // Клик на кнопку добавить
            $(document).on('click', '.{{ $dataId }}_json_btn_add', function() {
                var self = $(this)

                // Заполняем данными input data
                if ({{ $dataId }}HandleData(self)) {

                    self.closest('.row').removeClass('{{ $dataId }}_json_add') // Удалить класс _json_add
                        .find('.{{ $dataId }}_json_btn_remove').removeClass('js-none') // Удалить класс js-none

                    // Удалить кнопку
                    self.remove()

                    // Вставить пустую форму
                    form.append(formHtml)

                } else {

                    // Показать ошибку
                    toastr.error('{{ __('a.key') }} {{ Str::lower(__('a.required')) }}')
                }
            })



            // Клик на кнопку удалить
            $(document).on('click', '.{{ $dataId }}_json_btn_remove', function() {
                var self = $(this)

                // Заполняем данными input data
                {{ $dataId }}HandleData(self, false)

                // Удаляем блок
                self.closest('.{{ $dataId }}_json_block').remove()
            })



            // Отлеживаем изменение inputs, кроме inputs добавления
            $(document).on('input', inputsBlock + ' input', function() {
                var data = {}
                $(inputsBlock).each(function(i, el) {
                    var key = $(el).find('input[name={{ $dataId }}_json_key]').val(),
                        value = $(el).find('input[name={{ $dataId }}_json_value]').val()

                    // Собираем данные в объект
                    data[key] = value
                })

                // Вставляем данные в input
                $('#{{ $dataId }}').val(JSON.stringify(data))
            })



            /*
             * Добавляем или удаляем данные из скрытого input в полученные данные.
             * self - jQuery объект текущего события.
             * $addData - если нужно удалить данные передать false.
             */
            function {{ $dataId }}HandleData(self, $addData = true) {
                if (self.length) {
                    var key = self.closest('.{{ $dataId }}_json_block').find('input[name={{ $dataId }}_json_key]').val(),
                        value = self.closest('.{{ $dataId }}_json_block').find('input[name={{ $dataId }}_json_value]').val(),
                        input = $('#{{ $dataId }}'),
                        data = input.val()

                    if (key) {

                        // Приводим данные к Json
                        data = data ? JSON.parse(data) : data

                        if ($addData) {

                            // Добавляем данные
                            data[key] = value
                        } else {

                            // Удаляем данные
                            delete data[key];
                        }

                        // Вставить данные скрытый input
                        input.val(JSON.stringify(data))

                        return true
                    }
                }
                return false
            }



            /*function {{ $dataId }}InsertData(self) {
                if (self.length) {

                }
                return false
            }*/
        </script>
    @endpush
@endisset
