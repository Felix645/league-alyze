<?php


namespace App\Http\Controllers;


use Artemis\Core\Template\View;


class ExampleController
{
    /**
     * Renders the example view
     *
     * @return
     */
    public function index()
    {
        require ROOT_PATH . 'database/migrations/db/2021_12_19_152252-db_create_champions_table.php';

        $migration = new \DbCreateChampionsTable();

        $migration->up();

        return 'done';
    }
}
