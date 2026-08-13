<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('adspv_staff', function (Blueprint $table) {
            $table->json('departments')->nullable()->after('department');
        });

        // Migrate existing department column values to departments JSON array
        $staffMembers = DB::table('adspv_staff')->whereNotNull('department')->get();
        foreach ($staffMembers as $staff) {
            if (!empty($staff->department)) {
                DB::table('adspv_staff')
                    ->where('id', $staff->id)
                    ->update([
                        'departments' => json_encode([$staff->department])
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adspv_staff', function (Blueprint $table) {
            $table->dropColumn('departments');
        });
    }
};
