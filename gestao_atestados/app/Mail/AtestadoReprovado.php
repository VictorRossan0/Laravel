<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\TipoAtestado; // Importe o modelo TipoAtestado para acessar os dados do atestado

class AtestadoReprovado extends Mailable
{
    use Queueable, SerializesModels;

    public $atestado;
    public $analistaName;
    public $motivo;

    /**
     * Create a new message instance.
     */
    public function __construct(TipoAtestado $atestado, $analistaName, $motivo)
    {
        $this->atestado = $atestado;
        $this->analistaName = $analistaName;
        $this->motivo = $motivo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Atestado Reprovado') // Assunto do e-mail
                    ->view('admin.emails.atestado-reprovado') // Utilize a nova view criada
                    ->with([
                        'analistaName' => $this->analistaName,
                        'motivo' => $this->motivo,
                        'tipoAtestado' => $this->atestado,
                    ]);
    }
}