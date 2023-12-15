<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIpToAuditTrailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audit_trails', function (Blueprint $table) {
            // Add 'user_ip' column
            $table->string('user_ip')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audit_trails', function (Blueprint $table) {
            // Reverse if needed
            $table->dropColumn('user_ip');
        });
    }
}
