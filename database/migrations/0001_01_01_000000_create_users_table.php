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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('api_token')->nullable();
            $table->string('login')->index();
            $table->string('user_first_name');
            $table->string('user_last_name');
            $table->string('tel')->nullable();
            $table->string('user_profil_id');
            $table->string('email');
            $table->string('password');
            $table->string('user_rights')->nullable();
            $table->string('user_make_date')->nullable();
            $table->string('user_revised_date')->nullable();
            $table->string('user_ip')->nullable();
            $table->string('user_history')->nullable();
            $table->string('user_logs')->nullable();
            $table->string('user_lang')->nullable();
            $table->string('user_photo')->nullable();
            $table->string('user_actif')->nullable();
            $table->string('user_actions')->nullable();
            $table->string('code_personnel')->nullable();
            $table->string('photo')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
