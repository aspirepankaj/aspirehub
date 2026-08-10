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
        Schema::table('adspv_staff', function (Blueprint $table) {
            $table->unsignedBigInteger('designation_id')->nullable()->after('user_id');
        });

        // Data migration: Match current 'role' string values with designations and link them
        $staffMembers = DB::table('adspv_staff')->get();
        foreach ($staffMembers as $staff) {
            if ($staff->role) {
                $designation = DB::table('adspv_designations')->where('name', $staff->role)->first();
                if ($designation) {
                    DB::table('adspv_staff')->where('id', $staff->id)->update([
                        'designation_id' => $designation->id
                    ]);
                }
            }
        }

        Schema::table('adspv_staff', function (Blueprint $table) {
            $table->dropColumn('role');
            $table->foreign('designation_id')->references('id')->on('adspv_designations')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adspv_staff', function (Blueprint $table) {
            $table->dropForeign(['designation_id']);
            $table->string('role')->nullable()->after('company_name');
        });

        // Revert data migration: Populate 'role' back from designation names
        $staffMembers = DB::table('adspv_staff')->get();
        foreach ($staffMembers as $staff) {
            if ($staff->designation_id) {
                $designation = DB::table('adspv_designations')->where('id', $staff->designation_id)->first();
                if ($designation) {
                    DB::table('adspv_staff')->where('id', $staff->id)->update([
                        'role' => $designation->name
                    ]);
                }
            }
        }

        Schema::table('adspv_staff', function (Blueprint $table) {
            $table->dropColumn('designation_id');
        });
    }
};
