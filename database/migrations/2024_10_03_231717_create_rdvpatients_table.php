<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rdvpatients', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->string('tel');
            $table->string('motif');
            $table->string('statut')->index();
            $table->string('codemedecin');
            $table->string('patient_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rdvpatients');
    }
};
