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
        Schema::create('ieee_oui_data', function (Blueprint $table) {
            $table->id();
            $table->string('registry');
            $table->string('assignment');
            $table->string('organization_name');
            $table->string('organization_address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ieee_oui_data');
    }
};
