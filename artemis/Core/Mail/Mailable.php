<?php


namespace Artemis\Core\Mail;


abstract class Mailable
{
    /**
     * 'From' adresses
     * 
     * @var array
     */
    private $from = [];

    /**
     * 'To' adresses
     * 
     * @var array
     */
    private $to = [];

    /**
     * 'CC' adresses
     * 
     * @var array
     */
    private $cc = [];

    /**
     * 'BCC' adresses
     * 
     * @var array
     */
    private $bcc = [];

    /**
     * File attachments
     * 
     * @var array
     */
    private $attachments = [];

    /**
     * Is the mail body html?
     * 
     * @var bool
     */
    private $html = true;

    /**
     * Mail subject
     * 
     * @var string
     */
    private $subject = '';

    /**
     * Alternative mail body (if html is true)
     * 
     * @var string
     */
    private $bodyAlt = '';

    /**
     * Builds the mail body
     * 
     * @return string
     */
    abstract public function build();

    /**
     * Sets a 'from' address
     * 
     * @param array|string $from
     * @param string $name
     * 
     * @return Mailable
     */
    final protected function setFrom($from, $name = '')
    {
        if( is_string($from) )
            $this->from[$from] = $name;

        if( is_array($from) )
            $this->from = $from;

        return $this;
    }

    /**
     * Sets a 'to' address
     * 
     * @param array|string $to
     * @param string $name
     * @return Mailable
     */
    final protected function setTo($to, string $name = '')
    {
        if( is_string($to) )
            $this->to[$to] = $name;

        if( is_array($to) )
            $this->to = $to;

        return $this;
    }

    /**
     * Sets a 'cc' address
     * 
     * @param array|string $cc
     * @param string $name
     * 
     * @return Mailable
     */
    final protected function setCc($cc, string $name = '')
    {
        if( is_string($cc) )
            $this->cc[$cc] = $name;

        if( is_array($cc) )
            $this->cc = $cc;

        return $this;
    }

    /**
     * Sets a 'bcc' address
     * 
     * @param array|string $bcc
     * @param string $name
     * 
     * @return Mailable
     */
    final protected function setBcc($bcc, string $name = '')
    {
        if( is_string($bcc) )
            $this->bcc[$bcc] = $name;

        if( is_array($bcc) )
            $this->bcc = $bcc;

        return $this;
    }

    /**
     * Sets an attachment
     * 
     * @param string|array $attachments
     * 
     * @return Mailable
     */
    final protected function setAttachments($attachments)
    {
        if( is_string($attachments) )
            $this->attachments[] = $attachments;

        if( is_array($attachments) )
            $this->attachments = $attachments;

        return $this;
    }

    /**
     * Sets if the mail body is html or not
     * 
     * @param bool $html
     * 
     * @return Mailable
     */
    final protected function setHtml(bool $html)
    {
        $this->html = $html;
        return $this;
    }

    /**
     * Sets the mail subject
     * 
     * @param string $subject
     * 
     * @return Mailable
     */
    final protected function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * Sets an alternative mail body if html cant be interpreted
     * 
     * @param string $bodyAlt
     * 
     * @return Mailable
     */
    final protected function setBodyAlt($bodyAlt)
    {
        $this->bodyAlt = $bodyAlt;
        return $this;
    }

    /**
     * Get the 'from' adresses
     * 
     * @return array
     */
    final public function getFrom()
    {
        return $this->from;
    }

    /**
     * Get the 'to' adresses
     *
     * @return array
     */
    final public function getTo()
    {
        return $this->to;
    }

    /**
     * Get the 'cc' adresses
     * 
     * @return array
     */
    final public function getCc()
    {
        return $this->cc;
    }

    /**
     * Get the 'bcc' adresses
     * 
     * @return array
     */
    final public function getBcc()
    {
        return $this->bcc;
    }

    /**
     * Get file attachments
     * 
     * @return array
     */
    final public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * Is the mail body html?
     * 
     * @return bool
     */
    final public function getHtml()
    {
        return $this->html;
    }

    /**
     * Get mail subject
     * 
     * @return string
     */
    final public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Get alternative mail body (if html is true)
     * 
     * @return string
     */
    final public function getBodyAlt()
    {
        return $this->bodyAlt;
    }
}

