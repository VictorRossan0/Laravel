@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header font-weight-bold">
                    <h2 class="float-left">Cadastrar Usuário</h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('users.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nome</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Senha</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="type">Tipo</label>
                            <select class="form-control" id="type" name="type">
                                <option value="0">Analista</option>
                                <option value="1">Admin</option>
                                <option value="2">Gerente</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="setor">Nome do Projeto</label>
                            <input type="text" id="setor" name="setor" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="gestor_imediato">Gestor Imediato</label>
                            <input type="text" id="gestor_imediato" name="gestor_imediato" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">Voltar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection