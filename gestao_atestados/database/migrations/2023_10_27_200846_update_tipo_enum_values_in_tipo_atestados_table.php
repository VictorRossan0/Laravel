<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement("ALTER TABLE tipo_atestados MODIFY tipo ENUM('Audiencia', 'Consulta medica/odontologica', 'Exame medico', 'Doenca', 'Repouso a Gestante', 'Acidente de trabalho', 'Atestado para Amamentacao', 'Laudo/Parecer Medico', 'Resultado Pericia', 'Licenca Maternidade', 'Licenca Paternidade', 'Acompanhamento familiar', 'Luto', 'Licenca Casamento')");
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tipo_atestados', function (Blueprint $table) {
            //
        });
    }
};
