<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    protected $tableName = 'users';

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

        $users = [
            [
                'id' => 1,
                'name' => 'Tu Pham',
                'email' => 'thanhtutdt96@gmail.com',
                'identity_number' => '0123456789',
                'birthday' => Carbon::create(1996, 9, 5),
                'gender' => 'male',
                'address' => '123 To Hien Thanh',
                'city' => 'Ho Chi Minh',
                'phone' => '0374263605',
                'password' => bcrypt('YF4pfsHx'),
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now()
            ],
            [
                'id' => 2,
                'name' => 'Aspire',
                'email' => 'test@aspire.com',
                'identity_number' => '99999999',
                'birthday' => Carbon::create(1996, 1, 1),
                'gender' => 'female',
                'address' => '123 Dien Bien Phu',
                'city' => 'Ho Chi Minh',
                'phone' => '0123456789',
                'password' => bcrypt('N349csW3'),
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now()
            ]
        ];

        User::insert($users);
    }
}
