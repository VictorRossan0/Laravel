<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">Atestado Reprovado</h1>
                </div>

                <div class="card-body">
                    <p>Olá, {{ $analistaName }},</p>

                    <p>Seu atestado foi reprovado com o seguinte motivo:</p>

                    <p><strong>Motivo:</strong> {{ $motivo }}</p>
                    
                    <p>Por favor, revise o atestado e faça as correções necessárias.</p>

                    <!-- Adicione o link para a página de lista de atestados -->
                    <a href="{{ route('tipo_atestado.index') }}">Mais Detalhes</a>

                    <p>Obrigado.</p>
                </div>
            </div>
        </div>
    </div>
</div>
