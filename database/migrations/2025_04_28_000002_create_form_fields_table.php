<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id');
            $table->string('type');
            $table->string('label');
            $table->string('name');
            $table->json('options')->nullable();
            $table->string('validation_prompt')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade');
        });
    }
    public function down() {
        Schema::dropIfExists('form_fields');
    }
};
