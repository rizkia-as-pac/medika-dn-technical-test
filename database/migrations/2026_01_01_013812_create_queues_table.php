<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('queues', function (Blueprint $table) {
            $table->id();
            $table->date('date')->index();
            $table->unsignedInteger('number');
            $table->string('status')->default('waiting');
            $table->timestamp('called_at')->nullable();
            $table->timestamps();

            $table->unique(['date', 'number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};
