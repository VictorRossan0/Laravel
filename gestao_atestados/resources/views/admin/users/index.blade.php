@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h1 class="mb-0">Lista de Usuários</h1>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.index') }}" method="GET" class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="input-group flex-grow-1">
                                <input type="text" name="query" class="form-control" placeholder="Digite o nome do usuário...">
                            </div>
                            <div class="ml-2">
                                <select name="per_page" class="form-control">
                                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="20" {{ request('per_page', 10) == 20 ? 'selected' : '' }}>20</option>
                                    <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                                    <!-- Adicione outras opções conforme necessário -->
                                </select>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary ml-2">Pesquisar</button>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Tipo</th>
                                    <th>Nome do Projeto</th>
                                    <th>Gestor Imediato</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->type }}</td>
                                    <td>{{ $user->setor }}</td>
                                    <td>{{ $user->gestor_imediato }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('users.show', $user->id) }}" class="btn btn-info btn-sm">Show</a>
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm">Editar</a>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza de que deseja excluir este usuário?')">Deletar</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $users->appends(['query' => Request::input('query')])->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection