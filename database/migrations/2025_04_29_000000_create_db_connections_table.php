<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('db_connections', function (Blueprint $table) {
            $table->id();
            $table->string('host');
            $table->integer('port')->default(3306);
            $table->string('database');
            $table->string('username');
            $table->text('password'); // Encriptada
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('db_connections');
    }
};
