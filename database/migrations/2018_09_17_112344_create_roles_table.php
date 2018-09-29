<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_roles', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->text('description');

            $table->integer('created_by');
            $table->foreign('created_by')
                ->references('id')
                ->on('users');

            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('blog_role_id')->nullable();

            $table->foreign('blog_role_id')
                ->references('id')
                ->on('blog_roles')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('blog_role_id');
        });

        Schema::dropIfExists('blog_roles');
    }
}
