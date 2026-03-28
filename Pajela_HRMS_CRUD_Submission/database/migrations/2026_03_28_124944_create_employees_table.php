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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('FN');
            $table->string('MN')->nullable();
            $table->string('LN');
            $table->enum('gender', ['male', 'female']);
            $table->date('hire_date');
            $table->enum('status', ['active', 'resigned', 'terminated', 'on_leave']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
