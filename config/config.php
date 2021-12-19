<?php

return [
    /**
     * Specifies if the application should be protected from csrf attacks
     * IMPORTANT:   If this is set to true, every POST, PUT and DELETE request
     *              MUST contain a csrf token via the '@csrf' template directive
     *
     * Default: false
     *
     * @var bool
     */
    'csrf_protection' => false,


    /**
     * Timespan how long a csrf token is valid, relative to the timestamp it was created.
     * Value is equal to a date modifier
     * eg. '+1 hour', '+1 days', etc.
     *
     * Default: '+1 hour'
     *
     * @var string
     */
    'csrf_expiration_time' => '+1 hour',


    /**
     * Here legacy redirect can be enabled.
     *
     * @var bool
     */
    'legacy_redirects' => false,


    /**
     * Specifies from which databases the settings table should be loaded
     * If the array is empty no settings will be loaded
     * Syntax: ['db_key_1', 'db_key_2', ...]
     *
     * @var array
     */
    'db_settings' => [],


    /**
     * Identifier for the session authentication user id
     *
     * @var string
     */
    'auth_session_id' => 'sn_auth_uid',


    /**
     * Identifier for the session authentication user token
     *
     * @var string
     */
    'auth_session_token' => 'sn_auth_utoken',


    /**
     * Identifier for the curl authentication user id
     *
     * @var string
     */
    'auth_curl_id' => 'curl_auth_uid',


    /**
     * Identifier for the curl authentication user token
     *
     * @var string
     */
    'auth_curl_token' => 'curl_auth_utoken'
];