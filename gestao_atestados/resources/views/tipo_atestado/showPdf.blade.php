@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header font-weight-bold">
                    <strong class="float-left">Visualização do Arquivo</strong>
                </div>
                
                <div class="card-body">
                    @if (in_array(pathinfo($tipoAtestado->arquivo, PATHINFO_EXTENSION), ['pdf']))
                        <iframe src="{{ route('tipo_atestado.file', $tipoAtestado) }}" style="width: 100%; height: 500px;"></iframe>
                    @else
                        <!-- Se não for um PDF, exiba uma mensagem ou lógica adequada para outros tipos de arquivo -->
                        <p>Visualização não suportada para este tipo de arquivo.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
