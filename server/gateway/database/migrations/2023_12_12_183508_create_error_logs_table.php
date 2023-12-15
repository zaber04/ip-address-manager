<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateErrorLogsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('error_logs', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID primary key
            $table->string('url'); // support relative urls
            $table->text('param')->nullable();
            $table->text('body')->nullable();
            $table->string('controller')->nullable();
            $table->string('functionName');
            $table->string('statusCode');
            $table->text('message');
            $table->text('error');
            $table->ipAddress('ip')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('error_logs');
    }
}
