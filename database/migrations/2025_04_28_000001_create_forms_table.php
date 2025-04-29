<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('table_name')->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('forms');
    }
};
