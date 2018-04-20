<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username')->nullable();
            $table->string('email');
            $table->string('api_token')->nullable();
            $table->string('fcm_token')->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_winner')->default(0);
            $table->boolean('has_complete')->default(0);
            $table->integer('coins')->default(500);
            $table->bigInteger('referrals')->default(0);
            $table->string('referral_code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
