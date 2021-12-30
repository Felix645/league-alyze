<?php


use Artemis\Core\Database\Migration\Migration;
use Artemis\Client\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;


class DbAddIconPathColumnToRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() : void
    {
        Schema::on('db')->table('roles', function(Blueprint $table) {
            $table->string('icon_path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() : void
    {
        Schema::on('db')->dropColumns('roles', 'icon_path');
    }
}