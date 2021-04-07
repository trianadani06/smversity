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
            $table->increments('id');
            $table->string('name');
            $table->string('email',50)->unique();
            $table->string('NIM',50)->unique();
            $table->date('tanggallahir');
            $table->string('angkatan');
            $table->string('jurusan_id');
            $table->text('foto');
            $table->text('coverfoto');
            $table->string('nickname');
            $table->string('stat');
            $table->string('verifiedBy');
            $table->text('description');
            $table->string('password');
            $table->rememberToken();
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
