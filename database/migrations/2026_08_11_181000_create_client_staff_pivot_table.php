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
        Schema::create('adspv_client_staff', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('staff_id');
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('adspv_clients')->onDelete('cascade');
            $table->foreign('staff_id')->references('id')->on('adspv_staff')->onDelete('cascade');
            $table->unique(['client_id', 'staff_id']);
        });

        // Migrate existing assigned_staff_id to pivot table
        $clients = DB::table('adspv_clients')->get();
        foreach ($clients as $client) {
            if (!empty($client->assigned_staff_id)) {
                DB::table('adspv_client_staff')->insert([
                    'client_id' => $client->id,
                    'staff_id' => $client->assigned_staff_id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        // Drop assigned_staff_id foreign key & column
        Schema::table('adspv_clients', function (Blueprint $table) {
            $table->dropForeign(['assigned_staff_id']);
            $table->dropColumn('assigned_staff_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adspv_clients', function (Blueprint $table) {
            $table->unsignedBigInteger('assigned_staff_id')->nullable()->after('user_id');
            $table->foreign('assigned_staff_id')->references('id')->on('adspv_staff')->nullOnDelete();
        });

        // Restore single assigned_staff_id from pivot (taking the first matching item)
        $links = DB::table('adspv_client_staff')->get();
        foreach ($links as $link) {
            DB::table('adspv_clients')->where('id', $link->client_id)->update([
                'assigned_staff_id' => $link->staff_id
            ]);
        }

        Schema::dropIfExists('adspv_client_staff');
    }
};
