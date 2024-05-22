@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                @if(session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                
                <div class="card-header font-weight-bold">
                    <h2 class="float-left">Cadastro em Lote</h2>
                    {{-- <h2 class="float-right">
                        <a href="{{ route('export.excel', 'xlsx') }}" class="btn btn-success">Exportar Excel</a>
                        <a href="{{ route('export.excel', 'csv') }}" class="btn btn-success">Exportar CSV</a>
                    </h2> --}}
                </div>
        
                <div class="card-body">
                    <form id="excel-csv-import-form" method="POST" action="{{ route('import.excel') }}" accept-charset="utf-8" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="file" name="file" class="form-control-file">
                                </div>
                                @error('file')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                                <button type="submit" class="btn btn-primary" id="submit">Enviar</button>
                                <a href="{{route('colaboradores.index')}}" class="btn btn-outline-primary">Voltar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
