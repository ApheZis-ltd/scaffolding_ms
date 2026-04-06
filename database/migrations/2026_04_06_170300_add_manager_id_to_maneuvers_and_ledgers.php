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
        Schema::table('maneuvers', function (Blueprint $table) {
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('manager_comments')->nullable();
        });

        Schema::table('financial_ledgers', function (Blueprint $table) {
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('financial_ledgers', function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
            $table->dropColumn('manager_id');
        });

        Schema::table('maneuvers', function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
            $table->dropColumn(['manager_id', 'manager_comments']);
        });
    }
};
