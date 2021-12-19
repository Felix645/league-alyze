<?php


use Artemis\Core\Database\Migration\Migration;
use Artemis\Client\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;


class DbCreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() : void
    {
        Schema::on('db')->create('matches', function(Blueprint $table) {
            $table->id();
            $table->boolean('is_win');
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('played_as');
            $table->unsignedBigInteger('played_against');
            $table->unsignedInteger('kills');
            $table->unsignedInteger('deaths');
            $table->unsignedInteger('assists');
            $table->unsignedInteger('creep_score');
            $table->unsignedInteger('minutes');
            $table->unsignedInteger('seconds');
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('played_as')->references('id')->on('champions');
            $table->foreign('played_against')->references('id')->on('champions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() : void
    {
        Schema::on('db')->drop('matches');
    }
}