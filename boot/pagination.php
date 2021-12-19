<?php

/*
/ -----------------------------------------------------------------------------
/ Initiate Page Resolver
/ -----------------------------------------------------------------------------
/
/ Sets the page resolver based on $_GET variables to let the paginator know
/ what is the current page.
/
*/

\Illuminate\Pagination\Paginator::currentPageResolver(function ($pageName) {
    return empty($_GET[$pageName]) ? 1 : $_GET[$pageName];
});

/*
/ -----------------------------------------------------------------------------
/ Initiate Path Resolver
/ -----------------------------------------------------------------------------
/
/ Sets the url path resolver so that correct url's are generated for each
/ page link.
/
*/

\Illuminate\Pagination\Paginator::currentPathResolver(function() {
    return parse_url(container('request')->getRequestURL())['path'];
});