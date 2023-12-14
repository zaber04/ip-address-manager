<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditTrailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_trails', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('action');
            $table->string('property_name');
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();
            $table->foreignUuid('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->uuid('session_id')->nullable();
            $table->timestamps();
        });

        // Add index on foreign key column
        Schema::table('audit_trails', function (Blueprint $table) {
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('audit_trails');
    }
}
