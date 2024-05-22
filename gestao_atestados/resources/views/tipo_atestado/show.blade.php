@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="font-weight-bold float-left">Detalhes do Atestado</h2>
                </div>
                
                <div class="card-body">
                    <p><strong>Tipo de Atestado:</strong> {{ $tipoAtestado->tipo_atestado }}</p>
                    <p><strong>Cadastrado por:</strong> {{ $tipoAtestado->user->name }}</p>
                    <p><strong>Colaborador:</strong>{{ $tipoAtestado->colaborador}}</p>
                    <p><strong>Tipo:</strong> {{ $tipoAtestado->tipo }}</p>
                    <p><strong>CID:</strong> {{ $tipoAtestado->CID ?? 'N/A' }}</p>
                    <p><strong>Data:</strong> {{ $tipoAtestado->data->format('d-m-Y') }}</p>
                    <p><strong>Quantidade de Dias:</strong> {{ $tipoAtestado->quantidade_dias ?? 'N/A' }}</p>
                    <p><strong>Horas:</strong> {{ $tipoAtestado->horas_format ?? 'N/A' }}</p>
                    <p><strong>Minutos:</strong> {{ $tipoAtestado->minutos_format ?? 'N/A' }}</p>
                    <p><strong>Data Fim:</strong> {{ optional($tipoAtestado->data_fim)->format('d-m-Y') ?? 'N/A' }}</p>
                    <p><strong>Data Retorno:</strong> {{ optional($tipoAtestado->data_retorno)->format('d-m-Y') ?? 'N/A' }}</p>
                    <p><strong>Observações:</strong> {{ $tipoAtestado->obs ?? 'N/A' }}</p>
                    <p><strong>Arquivo:</strong> {{ basename($tipoAtestado->arquivo) }}</p>
                    <p>
                        <a href="{{ route('tipo_atestado.file', ['tipoAtestado' => $tipoAtestado]) }}" target="_blank">
                            <strong>Visualizar Arquivo</strong>
                        </a>
                    </p>
                    <br>
                    <p><strong>Status:</strong> {{ $tipoAtestado->status }}</p>
                    <a href="{{ route('tipo_atestado.index') }}" class="btn btn-outline-info">Voltar à Lista</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection