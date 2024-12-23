<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AddCompanyFieldsToTeamsTable extends Migration
{
    public function up()
    {
        Schema::table('teams', function (Blueprint $table) {
            // Remove the slug column addition since it already exists
            // $table->string('slug')->unique()->after('name');

            // Add other columns as needed
            $table->string('address')->nullable()->after('name');
            $table->string('phone')->nullable()->after('address');
            $table->string('logo')->nullable()->after('phone');
            $table->string('location')->nullable()->after('logo');
            $table->text('description')->nullable()->after('location');
            $table->string('insurance_document')->nullable()->after('description');
            $table->string('license_document')->nullable()->after('insurance_document');
            $table->text('associations')->nullable()->after('license_document');
        });
    }

    public function down()
    {
        Schema::table('teams', function (Blueprint $table) {
            // Drop the columns added in the up() method
            $table->dropColumn([
                'address',
                'phone',
                'logo',
                'location',
                'description',
                'insurance_document',
                'license_document',
                'associations',
            ]);

            // No need to drop the slug column since we didn't add it here
        });
    }
}
