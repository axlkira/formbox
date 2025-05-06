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
        Schema::table('forms', function (Blueprint $table) {
            $table->string('description')->nullable();
            $table->string('json_file')->nullable();
            $table->string('version')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('forms', function (Blueprint $table) {
            $table->dropColumn(['description', 'json_file', 'version']);
        });
    }
};
