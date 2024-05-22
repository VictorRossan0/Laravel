@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        <h2 class="float-left">Novo Atestado</h2>
                    </div>

                    <div class="card-body">
                        <form method="post" action="{{ route('tipo_atestado.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-container">
                                <div class="col-md-6 form-group">
                                    <label for="tipo_atestado"><strong>Tipo de Atestado:</strong></label>
                                    <select class="form-control" name="tipo_atestado" id="tipo_atestado" required>
                                        <option value="">Selecionar Opção</option>
                                        <option value="Horas"
                                            data-options="Audiencia, Consulta medica/odontologica, Exame medico">Horas
                                        </option>
                                        <option value="Licença Médica"
                                            data-options="Doenca,Repouso a Gestante,Acidente de trabalho,Atestado para Amamentacao,Laudo/Parecer Medico,Resultado Pericia">
                                            Licença Médica</option>
                                        <option value="Licença CLT"
                                            data-options="Licenca Maternidade,Licenca Paternidade,Acompanhamento familiar,Luto,Licenca Casamento">
                                            Licença CLT</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-container">
                                <div class="col-md-6 form-group">
                                    <label for="tipo_atestado"><strong>Colaborador:</strong></label>
                                    <select name="colaborador" id="colaborador" class="form-control" required>
                                        @if (Auth()->user()->type === 'Analista')
                                            <option value="{{ Auth()->user()->name }}" selected>{{ Auth()->user()->name }}
                                            </option>
                                        @elseif (Auth()->user()->type === 'Gerente' || Auth()->user()->type === 'Admin')
                                            <option value="">Selecionar Opção</option>
                                            @foreach ($colaboradores as $colaborador)
                                                <option value="{{ $colaborador->name }}">{{ $colaborador->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>


                            <div class="form-container">
                                <div class="col-md-6 form-group">
                                    <label for="tipo"><strong>Tipo:</strong></label>
                                    <select class="form-control" name="tipo" id="tipo" required>
                                        <!-- As opções serão adicionadas dinamicamente usando JavaScript -->
                                    </select>
                                </div>

                                <div class="col-md-6 form-group" id="coluna_cid">
                                    <label for="CID"><strong>CID:</strong></label>
                                    <input type="text" class="form-control" name="CID" id="CID">
                                </div>
                            </div>

                            <div class="form-container">
                                <div class="row">
                                    <div class="col-md-6 form-group" id="coluna_data">
                                        <label for="data"><strong>Data:</strong></label>
                                        <input type="text" class="form-control" name="data" id="data"
                                            placeholder="DD-MM-YYYY" data-input required>
                                    </div>

                                    <div class="col-md-6 form-group" id="coluna_quantidade_dias">
                                        <label for="quantidade_dias"><strong>Quantidade de Dias:</strong></label>
                                        <input type="number" class="form-control" name="quantidade_dias"
                                            id="quantidade_dias">
                                    </div>

                                    <div class="col-md-6 form-group" id="coluna_data_fim">
                                        <label for="data_fim"><strong>Data Fim:</strong></label>
                                        <input type="text" class="form-control" name="data_fim" id="data_fim"
                                            placeholder="DD-MM-YYYY" data-input>
                                    </div>

                                    <div class="col-md-6 form-group" id="coluna_data_retorno">
                                        <label for="data_retorno"><strong>Data Retorno:</strong></label>
                                        <input type="text" class="form-control" name="data_retorno" id="data_retorno"
                                            placeholder="DD-MM-YYYY" data-input>
                                    </div>
                                </div>
                            </div>

                            <!-- Column: Horas -->
                            <div class="form-container">
                                <div class="row">
                                    <div class="col-md-6 form-group" id="coluna_horas">
                                        <label for="horas"><strong>Horas:</strong></label>
                                        <input type="number" class="form-control" name="horas" id="horas">
                                    </div>

                                    <div class="col-md-6 form-group" id="coluna_minutos">
                                        <label for="minutos"><strong>Minutos:</strong></label>
                                        <input type="number" class="form-control" name="minutos" id="minutos">
                                    </div>
                                </div>
                            </div>

                            <!-- Column: Observações -->
                            <div class="form-container">
                                <div class="row">
                                    <div class="col-md-12 form-group" id="coluna_obs">
                                        <label for="obs"><strong>Observações:</strong></label>
                                        <textarea class="form-control" name="obs" id="obs"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="form-container">
                                <div class="row">
                                    <div class="form-group d-grid gap-2 col-6 mx-auto" id="coluna_arquivo">
                                        <label for="arquivo"><strong>Arquivo (Permitido formato PDF, JPEG, JPG,
                                                PNG):</strong></label>
                                        <input type="file" class="form-control-file" name="arquivo" id="arquivo"
                                            required accept=".pdf, .jpeg, .jpg, .png">
                                        <small class="form-text text-muted">Formatos permitidos: PDF, JPEG, JPG,
                                            PNG.</small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 form-group d-flex">
                                    <button type="submit" class="btn btn-primary btn-block">Enviar</button>
                                </div>

                                <div class="col-md-6 form-group d-flex">
                                    <a href="{{ route('tipo_atestado.index') }}"
                                        class="btn btn-outline-primary btn-block mr-2">Voltar à lista</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#tipo_atestado').change(function() {
                var selectedOption = $(this).find('option:selected');
                var optionsString = selectedOption.data('options');
                var optionsArray = optionsString.split(',');

                // Limpar e atualizar as opções suspensas Tipo
                var tipoDropdown = $('#tipo');
                tipoDropdown.empty();

                $.each(optionsArray, function(index, value) {
                    tipoDropdown.append($('<option>').text(value).attr('value', value));
                });

                // Mostrar ou ocultar colunas com base na opção selecionada
                var selectedValue = selectedOption.val();
                hideAllColumns();

                if (selectedValue !==
                    '') { // Ocultar colunas somente se uma opção específica estiver selecionada
                    if (selectedValue === 'Horas') {
                        showColumn('coluna_horas');
                        showColumn('coluna_minutos');
                        showColumn('coluna_data');
                    } else if (selectedValue === 'Licença Médica') {
                        showColumn('coluna_cid');
                        showColumn('coluna_data');
                        showColumn('coluna_quantidade_dias');
                        showColumn('coluna_data_fim');
                        showColumn('coluna_data_retorno');
                    } else if (selectedValue === 'Licença CLT') {
                        showColumn('coluna_quantidade_dias');
                        showColumn('coluna_data');
                        showColumn('coluna_data_fim');
                        showColumn('coluna_data_retorno');
                    }
                }
            });
        });

        function showColumn(columnId) {
            $('#' + columnId).show();
        }

        function hideColumn(columnId) {
            $('#' + columnId).hide();
        }

        function hideAllColumns() {
            hideColumn('coluna_cid');
            hideColumn('coluna_data');
            hideColumn('coluna_quantidade_dias');
            hideColumn('coluna_data_fim');
            hideColumn('coluna_data_retorno');
            hideColumn('coluna_horas');
            hideColumn('coluna_minutos');
            // Adicione outra coluna ocultando aqui...
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr('#data', {
                dateFormat: 'd-m-Y',
                placeholder: 'DD-MM-YYYY',
            });

            flatpickr('#data_fim', {
                dateFormat: 'd-m-Y',
                placeholder: 'DD-MM-YYYY',
            });

            flatpickr('#data_retorno', {
                dateFormat: 'd-m-Y',
                placeholder: 'DD-MM-YYYY',
            });
        });
    </script>

    <script>
        function addDays(date, days) {
            const newDate = new Date(date);
            newDate.setDate(newDate.getDate() + days);
            return newDate;
        }

        function calculateDates() {
            const tipoAtestadoSelect = document.getElementById('tipo_atestado');
            const quantidadeDiasInput = document.getElementById('quantidade_dias');
            const dataInput = document.getElementById('data');
            const dataFimInput = document.getElementById('data_fim');
            const dataRetornoInput = document.getElementById('data_retorno');

            const selectedTipoAtestado = tipoAtestadoSelect.options[tipoAtestadoSelect.selectedIndex].value;

            // Se a opção for "Horas" ou se a quantidade de dias não for um número válido, saia da função
            if (selectedTipoAtestado === 'Horas' || isNaN(quantidadeDiasInput.value)) {
                dataFimInput.value = '';
                dataRetornoInput.value = '';
                return;
            }

            const quantidadeDias = parseInt(quantidadeDiasInput.value);
            const data = flatpickr.parseDate(dataInput.value, 'd-m-Y');

            if (!isNaN(quantidadeDias) && data) {
                let dataFim = addDays(data, quantidadeDias - 1); // Subtrair 1 porque a data inicial já conta como um dia
                let dataRetorno = dataFim;

                if (dataFim.getDay() === 0) { // 0 é o código para domingo
                    dataRetorno = addDays(dataFim, 1); // Adiciona 1 dia para chegar na próxima segunda-feira
                } else if (dataFim.getDay() === 6) { // 6 é o código para sábado
                    dataRetorno = addDays(dataFim, 2); // Adiciona 2 dias para chegar na próxima segunda-feira
                } else if (dataFim.getDay() === 5) { // 5 é o código para sexta-feira
                    dataRetorno = addDays(dataFim, 3); // Adiciona 3 dias para chegar na próxima segunda-feira
                }

                const currentDate = new Date();
                if (data < currentDate) {
                    dataFim = addDays(currentDate, quantidadeDias - 2); // Subtrair 1 porque a data atual já conta como um dia
                    dataRetorno = dataFim;

                    if (dataFim.getDay() === 0) {
                        dataRetorno = addDays(dataFim, 1);
                    } else if (dataFim.getDay() === 6) {
                        dataRetorno = addDays(dataFim, 2);
                    }
                }

                dataFimInput.value = flatpickr.formatDate(dataFim, 'd-m-Y');
                dataRetornoInput.value = flatpickr.formatDate(dataRetorno, 'd-m-Y');
            }
        }


        document.addEventListener('DOMContentLoaded', function() {
            // Adicione event listeners para os campos quantidade_dias e tipo_atestado
            const quantidadeDiasInput = document.getElementById('quantidade_dias');
            quantidadeDiasInput.addEventListener('input', calculateDates);

            const tipoAtestadoSelect = document.getElementById('tipo_atestado');
            tipoAtestadoSelect.addEventListener('change', calculateDates);

            // Chame a função calculateDates para calcular as datas iniciais
            calculateDates();
        });
    </script>

@endsection
