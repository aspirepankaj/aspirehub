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
        Schema::create('adspv_staff_designation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_id');
            $table->unsignedBigInteger('designation_id');
            $table->timestamps();

            $table->foreign('staff_id')->references('id')->on('adspv_staff')->onDelete('cascade');
            $table->foreign('designation_id')->references('id')->on('adspv_designations')->onDelete('cascade');
            $table->unique(['staff_id', 'designation_id']);
        });

        // Migrate existing designation_id to pivot table
        $staffMembers = DB::table('adspv_staff')->get();
        foreach ($staffMembers as $staff) {
            if ($staff->designation_id) {
                DB::table('adspv_staff_designation')->insert([
                    'staff_id' => $staff->id,
                    'designation_id' => $staff->designation_id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        // Drop designation_id foreign key & column
        Schema::table('adspv_staff', function (Blueprint $table) {
            $table->dropForeign(['designation_id']);
            $table->dropColumn('designation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adspv_staff', function (Blueprint $table) {
            $table->unsignedBigInteger('designation_id')->nullable()->after('user_id');
            $table->foreign('designation_id')->references('id')->on('adspv_designations')->nullOnDelete();
        });

        // Restore single designation_id from pivot (taking the first matching item)
        $links = DB::table('adspv_staff_designation')->get();
        foreach ($links as $link) {
            DB::table('adspv_staff')->where('id', $link->staff_id)->update([
                'designation_id' => $link->designation_id
            ]);
        }

        Schema::dropIfExists('adspv_staff_designation');
    }
};
