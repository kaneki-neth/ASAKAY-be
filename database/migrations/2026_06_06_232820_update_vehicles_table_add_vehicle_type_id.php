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
        Schema::table('vehicles', function (Blueprint $table) {
            $table->foreignId('vehicle_type_id')->nullable()->after('id')->constrained('vehicle_types');
        });

        // Migrate existing data
        $adminId = DB::table('users')->first()->id ?? 1;

        $types = DB::table('vehicles')->select('type')->distinct()->pluck('type');
        foreach ($types as $type) {
            $typeId = DB::table('vehicle_types')->insertGetId([
                'name' => ucfirst($type),
                'status' => 'active',
                'created_by' => $adminId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('vehicles')->where('type', $type)->update(['vehicle_type_id' => $typeId]);
        }

        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn('type');
        });
        
        Schema::table('vehicles', function (Blueprint $table) {
            $table->unsignedBigInteger('vehicle_type_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->enum('type', ['jeepney', 'bus', 'van'])->after('code')->default('jeepney');
        });

        $vehicles = DB::table('vehicles')->join('vehicle_types', 'vehicles.vehicle_type_id', '=', 'vehicle_types.id')->select('vehicles.id', 'vehicle_types.name')->get();
        foreach ($vehicles as $vehicle) {
            $type = strtolower($vehicle->name);
            if (!in_array($type, ['jeepney', 'bus', 'van'])) {
                $type = 'jeepney'; // fallback
            }
            DB::table('vehicles')->where('id', $vehicle->id)->update(['type' => $type]);
        }

        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['vehicle_type_id']);
            $table->dropColumn('vehicle_type_id');
        });
    }
};
