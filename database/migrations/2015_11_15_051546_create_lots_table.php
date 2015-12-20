<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateLotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lots', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('app_id')->unsigned();
            $table->bigInteger('class_id')->unsigned();
            $table->string('bot_steam_id', 64)->unique();
            // Тип цена на сайте за место
            $table->integer('price_per_place')->unsigned();
            // Тип сколько мест может быть
            $table->integer('places')->unsigned();
            $table->timestamps();
        });

        DB::table('lots')->insert(
            [
                'app_id' => '570',
                'class_id' => '771176189',
                'bot_steam_id' => '76561198121640559',
                'price_per_place' => '220',
                'places' => '50',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('lots');
    }
}