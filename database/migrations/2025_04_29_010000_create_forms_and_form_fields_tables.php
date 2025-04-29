<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Esta migración queda vacía porque la creación de 'forms' y 'form_fields' ya está cubierta por migraciones anteriores.
    }
    public function down()
    {
        // No hace nada
    }
};
