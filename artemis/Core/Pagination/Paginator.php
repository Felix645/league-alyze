<?php


namespace Artemis\Core\Pagination;


use Artemis\Support\Arr;
use Artemis\Resource\Extensions\CustomBladeExtension;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class Paginator
{
    /**
     * Path to the core views
     *
     * @var string
     */
    private $core_views = ROOT_PATH . 'artemis/Core/Views';

    /**
     * Path to the app views
     *
     * @var string
     */
    private $app_views = ROOT_PATH . 'app/Views';

    /**
     * Path to the view cache
     *
     * @var string
     */
    private $cache = ROOT_PATH . 'cache/views';

    /**
     * Custom view
     *
     * @var null
     */
    private $custom_view = null;

    /**
     * Pagination input
     *
     * @var LengthAwarePaginator
     */
    private $pagination;

    /**
     * Number of pages before current page.
     *
     * @var int
     */
    private $pages_before;

    /**
     * Array of info about each page before current page.
     *
     * @var array
     */
    private $page_links_before;

    /**
     * Number of pages after current page.
     *
     * @var int
     */
    private $pages_after;

    /**
     * Array of info about each page after current page.
     *
     * @var array
     */
    private $page_links_after;

    /**
     * Sets the pagination input.
     *
     * @param LengthAwarePaginator $pagination
     *
     * @return $this
     */
    public function input(LengthAwarePaginator $pagination)
    {
        $this->pagination = $pagination;

        return $this;
    }

    /**
     * Sets a custom view.
     *
     * @param string $view
     *
     * @return $this
     */
    public function view($view)
    {
        $this->custom_view = $view;
        return $this;
    }

    /**
     * Gets the pagination data.
     *
     * @return LengthAwarePaginator
     */
    public function data()
    {
        return $this->pagination;
    }

    /**
     * Gets the pagination links.
     *
     * @return string
     */
    public function links()
    {
        $this->pagesBefore();
        $this->pagesAfter();
        $this->checkPages();
        $this->page_links_after = Arr::reverse($this->page_links_after);

        $pagination_data = [
            'pagination' => $this->pagination,
            'pages_before' => $this->page_links_before,
            'pages_after' => $this->page_links_after
        ];

        try {
            if( !$this->custom_view ) {
                $blade = new CustomBladeExtension($this->core_views, $this->cache);
                return $blade->run('pagination.links-core', $pagination_data);
            }

            $blade = new CustomBladeExtension($this->app_views, $this->cache);
            return $blade->run($this->custom_view, $pagination_data);
        } catch(\Throwable $e) {
            report($e);
            exit;
        }
    }

    /**
     * Calculates the pages before current page.
     *
     * @return void
     */
    private function pagesBefore()
    {
        $onEachSide = $this->pagination->onEachSide;

        $pagenumber = $this->pagination->currentPage() - $onEachSide;
        while( $pagenumber < 1 ) {
            $pagenumber++;
        }

        $pages = [];
        while( $pagenumber < $this->pagination->currentPage() ) {
            $pages[] = ['link' => $this->pagination->url($pagenumber), 'page' => $pagenumber ];

            $pagenumber++;
        }

        $this->pages_before = count($pages);
        $this->page_links_before = $pages;
    }

    /**
     * Calculates the pages after current page.
     *
     * @return void
     */
    private function pagesAfter()
    {
        $onEachSide = $this->pagination->onEachSide;

        $pagenumber = $this->pagination->currentPage() + $onEachSide;
        while( $pagenumber > $this->pagination->lastPage() ) {
            $pagenumber--;
        }

        $pages = [];
        while( $pagenumber > $this->pagination->currentPage() ) {
            $pages[] = ['link' => $this->pagination->url($pagenumber), 'page' => $pagenumber ];

            $pagenumber--;
        }

        $this->pages_after = count($pages);
        $this->page_links_after = $pages;
    }

    /**
     * Checks if any pages are missing on either side and appends additional ones to the other side.
     *
     * @return void
     */
    private function checkPages()
    {
        $page_difference = abs($this->pages_before - $this->pages_after);

        if( 0 === $page_difference ) {
            return;
        }

        if( $this->pages_before > $this->pages_after ) {
            while( $page_difference > 0 ) {
                $first = $this->page_links_before[0]['page'];
                $add_page_number = $first - 1;

                if( $add_page_number < 1 ) {
                    break;
                }

                $add_page = ['link' => $this->pagination->url($add_page_number), 'page' => $add_page_number ];
                array_unshift($this->page_links_before, $add_page);

                $page_difference--;
            }

            return;
        }

        while( $page_difference > 0 ) {
            $last = $this->page_links_after[0]['page'];
            $add_page_number = $last + 1;

            if( $add_page_number > $this->pagination->lastPage() ) {
                break;
            }

            $add_page = ['link' => $this->pagination->url($add_page_number), 'page' => $add_page_number ];
            array_unshift($this->page_links_after, $add_page);

            $page_difference--;
        }
    }
}