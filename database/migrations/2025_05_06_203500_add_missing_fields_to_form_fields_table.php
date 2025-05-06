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
        Schema::table('form_fields', function (Blueprint $table) {
            if (!Schema::hasColumn('form_fields', 'type')) $table->string('type')->nullable();
            if (!Schema::hasColumn('form_fields', 'label')) $table->string('label')->nullable();
            if (!Schema::hasColumn('form_fields', 'name')) $table->string('name')->nullable();
            if (!Schema::hasColumn('form_fields', 'options')) $table->json('options')->nullable();
            if (!Schema::hasColumn('form_fields', 'order')) $table->integer('order')->nullable();
            if (!Schema::hasColumn('form_fields', 'required')) $table->boolean('required')->default(0);
            if (!Schema::hasColumn('form_fields', 'extra')) $table->json('extra')->nullable();
        });
    }

    public function down()
    {
        Schema::table('form_fields', function (Blueprint $table) {
            $table->dropColumn(['type','label','name','options','order','required','extra']);
        });
    }
};
