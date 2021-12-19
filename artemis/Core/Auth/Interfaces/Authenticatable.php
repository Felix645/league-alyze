<?php


namespace Artemis\Core\Auth\Interfaces;


interface Authenticatable
{
    /**
     * Returns a user object by given identifier (e.g. username) and database key.
     *
     * @param string $input
     * @param string $database
     *
     * @return Authenticatable|null
     */
    public static function findByIdentifier($input, $database);

    /**
     * Returns a user object by given primary key and database key.
     *
     * @param $input
     * @param string $database
     *
     * @return Authenticatable|null
     */
    public static function findByPrimaryKey($input, $database);

    /**
     * Gets the column name of the identifier (e.g. 'username' or 'email).
     *
     * @return string
     */
    public static function getIdentifierName();

    /**
     * Gets the column name of the primary key (e.g. 'id').
     *
     * @return string
     */
    public static function getPrimaryKeyName();

    /**
     * Gets the identifer value (e.g. 'test.tester' for 'username')
     *
     * @return string
     */
    public function getIdentfier();

    /**
     * Gets the primary key of the user
     *
     * @return mixed
     */
    public function getPrimaryKey();

    /**
     * Gets the password hash of the user.
     * Hash had to be made via the PHP's password_hash function.
     *
     * @return string
     */
    public function getPassword();
}