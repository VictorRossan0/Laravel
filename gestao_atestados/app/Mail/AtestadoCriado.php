<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\TipoAtestado; // Importe o modelo TipoAtestado para acessar os dados do atestado

class AtestadoCriado extends Mailable
{
    use Queueable, SerializesModels;

    public $atestado;
    public $supervisorName;

    /**
     * Create a new message instance.
     */
    public function __construct(TipoAtestado $atestado, $supervisorName)
    {
        $this->atestado = $atestado;
        $this->supervisorName = $supervisorName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Atestado Criado') // Assunto do e-mail
                    ->view('admin.emails.atestado-criado') // Utilize a nova view criada
                    ->with([
                        'supervisorName' => $this->supervisorName,
                        'tipoAtestado' => $this->atestado,
                    ]);
    }


}
