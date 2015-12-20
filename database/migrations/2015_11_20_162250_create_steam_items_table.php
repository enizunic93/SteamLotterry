<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSteamItemsTable extends Migration
{
    /**
     * Таблица предметов, которыми владеет игрок на сайте
     * Процесс загрузки предмета на сайт:
     *  1) Пользователь регистририуется и/или авторизовывается через Steam.
     *  2) Пользователь заходит в личный кабинет и жмёт кнопку "Загрузить вещи".
     *  3) Ему высвечивается таблица его предметов, он выделяет галочками те, что хочет загрузить.
     *  TODO: При выделении предмета идёт подзагрузка данных о цене предмета, а не сразу, ибо так API может зажадничать и выдать вовсе NULL
     * 4) Пользователь нажимает "Подтвердить загрузку", после чего AJAX пингует скрипт, а тот возвращает true/false в зависимости от наличия
     *  в bot_queue записи с "N" id. Пока скрипт НЕ вернёт FALSE, мы показываем анимацию загрузки.
     * 5) Пользователю приходит обмен в стиме по заданным параметрам. Т.е. фактически, пользователь дарит боту вещи, к
     *
     * @return void
     */
    public function up()
    {
        Schema::create('steam_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('app_id');
            $table->string('class_id');
            $table->string('user_id')->index();
            $table->string('bot_steam_id');

            $table->timestamps();
        });

        /**
         * TODO: TEST!!! убрать!!!!!!!!!!!!
         */

        DB::table('steam_items')->insert(
            [
                'app_id' => '570',
                'class_id' => '237195590',
                'user_id' => '1',
                'bot_steam_id' => '76561198121640559'
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
        Schema::drop('steam_items');
    }
}