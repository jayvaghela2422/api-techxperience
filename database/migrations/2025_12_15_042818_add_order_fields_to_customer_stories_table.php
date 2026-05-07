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
        Schema::table('customer_stories', function (Blueprint $table) {
            $table->integer('admin_order')->default(0)->after('status');
            $table->integer('public_order')->default(0)->after('admin_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_stories', function (Blueprint $table) {
            //
        });
    }
};
