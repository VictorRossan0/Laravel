<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipoAtestadosTable extends Migration
{
    public function up()
    {
        Schema::create('tipo_atestados', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_atestado', ['Horas', 'Licenca Medica', 'Licenca CLT']);
            $table->enum('tipo', [
                'Audiencia', 'Consulta medica/odontologica', 'Exame medico',
                'Doenca', 'Repouso a Gestante', 'Acidente de trabalho', 'Atestado para Amamentacao',
                'Laudo/Parecer Medico', 'Resultado Pericia',
                'Licenca Maternidade', 'Licenca Paternidade', 'Acompanhamento familiar', 'Luto', 'Licenca Casamento'
            ]);
            $table->string('CID')->nullable();
            $table->date('data');
            $table->integer('quantidade_dias')->nullable();
            $table->integer('horas')->nullable();
            $table->integer('minutos')->nullable();
            $table->date('data_fim')->nullable();
            $table->date('data_retorno')->nullable();
            $table->text('obs')->nullable();
            $table->string('arquivo')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tipo_atestado');
    }
}