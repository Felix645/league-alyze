<?php


namespace Artemis\Core\Auth\Traits;


trait hasAuthenticationContract
{
    /**
     * Column name of the primary key
     *
     * @var string
     */
    protected static $primary_key_field = 'ID';

    /**
     * Column name of the user identifier
     *
     * @var string
     */
    protected static $identifier = 'username';

    /**
     * Column name of the password hash
     *
     * @var string
     */
    protected $password_field = 'password_hash';

    /**
     * @inheritDoc
     */
    public static function findByIdentifier($input, $database)
    {
        $user = self::on($database)->where(self::getIdentifierName(), $input)->first();

        if( !$user )
            return null;

        return $user;
    }

    /**
     * @inheritDoc
     */
    public static function findByPrimaryKey($input, $database)
    {
        $user = self::on($database)->with(['user_mech', 'token'])->find($input);

        if( !$user )
            return null;

        return $user;
    }

    /**
     * @inheritDoc
     */
    public static function getIdentifierName()
    {
        return self::$identifier;
    }

    /**
     * @inheritDoc
     */
    public static function getPrimaryKeyName()
    {
        return self::$primary_key_field;
    }

    /**
     * @inheritDoc
     */
    public function getIdentfier()
    {
        return $this->{self::getIdentifierName()};
    }

    /**
     * @inheritDoc
     */
    public function getPrimaryKey()
    {
        return $this->{$this->primaryKey};
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->{$this->password_field};
    }
}