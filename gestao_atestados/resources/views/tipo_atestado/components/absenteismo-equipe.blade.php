@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h1>Absenteísmo da Equipe</h1>
                </div>

                <div class="card-body text-center">
                    <form action="{{ route('absenteismo.equipe') }}" method="GET" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="query" class="form-control" placeholder="Digite o nome do Analista...">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">Pesquisar</button>
                            </div>
                        </div>
                    </form>
                    <table class="table table-responsive table-bordered">
                        <thead style="white-space: nowrap;" class="align-center">
                            <tr>
                                <th>Analista</th>
                                <th>Absenteísmo Mensal</th>
                                <th>Atestados Aprovados</th>
                                <th>Mês/Ano</th>
                                <th>Gráfico Analista</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($absenteismoEquipe as $item)
                                <tr>
                                    <td>{{ $item['analista'] }}</td>
                                    <td>{{ number_format(sprintf('%.2f', $item['absenteismo']), 2) }}%</td>
                                    <td>
                                        {{-- Verifique se $item['atestados_aprovados'] é um array --}}
                                        @if (is_array($item['atestados_aprovados']))
                                            @foreach ($item['atestados_aprovados'] as $atestado)
                                                {{ $atestado['mes'] }}: {{ $atestado['total'] }} atestados<br>
                                            @endforeach
                                        @else
                                            {{ $item['atestados_aprovados'] }} atestados
                                        @endif
                                    </td>
                                    <td>{{ Carbon\Carbon::createFromDate($item['ano'], $item['mes'])->format('F Y') }}</td>
                                    <td style="white-space: nowrap;">
                                        <button type="button" class="btn btn-info openModalBtn" data-toggle="modal" data-target="#analistaModal" data-analista-id="{{ $item['id'] }}" data-chart-target="#chart{{ $item['id'] }}">
                                            Gráfico Analista
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <p>Quantidade de analistas na equipe: {{ $quantidadeEquipe }}</p>
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#quantidadeAnalistasModal">
                        Ver Gráfico da Quantidade de Analistas na Equipe
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Inclua o script jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.0/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<script>
    // Declare variáveis globais para armazenar as instâncias dos gráficos
    let currentChart;
    let chartQuantidadeAnalistas;

    // Aguarde o carregamento completo da página
    $(document).ready(function() {
        $('.openModalBtn').on('click', function() {
            const analistaId = $(this).data('analista-id');

            axios.get('/tipo-atestados', {
                params: {
                    analistaId: analistaId
                }
            })
            .then(response => {

                let dados = Array.isArray(response.data) ? response.data : [];
                dados = dados.filter(item =>
                    item.absenteismo !== undefined &&
                    item.id !== undefined &&
                    item.absenteismo &&
                    item.id === analistaId
                );

                // Destrua o gráfico existente antes de criar um novo
                if (currentChart) {
                    currentChart.destroy();
                }

                // Crie um novo gráfico para o analista
                currentChart = new Chart(document.getElementById("myChartModal"), {
                    type: "bar",
                    data: {
                        labels: dados.map(item => formatDate(item.ano, item.mes)),
                        datasets: [
                            {
                                data: dados.map(item => parseFloat(item.absenteismo).toFixed(2)),
                                label: "Absenteísmo",
                                borderColor: 'rgb(54, 162, 235)',
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                fill: false,
                                datalabels: {
                                    color: 'black',
                                    anchor: 'end',
                                    align: 'top',
                                    offset: -5,
                                    display: function(context) {
                                        return context.dataset.data[context.dataIndex] > 0;
                                    },
                                    formatter: function(value, context) {
                                        return value > 0 ? value + '%' : '';
                                    }
                                }
                            },
                            {
                                type: 'line',
                                label: 'Target = 4%',
                                data: dados.map(item => item.target),
                                borderColor: 'rgb(255, 99, 132)',
                                backgroundColor: 'red',
                                fill: false,
                                datalabels: {
                                    display: false
                                }
                            }
                        ]
                    },
                    plugins: [ChartDataLabels],
                    options: {
                        title: {
                            display: true,
                            text: "Aprovados por Mês/Ano"
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Erro na solicitação:', error.message);
            });
        });

        // Dentro da função que lida com a exibição do gráfico da quantidade de analistas na equipe
        $('#quantidadeAnalistasModal').on('shown.bs.modal', function (e) {
            // Adicione um evento de mudança ao select
            $('#selectMes').on('change', function() {
                // Obtenha o valor selecionado do mês
                const selectedMonth = $(this).val();

                // Chame a função para obter os dados do servidor
                axios.get('/tipo-atestados', {
                    params: {
                        meses: [selectedMonth]
                    }
                })
                .then(response => {
                    const quantidadeAnalistasData = response.data;

                    // Destrua o gráfico existente antes de criar um novo
                    if (chartQuantidadeAnalistas) {
                        chartQuantidadeAnalistas.destroy();
                    }

                    // Crie um novo gráfico para a quantidade de analistas
                    chartQuantidadeAnalistas = new Chart(document.getElementById("chartQuantidadeAnalistas"), {
                        type: "bar",
                        data: {
                            labels: quantidadeAnalistasData.map(item => item.analista),
                            datasets: [
                                {
                                    data: quantidadeAnalistasData.map(item => parseFloat(item.absenteismo).toFixed(2)),
                                    label: "Absenteísmo Total da Equipe",
                                    borderColor: 'rgb(54, 162, 235)',
                                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                    fill: false,
                                    datalabels: {
                                        color: 'black',
                                        anchor: 'end',
                                        align: 'top',
                                        offset: -5,
                                        display: function(context) {
                                            return context.dataset.data[context.dataIndex] > 0;
                                        },
                                        formatter: function(value, context) {
                                            return value > 0 ? value + '%' : '';
                                        }
                                    }
                                }
                            ]
                        },
                        plugins: [ChartDataLabels],
                        options: {
                            title: {
                                display: true,
                                text: "Absenteísmo Total da Equipe"
                            }
                        }
                    });
                })
                .catch(error => {
                    console.error('Erro na solicitação:', error.message);
                });
            });
        });
    });

    // Função para formatar a data no formato desejado
    function formatDate(ano, mes) {
        // Adapte esta função conforme necessário para atender ao seu formato de data desejado
        return mes + '/' + ano;
    }
</script>

<!-- Adicione os scripts do Chart.js para o modal se necessário -->
@endsection

<div class="modal fade" id="analistaModal" tabindex="-1" role="dialog" aria-labelledby="analistaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="analistaModalLabel">Gráfico Analista:</h5>
            </div>
            <div class="modal-body">
                <div id="chartContainer">
                    <p id="absenteismoInfo">Informações de Absenteísmo:</p>
                    <!-- Use uma classe em vez de ID para o canvas -->
                    <canvas id="myChartModal" width="400" height="200"></canvas>
                </div> <!-- Container para o gráfico -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para o gráfico da quantidade de analistas na equipe -->
<div class="modal fade" id="quantidadeAnalistasModal" tabindex="-1" role="dialog" aria-labelledby="quantidadeAnalistasModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quantidadeAnalistasModalLabel">Gráfico de Absenteísmo Total da Equipe:</h5>
            </div>
            <div class="modal-body">
                <label for="selectMes">Selecione o Mês:</label>
                <select id="selectMes" class="form-select mb-3">
                    <option >Selecione o Mês</option>
                    <option value="1">Janeiro</option>
                    <option value="2">Fevereiro</option>
                    <option value="3">Março</option>
                    <option value="4">Abril</option>
                    <option value="5">Maio</option>
                    <option value="6">Junho</option>
                    <option value="7">Julho</option>
                    <option value="8">Agosto</option>
                    <option value="9">Setembro</option>
                    <option value="10">Outubro</option>
                    <option value="11">Novembro</option>
                    <option value="12">Dezembro</option>
                </select>
                <canvas id="chartQuantidadeAnalistas" width="400" height="200"></canvas>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
