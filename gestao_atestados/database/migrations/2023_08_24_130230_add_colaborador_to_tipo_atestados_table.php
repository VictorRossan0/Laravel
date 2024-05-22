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
        Schema::table('tipo_atestados', function (Blueprint $table) {
            $table->string('colaborador')->nullable(false); // 'nullable(false)' torna a coluna obrigatória
        });
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
