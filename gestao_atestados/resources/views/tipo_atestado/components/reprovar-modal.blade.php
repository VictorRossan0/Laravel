<!-- resources/views/components/reprovar-modal.blade.php -->

<div class="modal fade" id="reprovarModal" tabindex="-1" aria-labelledby="reprovarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="reprovarForm" action="{{ route('tipo_atestado.doReprovar', ['id' => $atestadoId]) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="reprovarModalLabel">Reprovar Atestado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="motivoReprovacao">Motivo da Reprovação</label>
                        <textarea class="form-control" id="motivoReprovacao" name="motivoReprovacao" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-danger">Reprovar</button>
                </div>
            </form>
        </div>
    </div>
</div>