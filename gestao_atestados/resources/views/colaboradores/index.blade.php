@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h1>Lista de Colaboradores</h1>
                        <!-- Botão "Excluir Selecionados" -->
                        @if (auth()->user()->type === 'Admin')
                            <button type="button" class="btn btn-danger" id="delete-selected">Excluir Selecionados</button>
                        @endif
                    </div>
                </div>
        
                <div class="card-body">
                    <form action="{{ route('colaboradores.index') }}" method="GET" class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="input-group" style="flex: 1;">
                                <input type="text" name="query" class="form-control" placeholder="Pesquisar...">
                            </div>
                            <div class="d-flex align-items-center ml-3">
                                <select name="per_page" class="form-control">
                                    <option value="10" selected="selected" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                                    <option value="50" {{ request('per_page', 50) == 50 ? 'selected' : '' }}>50</option>
                                    <!-- Adicione outras opções conforme necessário -->
                                </select>
                                <label class="small m-0 ml-2">Registros por Página</label>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary ml-2">Pesquisar</button>
                            </div>
                        </div>
                    </form>                                     
                    
                    <table class="table table-bordered table-striped" style="width: 100%">
                        <thead class="col-md-12">
                            <tr>
                                <th>Nome</th>
                                <th>Setor</th>
                                <th>Ações</th>
                                @if (auth()->user()->type === 'Admin')
                                    <th>Excluir</th> <!-- Adicione esta coluna -->
                                    <!-- Adicione esta linha no cabeçalho da tabela -->
                                    <th>Selecionar Todos <input type="checkbox" id="select-all"></th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="col-md-12">
                            @foreach ($colaboradores as $colaborador)
                                <tr>
                                    <td style="vertical-align: middle;">{{ $colaborador->nome }}</td>
                                    <td style="vertical-align: middle;">{{ $colaborador->setor }}</td>
                                    <td style="text-align: center">
                                        <a href="{{ route('colaboradores.edit', $colaborador->id) }}" class="btn btn-primary">Editar</a>
                                        <a href="{{ route('colaboradores.show', $colaborador->id) }}" class="btn btn-info">Show</a>
                                    </td>
                                    @if (auth()->user()->type === 'Admin')
                                        <td style="text-align: center">
                                            <!-- Formulário para exclusão individual -->
                                            <form action="{{ route('colaboradores.destroy', $colaborador->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </td>
                                        <td style="text-align: center;">
                                            <!-- Caixa de seleção para selecionar colaboradores -->
                                            <input type="checkbox" name="selected_colaboradores[]" value="{{ $colaborador->id }}">
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center">
                        {{ $colaboradores->appends(['query' => Request::input('query')])->links('pagination::bootstrap-5') }}
                    </div>                  
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        // Quando o botão "Excluir Selecionados" for clicado
        $('#delete-selected').click(function () {
            // Obtenha os IDs dos colaboradores selecionados
            var selectedColaboradores = [];
            $('input[name="selected_colaboradores[]"]:checked').each(function () {
                selectedColaboradores.push($(this).val());
            });

            // Verifique se pelo menos um colaborador está selecionado
            if (selectedColaboradores.length > 0) {
                if (confirm('Você tem certeza que deseja excluir os colaboradores selecionados?')) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('colaboradores.delete-selected') }}',
                        data: {
                            _token: '{{ csrf_token() }}',
                            colaboradores: selectedColaboradores
                        },
                        success: function (data) {
                            // Atualize a página ou faça qualquer outra ação necessária
                            location.reload();
                        },
                        error: function (data) {
                            console.log(data);
                            alert('Ocorreu um erro ao excluir os colaboradores selecionados.');
                        }
                    });
                }
            } else {
                alert('Selecione pelo menos um colaborador para excluir.');
            }
        });

        // Quando a caixa de seleção "Selecionar Todos" for clicada
        $('#select-all').click(function () {
            // Marque ou desmarque todas as caixas de seleção de colaboradores
            $('input[name="selected_colaboradores[]"]').prop('checked', this.checked);
        });
    });
</script>
@endsection