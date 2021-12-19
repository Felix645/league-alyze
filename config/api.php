<?php


return [
    /**
     * Prefix for all routes defined in resource/Routes/api.php
     *
     * @var string
     */
    'route_prefix' => 'api',


    /**
     * API Response message for 'notFound' responses
     *
     * @var string
     */
    'notFoundMessage' => 'Die angeforderte Ressource konnte nicht gefunden werden.',


    /**
     * API Response message for 'unauthorized' responses
     *
     * @var string
     */
    'unauthorizedMessage' => 'Die Anfrage konnte nicht authorisiert werden.',


    /**
     * API Response message for 'forbidden' responses
     *
     * @var string
     */
    'forbiddenMessage' => 'Der Zugriff auf diese Ressource ist nicht erlaubt.',


    /**
     * API Response message for 'badRequest' responses
     *
     * @var string
     */
    'badRequestMessage' => 'Für diese Anfrage fehlen eine oder mehrere Parameter.'
];