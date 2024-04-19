<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $schemaTableName = config('cart.table_name');

        Schema::create($schemaTableName, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable(true);
            $table->char('session_id', 191)->nullable(true);
            $table->integer('item_id')->unsigned();
            $table->integer('count')->unsigned();
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
        $schemaTableName = config('cart.table_name');

        Schema::dropIfExists($schemaTableName);
    }
}
