@php
    use Carbon\Carbon;
    $anoAtual = date('Y');
    $mesAtual = date('n');
    $tipoAtestadoModel = new \App\Models\TipoAtestado(); // Crie uma instância do modelo
    $diasUteis = $tipoAtestadoModel->diasUteisNoMes($anoAtual, $mesAtual);
    $isGerente = auth()->user()->type === 'Gerente';
@endphp

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h1>Lista de Atestados</h1>

                            <!-- Se houver atestados, exiba as informações de absenteísmo -->
                            @if ($usuario->id)
                                <div>
                                    @if ($isGerente)
                                        <a href="{{ route('absenteismo.equipe') }}" class="btn btn-link">Ver Absenteísmo da
                                            Equipe</a>
                                    @endif
                                </div>
                                <div>
                                    @php
                                        $mesAtual = Carbon::now()->month;
                                        $anoAtual = Carbon::now()->year;

                                        // Calcula o número de dias úteis no mês atual
                                        $diasUteis = 0;
                                        $primeiroDia = Carbon::createFromDate($anoAtual, $mesAtual, 1);

                                        for ($i = 1; $i <= $primeiroDia->daysInMonth; $i++) {
                                            $dia = $primeiroDia->copy()->addDays($i - 1);
                                            if (!$dia->isWeekend()) {
                                                $diasUteis++;
                                            }
                                        }

                                        // Obtém os atestados aprovados para o usuário no mês atual
                                        $atestadosAprovados = $tipoAtestadoModel
                                            ::where('user_id', $usuario->id)
                                            ->where('status', 'Aprovado')
                                            ->whereMonth('data', $mesAtual)
                                            ->get();

                                        $totalAbsenteismoHoras = 0;

                                        foreach ($atestadosAprovados as $atestado) {
                                            if ($atestado->tipo_atestado === 'Horas') {
                                                // Para atestados do tipo 'Horas', calcula o total de horas de ausência
                                                $totalAbsenteismoHoras += $atestado->horas + $atestado->minutos / 60;
                                            } elseif (
                                                $atestado->tipo_atestado === 'Licenca Medica' ||
                                                $atestado->tipo_atestado === 'Licenca CLT'
                                            ) {
                                                // Para atestados do tipo 'Licenca Medica' ou 'Licenca CLT', calcula o total de dias de ausência
                                                $totalAbsenteismoHoras += $atestado->quantidade_dias * 8; // Considera 8 horas por dia
                                            }
                                        }

                                        // Calcula o total de absenteísmo para o usuário no mês atual
                                        $totalAbsenteismoPercent = ($totalAbsenteismoHoras / ($diasUteis * 8)) * 100;

                                        // Calcula o limite de faltas tolerado em horas
                                        $limiteFaltasTolerado = $diasUteis * 8 * 0.04;

                                    @endphp
                                    <p class="mb-0">Absenteismo: {{ number_format($totalAbsenteismoPercent, 2) }}%</p>
                                    <p class="mb-0">Target: 4%</p>
                                    <p class="mb-0">Dias úteis no mês: {{ $diasUteis }} Dias</p>
                                    <p class="mb-0">Limite de faltas tolerado: {{ round($limiteFaltasTolerado, 2) }} Horas
                                    </p>
                                    @if ($totalAbsenteismoPercent > 4)
                                        <p class="mb-0" style="color: red;">Limite Excedido:
                                            {{ number_format($totalAbsenteismoPercent - 4, 2) }}%</p>
                                    @endif
                                </div>
                            @else
                                <p class="mb-0">Não há atestados disponíveis.</p>
                            @endif
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('tipo_atestado.index') }}" method="GET">
                            <div class="input-group mb-3 input-group-sm">
                                <input type="text" name="query[colaborador]" class="form-control align-middle mr-2" placeholder="Colaborador">
                                <input type="text" name="query[data_atestado]" class="form-control align-middle mr-2" placeholder="Data do Atestado">
                                <input type="text" name="query[data_cadastro]" class="form-control align-middle mr-2" placeholder="Data de Cadastro">
                                <input type="text" name="query[tipo_atestado]" class="form-control align-middle mr-2" placeholder="Tipo Atestado">
                                <input type="text" name="query[anexo]" class="form-control align-middle mr-2" placeholder="Anexo">
                                <input type="text" name="query[status]" class="form-control align-middle mr-2" placeholder="Status">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary btn-sm">Pesquisar</button>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead style="white-space: nowrap;">
                                    <tr>
                                        <th>Colaborador</th>
                                        <th>Data do Atestado</th>
                                        <th>Data de Cadastro</th>
                                        <th>Tipo Atestado</th>
                                        <th>Anexo</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                        @if (auth()->user()->type === 'Admin')
                                            <th>Excluir</th> <!-- Adicione esta coluna -->
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tipoAtestados as $tipoAtestado)
                                        @can('view', $tipoAtestado)
                                            <tr>
                                                <td>{{ $tipoAtestado->colaborador }}</td>
                                                <td>{{ $tipoAtestado->data->format('d-m-Y') }}</td>
                                                <td>{{ $tipoAtestado->created_at->format('d-m-Y') }}</td>
                                                <td>{{ $tipoAtestado->tipo_atestado }}</td>
                                                <td>{{ basename($tipoAtestado->arquivo) }}</td>
                                                <td>{{ $tipoAtestado->status }}</td>
                                                <td style="white-space: nowrap; white-space: nowrap; display: flex; flex-direction: column; gap: 5px;">
                                                    <a href="{{ route('tipo_atestado.show', ['tipo_atestado' => $tipoAtestado->id]) }}"
                                                        class="btn btn-info btn-sm">Show</a>
                                                    @if (auth()->user()->type !== 'Analista')
                                                        <a href="{{ route('tipo_atestado.aprovar', ['id' => $tipoAtestado->id]) }}"
                                                            class="btn btn-success btn-sm">Aprovar</a>
                                                        <a href="#" class="btn btn-danger reprovar-button btn-sm"
                                                            data-toggle="modal" data-target="#reprovarModal"
                                                            data-atestado-id="{{ $tipoAtestado->id }}">Reprovar</a>
                                                    @endif
                                                </td>
                                                @if (auth()->user()->type === 'Admin')
                                                    <td style="white-space: nowrap;">
                                                        <!-- Formulário para exclusão -->
                                                        <form action="{{ route('tipo_atestado.destroy', $tipoAtestado->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </form>
                                                    </td>
                                                    <td style="white-space: nowrap;">
                                                        <!-- Caixa de seleção para selecionar atestados -->
                                                        <input type="checkbox" name="selected_atestados[]"
                                                            value="{{ $tipoAtestado->id }}">
                                                    </td>
                                                @endif
                                            </tr>
                                            @include('tipo_atestado.components.reprovar-modal', [
                                                'atestadoId' => $tipoAtestado->id,
                                            ])
                                        @endcan
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Botão para excluir atestados selecionados -->
                        @if (auth()->user()->type === 'Admin')
                            <button type="button" class="btn btn-danger" id="delete-selected">Excluir Selecionados</button>
                        @endif

                        <div class="d-flex justify-content-center">
                            {{ $tipoAtestados->appends(['query' => Request::input('query')])->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Quando o botão "Excluir Selecionados" for clicado
            $('#delete-selected').click(function() {
                // Obtenha os IDs dos atestados selecionados
                var selectedAtestados = [];
                $('input[name="selected_atestados[]"]:checked').each(function() {
                    selectedAtestados.push($(this).val());
                });

                // Se pelo menos um atestado estiver selecionado, envie uma solicitação de exclusão
                if (selectedAtestados.length > 0) {
                    if (confirm('Você tem certeza que deseja excluir os atestados selecionados?')) {
                        $.ajax({
                            type: 'POST',
                            url: '{{ route('tipo_atestado.delete-selected') }}',
                            data: {
                                _token: '{{ csrf_token() }}',
                                atestados: selectedAtestados
                            },
                            success: function(data) {
                                // Atualize a página ou faça qualquer outra ação necessária
                                location.reload();
                            },
                            error: function(data) {
                                console.log(data);
                                alert('Ocorreu um erro ao excluir os atestados selecionados.');
                            }
                        });
                    }
                } else {
                    alert('Selecione pelo menos um atestado para excluir.');
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {

            // Quando o botão "Reprovar" for clicado
            $('.reprovar-button').click(function() {
                var atestadoId = $(this).data('atestado-id');
                var formAction = '/tipo_atestado/' + atestadoId + '/reprovar';
                $('#reprovarForm').attr('action', formAction);
            });

            // Quando o formulário de reprovação for enviado
            $('#reprovarForm').submit(function(e) {
                e.preventDefault();
                var motivoReprovacao = $('#motivoReprovacao').val();

                // Envie uma solicitação AJAX para atualizar o motivo de reprovação
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: {
                        _token: '{{ csrf_token() }}',
                        motivoReprovacao: motivoReprovacao
                    },
                    success: function(data) {
                        // Atualize a página ou faça qualquer outra ação necessária
                        location.reload();
                    },
                    error: function(data) {
                        console.log(data);
                        alert('Ocorreu um erro ao reprovar o atestado.');
                    }
                });
            });
        });
    </script>
@endsection
