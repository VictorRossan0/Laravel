<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\TipoAtestado; // Importe o modelo TipoAtestado para acessar os dados do atestado

class AtestadoAprovado extends Mailable
{
    use Queueable, SerializesModels;

    public $atestado;
    public $analistaName;
    

    /**
     * Create a new message instance.
     */
    public function __construct(TipoAtestado $atestado, $analistaName)
    {
        $this->atestado = $atestado;
        $this->analistaName = $analistaName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Atestado Aprovado') // Assunto do e-mail
                    ->view('admin.emails.atestado-aprovado') // Utilize a nova view criada
                    ->with([
                        'analistaName' => $this->analistaName,
                        'tipoAtestado' => $this->atestado,
                    ]);
    }
}