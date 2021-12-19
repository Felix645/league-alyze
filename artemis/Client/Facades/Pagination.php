<?php


namespace Artemis\Client\Facades;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;


/**
 * Class Pagination
 * @package Artemis\Client\Facades
 *
 * @method static \Artemis\Core\Pagination\Paginator input(LengthAwarePaginator $pagination) Sets the pagination input
 * @method static \Artemis\Core\Pagination\Paginator view(string $view) Sets a custom view
 *
 * @uses \Artemis\Core\Pagination\Paginator::input()
 * @uses \Artemis\Core\Pagination\Paginator::view()
 */
class Pagination extends Facade
{
    /**
     * @inheritDoc
     */
    protected static function getAccessor()
    {
        return 'pagination';
    }
}