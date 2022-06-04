<?php


use Artemis\Client\Facades\DB;
use Artemis\Core\Database\Migration\Migration;
use Artemis\Client\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;


class DbAddGameModeToMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() : void
    {
        if( !Schema::on('db')->hasColumn('matches', 'game_mode_id') ) {
            Schema::on('db')->table('matches', function(Blueprint $table) {
                $table->unsignedBigInteger('game_mode_id')->default(null)->after('id');

                $table->foreign('game_mode_id')->references('id')->on('game_modes');
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
        if( Schema::on('db')->hasColumn('matches', 'game_mode_id') ) {
            DB::on('db')->statement('SET FOREIGN_KEY_CHECKS=0;');

            Schema::on('db')->table('matches', function(Blueprint $table) {
                $table->dropForeign(['game_mode_id']);
                $table->dropColumn('game_mode_id');
            });

            DB::on('db')->statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}