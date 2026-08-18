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
        Schema::table('adspv_documents', function (Blueprint $table) {
            $table->string('resource_type')->default('file')->after('title');
            $table->string('url')->nullable()->after('resource_type');
            $table->string('file_path')->nullable()->change();
            $table->string('file_name')->nullable()->change();
            $table->string('file_type')->nullable()->change();
            $table->unsignedBigInteger('file_size')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adspv_documents', function (Blueprint $table) {
            $table->dropColumn('resource_type');
            $table->dropColumn('url');
            $table->string('file_path')->nullable(false)->change();
            $table->string('file_name')->nullable(false)->change();
            $table->string('file_type')->nullable(false)->change();
            $table->unsignedBigInteger('file_size')->nullable(false)->change();
        });
    }
};
