<?php


namespace App\Http\Controllers;


use Artemis\Core\Template\View;


class ExampleController
{
    /**
     * Renders the example view
     *
     * @return View
     */
    public function index() : View
    {
        $view_data = array(
            'share' => 'overwriting share data'
        );

        return view('example', $view_data);
    }
}
