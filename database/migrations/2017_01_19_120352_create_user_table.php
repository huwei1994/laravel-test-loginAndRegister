<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id')->comment('用户id');
            $table->string('name')->comment('用户昵称');
            $table->string('email')->unique()->comment('用户邮箱');
            $table->string('password')->comment('用户密码');
            $table->string('token')->comment('用户Token');
            $table->integer('created_at',false,true)->comment('创建时间');
            $table->integer('modified_at',false,true)->default(0)->comment('修改时间');
            $table->integer('deleted_at',false,true)->default(0)->comment('删除时间');
        });
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
