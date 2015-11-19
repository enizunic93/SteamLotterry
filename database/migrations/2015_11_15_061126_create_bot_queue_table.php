<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateBotQueueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bot_queue', function (Blueprint $table) {
            $table->increments('id');
            $table->string('command');
            $table->text('arguments');
            $table->string('steam_id', 64)->unique();
        });

        /**
         * TODO: TEST!!! убрать!!!!!!!!!!!!
         */

        DB::table('bot_queue')->insert(
            [
                'command' => 'send-item',
                'arguments' => '{"receiver": "76561198031832170","appId": "570", "contextId": "2", "amount": "1", "itemId":"2028616720", "token": "zMih7YeC", "accountID": "131823210"}',
                'steam_id' => '76561198121640559'
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
        Schema::drop('bot_queue');
    }
}
