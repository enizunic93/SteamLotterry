<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateSteamItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('steam_items', function (Blueprint $table) {
            $table->increments('id');
            /**
             * appId_classid
             */
            $table->string('item_id');
            $table->string('user_id');

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
        Schema::table('steam_items', function (Blueprint $table) {
            Schema::drop('steam_items');
        });
    }
}
