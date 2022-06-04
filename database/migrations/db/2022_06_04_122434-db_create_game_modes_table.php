<?php


use Artemis\Core\Database\Migration\Migration;
use Artemis\Client\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;


class DbCreateGameModesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() : void
    {
        if( !Schema::on('db')->hasTable('game_modes') ) {
            Schema::on('db')->create('game_modes', function(Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->string('title');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() : void
    {


        if( Schema::on('db')->hasTable('game_modes') ) {
            Schema::on('db')->drop('game_modes');
        }
    }
}