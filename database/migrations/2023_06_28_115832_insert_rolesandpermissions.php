<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InsertRolesandpermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Insert role data into the table
        DB::table('role')->insert([
            [
                'id' => '1',
                'name' => 'SUPER_ADMIN',
                'description' => 'for Super admin',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'id' => '2',
                'name' => 'SUPPORT',
                'description' => 'for support',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'id' => '3',
                'name' => 'ADMIN',
                'description' => 'for ADMIN',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'id' => '4',
                'name' => 'USER',
                'description' => 'for USER',
                'created_at' => '2023-06-28 00:55:23'
            ],
        ]);

        // Insert permission data into the table
        DB::table('permission')->insert([

            [
                'name' => 'MANAGE_USER',
                'description' => 'for managing user',
                'role_id' => '1',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'MANAGE_PAYMENTS',
                'description' => 'for viewing payments',
                'role_id' => '1',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'MANAGE_EXAM',
                'description' => 'for creating and editing exam',
                'role_id' => '1',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'MANAGE_SUBJECT',
                'description' => 'for managing subject',
                'role_id' => '1',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'MANAGE_BADGE',
                'description' => 'for managing badge',
                'role_id' => '1',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'MANAGE_PAYMENT_SETTINGS',
                'description' => 'for honorary, conversion payment price and other payment settings',
                'role_id' => '1',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'CONFIGURE_DONATION',
                'description' => 'for configuring donation',
                'role_id' => '',
                'created_at' => '2023-06-28 00:55:23'
            ]

        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
