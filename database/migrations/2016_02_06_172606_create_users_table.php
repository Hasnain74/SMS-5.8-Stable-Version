<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kodeine\Acl\Traits\HasRole;

class CreateUsersTable extends Migration
{

    use \Illuminate\Auth\Authenticatable, \Illuminate\Auth\Passwords\CanResetPassword, HasRole;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('user_id')->nullable();
                $table->string('username');
                $table->string('status');
                $table->string('photo_id')->nullable();
                $table->string('email')->unique();
                $table->string('password', 60);
                $table->string('api_token');
                $table->softDeletes();
                $table->rememberToken();
                $table->timestamps();

//                $table->foreign('user_id')->references('user_id')->on('teachers')->onDelete('cascade');

            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
