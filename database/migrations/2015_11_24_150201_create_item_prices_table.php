<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateItemPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('class_id')->unsigned()->unique();
            $table->bigInteger('app_id')->unsigned();
            $table->double('min', null, 2)->unsigned();
            $table->double('median', null, 2)->unsigned();
            $table->bigInteger('volume')->unsigned();
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
        Schema::drop('item_prices');
    }
}
