<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">Atestado Criado</h1>
                </div>

                <div class="card-body">
                    <p>Olá, Olá Gestor,</p>

                    <p>Um novo atestado foi criado. Aqui estão os detalhes:</p>

                    <ul class="list-group">
                        <li class="list-group-item"><strong>Nome do Colaborador:</strong> {{ $tipoAtestado->colaborador }}</li>
                        <li class="list-group-item"><strong>Tipo de Atestado:</strong> {{ $tipoAtestado->tipo_atestado }}</li>
                        <li class="list-group-item"><strong>Data:</strong> {{ $tipoAtestado->data->format('d-m-Y') }}</li>
                        <!-- Adicione mais informações relevantes do atestado aqui -->
                    </ul>

                    <!-- Link para a página de detalhes do atestado -->
                    <a href="{{ route('tipo_atestado.index') }}">Mais Detalhes</a>

                    <p>Por favor, revise e tome as medidas necessárias.</p>

                    <p>Obrigado.</p>
                </div>
            </div>
        </div>
    </div>
</div>
