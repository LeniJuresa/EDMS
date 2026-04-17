<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
{
    Schema::create('reports', function (Blueprint $table) {
        $table->id();

        $table->string('session_id')->index();

        $table->string('id_number')->nullable()->index(); // dispatcher id_number
        $table->json('messages')->nullable();

        $table->string('location');
        $table->text('description');
        $table->string('file_location')->nullable();

        $table->string('status')->default('pending')->index(); // pending/claimed/accepted/denied
        $table->timestamp('claimed_at')->nullable();
        $table->timestamp('closed_at')->nullable();

        $table->timestamps();
    });
}


    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
