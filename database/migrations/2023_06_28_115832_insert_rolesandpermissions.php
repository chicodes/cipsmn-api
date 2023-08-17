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
                'name' => 'ADMIN',
                'description' => 'for ADMIN',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'id' => '3',
                'name' => 'SUPPORT',
                'description' => 'for support',
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
            //super-admin permissions
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
                'role_id' => '1',
                'created_at' => '2023-06-28 00:55:23'
            ],


            //user permissions

            [
            'name' => 'USER_TOP_USER_MENUS',
            'description' => 'for user top menus',
            'role_id' => '3',
            'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'STUDENT_SIDEBAR',
                'description' => 'for student sidebar',
                'role_id' => '3',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'PAY_REGISTRATION',
                'description' => 'allows student pay for registration',
                'role_id' => '3',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'VIEW_EXAM_TO_PAY_FOR',
                'description' => 'allow student view exam to pay for',
                'role_id' => '3',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'PAY_EXEMPTION_FEE',
                'description' => 'allow student pay for exemption',
                'role_id' => '3',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'UPLOAD_CERTIFICATES',
                'description' => 'allow student upload certificates',
                'role_id' => '3',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'VIEW_CERTIFICATES',
                'description' => 'allow student view certificate',
                'role_id' => '3',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'VIEW_PAYMENTS',
                'description' => 'allow student view payment',
                'role_id' => '3',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'PAY_HONORARY',
                'description' => 'allow student pay for honorary',
                'role_id' => '3',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'PAY_INDUCTION',
                'description' => 'allow student pay for induction',
                'role_id' => '3',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'PAY_SUBSCRIPTION',
                'description' => 'allow student pay for subscription',
                'role_id' => '3',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'VIEW_BADGE',
                'description' => 'allow student pay view badge',
                'role_id' => '3',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'VIEW_PROFILE',
                'description' => 'allow student view profile',
                'role_id' => '3',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'EDIT_PROFILE',
                'description' => 'allow student edit profile',
                'role_id' => '3',
                'created_at' => '2023-06-28 00:55:23'
            ],




            //admin permissions

            [
                'name' => 'ADMIN_TOP_DASHBOARD_MENUS',
                'description' => 'admin top dashboard menus',
                'role_id' => '2',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'ADMIN_SIDEBAR',
                'description' => 'for admin sidebar',
                'role_id' => '2',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'VIEW_USERS',
                'description' => 'for viewing users',
                'role_id' => '2',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'ADD_USERS',
                'description' => 'for adding users',
                'role_id' => '2',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'VIEW_ALL_PAYMENTS',
                'description' => 'for viewing all payments',
                'role_id' => '2',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'CREATE_EXAM',
                'description' => 'for creatig exams',
                'role_id' => '2',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'VIEW_EXAM',
                'description' => 'for viewing exams',
                'role_id' => '2',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'CREATE_SUBJECT',
                'description' => 'for creating subjects',
                'role_id' => '2',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'VIEW_SUBJECT',
                'description' => 'for viewing subjects',
                'role_id' => '2',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'CREATE_BADGES',
                'description' => 'for creating badges',
                'role_id' => '2',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'VIEW_BADGES',
                'description' => 'for viewing badges',
                'role_id' => '2',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'HONORARY_PAYMENT_SETTING',
                'description' => 'for configuring honorary payment',
                'role_id' => '2',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'CONFERENCE_PAYMENT_SETTING',
                'description' => 'for configuring payment setting',
                'role_id' => '2',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'EXEMPTION_PAYMENT_SETTING',
                'description' => 'for configuring exemption payment setting',
                'role_id' => '2',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'CONVERSION_PAYMENT_SETTING',
                'description' => 'for configuring conversion payment setting',
                'role_id' => '2',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'SUBSCRIPTION_PAYMENT_SETTING',
                'description' => 'for configuring subscription payment setting',
                'role_id' => '2',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'UPDATE_PASSWORD_SETTING',
                'description' => 'for enabling password update',
                'role_id' => '2',
                'created_at' => '2023-06-28 00:55:23'
            ],
            [
                'name' => 'CONFIGURE_DONATION_SETTING',
                'description' => 'for enabling password payment setting',
                'role_id' => '2',
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
