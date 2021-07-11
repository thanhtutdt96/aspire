<?php

namespace Database\Seeders;

use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PackageSeeder extends Seeder
{
    protected $tableName = 'packages';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table($this->tableName)->truncate();
        Schema::enableForeignKeyConstraints();

        $packages = [
            [
                'id' => 1,
                'interest_rate' => 7,
                'weeks' => 12,
                'arrangement_fee_rate' => 2,
                'description' => 'Loan package for 12 weeks',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now()
            ],
            [
                'id' => 2,
                'interest_rate' => 7.2,
                'weeks' => 18,
                'arrangement_fee_rate' => 2.5,
                'description' => 'Loan package for 18 weeks',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now()
            ],
            [
                'id' => 3,
                'interest_rate' => 7.5,
                'weeks' => 24,
                'arrangement_fee_rate' => 3,
                'description' => 'Loan package for 24 weeks',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now()
            ],
        ];

        Package::insert($packages);
    }
}
