@if(!empty($info['table']) && method_exists($values, 'getAttributes'))
    @php


    // Модель LastData
    $lastDataModel = '\App\Models\LastData';


    // Колонки, которые используем у текущей модели
    $columns = array_keys($values->getAttributes());

    // Убираем исключения
    $columns = array_diff($columns, $lastDataModel::$exception);

    // Получаем в массив данные полей, кроме исключений
    if ($columns) {
        foreach ($columns as $column) {
            $columnsData[$column] = $values->$column;
        }
    }


    // Получаем данные для данного элемента
    $lastData = $lastDataModel::whereTable($info['table'])->whereElementId($values->id)->get();


    @endphp
    @if($columns && $lastData->count())
        <div class="card collapsed-card">
            <div class="card-header">
                <h3 class="card-title">@lang('a.history_change')</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="chop" data-columns='{!! json_encode($columns ?? [], JSON_UNESCAPED_UNICODE) !!}'>
                    @foreach($lastData as $key =>$item)
                        @php


                        $data = json_decode($item->data);
                        foreach ($columns as $column) {
                            if (!empty($data->$column)) {
                                $dataOld[$column] = $data->$column;
                            }
                        }
                        $json = json_encode($dataOld ?? [], JSON_UNESCAPED_UNICODE);


                        @endphp
                        <div class="chop_item last_data_click" data-json='{!! $json !!}'>
                            <p class="chop_item_p">{{ $item->created_at->format(config('admin.date_format')) }}</p>
                            <p class="chop_item_p font-weight-light">{{ $item->users->name }}</p>
                        </div>
                    @endforeach
                    <div class="chop_item js-none last_data_click_back" data-json='{!! json_encode($columnsData ?? [], JSON_UNESCAPED_UNICODE) !!}'>
                        <p class="chop_item_p">@lang('s.back')</p>
                    </div>
                </div>
            </div>
        </div>
        {{--


        Этот код будет выведен после всех скриптов --}}
        @section('scripts')
            <script>
                var btnLast = $('.last_data_click'),
                    btnBack = $('.last_data_click_back'),
                    columns = btnLast.parent().data('columns')
                {{--


                При клике на плитки истории --}}
                btnLast.click(function() {
                    var self = $(this),
                        data = self.data('json')

                    // Удаляем класс active у всех
                    self.parent().children().removeClass('active')

                    // Вставляем данные в значения формы
                    insertData(data, columns)

                    // Добавим класс active
                    self.addClass('active')

                    // Добавляем кнопку вернуть
                    btnBack.show()
                })
                {{--


                При клике на кнопку назад --}}
                btnBack.click(function () {
                    var self = $(this),
                        data = self.data('json')

                    // Вставляем данные в значения формы
                    insertData(data, columns)

                    // Удаляем класс active у всех
                    btnLast.removeClass('active')

                    // Удаляем кнопку назад
                    self.hide()
                })


                /*
                 * Функция вставляет данные в значения формы по id.
                 * data - данные для вставки.
                 * columns - массив с колонками, которые нужно брать из данных.
                 */
                function insertData(data, columns) {
                    if (data && columns) {

                        for (var key in columns) {
                            var id = document.getElementById(columns[key])
                            if (columns.hasOwnProperty(key) && id) {

                                // Удаляем класс is-valid
                                $(id).removeClass('is-valid')

                                // Вставляем данные в поля формы если есть разница значений, добавить класс is-valid
                                if (data[columns[key]] && data[columns[key]] !== $(id).val()) {
                                    $(id).val(data[columns[key]]).addClass('is-valid')
                                }
                            }
                        }
                    }
                }
            </script>
        @endsection
    @endif
@endif
