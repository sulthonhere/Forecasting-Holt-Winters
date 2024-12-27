<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peramalans', function (Blueprint $table) {
            $table->id();
            $table->date('periode');

            $table->float('alpha');
            $table->float('beta');
            $table->float('gamma');

            $table->float('hasil');
            $table->float('mape')->nullable();

            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peramalans');
    }
};
