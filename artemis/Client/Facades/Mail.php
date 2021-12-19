<?php


namespace Artemis\Client\Facades;


use Artemis\Core\Mail\Mailable;
use Artemis\Core\Mail\Mailer;


/**
 * Class Mail
 * @package Artemis\Client\Facades
 *
 * @method static bool send(Mailable $mailable)
 * @method static Mailer from($address, string $name = '')
 * @method static Mailer to(mixed $address, string $name = '')
 * @method static Mailer cc(mixed $address, string $name = '')
 * @method static Mailer bcc(mixed $address, string $name = '')
 * @method static string getErrorMessage()
 *
 * @uses Mailer::send()
 * @uses Mailer::from()
 * @uses Mailer::to()
 * @uses Mailer::cc()
 * @uses Mailer::bcc()
 * @uses Mailer::getErrorMessage()
 */
class Mail extends Facade
{
    /**
     * @inheritDoc
     */
    protected static function getAccessor()
    {
        return 'mail';
    }
}