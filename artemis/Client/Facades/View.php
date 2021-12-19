<?php


namespace Artemis\Client\Facades;


/**
 * Class View
 * @package Artemis\Client\Facades
 *
 * @method static void share(array $share_data)
 * @method static \Artemis\Core\Template\View setView(string $view)
 * @method static \Artemis\Core\Template\View setData(array $data)
 * @method static string render()
 *
 * @uses \Artemis\Core\Template\View::share()
 * @uses \Artemis\Core\Template\View::setView()
 * @uses \Artemis\Core\Template\View::setData()
 * @uses \Artemis\Core\Template\View::render()
 */
class View extends Facade
{
    /**
     * @inheritDoc
     */
    protected static function getAccessor()
    {
        return 'view';
    }
}