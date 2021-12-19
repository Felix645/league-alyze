<?php


namespace Artemis\Core\Mail;


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class Mailer
{
    /**
     * PHPMailer object
     * 
     * @var PHPMailer
     */
    private $mailer;

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
     * Will be filled when an exception occurs
     * 
     * @var string
     */
    private $error_message = '';

    /**
     * Mailer Constructor.
     */
    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
    }

    /**
     * Sends an email with the given Mailable object
     * 
     * @param Mailable $mailable
     * 
     * @return bool true on success, false on failure
     */
    public function send(Mailable $mailable)
    {
        try {
            $this->isSMTP();
            $this->mailer->SMTPDebug = env('MAIL_SMTP_DEBUG');
            $this->mailer->Host = env('MAIL_HOST');
            $this->mailer->SMTPAuth = env('MAIL_SMTP_AUTH');
            $this->addSMTPAuth();
            $this->addSMTPSecure();
            $this->mailer->SMTPAutoTLS = env('MAIL_SMTP_AUTOTLS');
            $this->mailer->Port = env('MAIL_PORT');
            $this->addPHPMailerInfo($mailable);
            $this->mailer->isHTML($mailable->getHtml());
            $this->mailer->Subject = $mailable->getSubject();
            $this->mailer->Body = $mailable->build();
            $this->mailer->AltBody = $mailable->getBodyAlt();
            $this->mailer->CharSet = 'UTF-8';
            $this->mailer->send();

            return true;
        } catch( Exception $e) {
            $this->error_message = $this->mailer->ErrorInfo;

            return false;
        }    
    }

    /**
     * Adds one or more 'from' addresses
     * 
     * @param mixed $address
     * @param string $name
     * 
     * @return Mailer
     */
    public function from($address, $name = '')
    {
        $this->addAddress('from', $address, $name);
        return $this;
    }

    /**
     * Adds one or more 'to' addresses
     * 
     * @param string|array $address
     * @param string $name
     * 
     * @return Mailer
     */
    public function to($address, $name = '')
    {
        $this->addAddress('to', $address, $name);
        return $this;
    }

    /**
     * Adds one or more 'cc' addresses
     * 
     * @param string|array $address
     * @param string $name
     * 
     * @return Mailer
     */
    public function cc($address, $name = '')
    {
        $this->addAddress('cc', $address, $name);
        return $this;
    }

    /**
     * Adds one or more 'bcc' addresses
     * 
     * @param string|array $address
     * @param string $name
     * 
     * @return Mailer
     */
    public function bcc($address, $name = '')
    {
        $this->addAddress('bcc', $address, $name);
        return $this;
    }

    /**
     * Adds the mail infos from Mailable object to PHPMailer
     * 
     * @param Mailable $mailable
     * @throws Exception
     * 
     * @return void
     */
    private function addPHPMailerInfo($mailable)
    {
        // Adds from addresses to phpmailer
        if( empty($this->from) )
            $this->addFromMailer($mailable->getFrom());
        else
            $this->addFromMailer($this->from);

        // Adds to addresses to phpmailer
        if( empty($this->to) )
            $this->addToMailer($mailable->getTo());
        else
            $this->addToMailer($this->to);

        // Adds cc addresses to phpmailer
        if( empty($this->cc) )
            $this->addCCMailer($mailable->getCc());
        else
            $this->addCCMailer($this->cc);
        
        // Adds bcc addresses to phpmailer
        if( empty($this->bcc) )
            $this->addBCCMailer($mailable->getBcc());
        else
            $this->addBCCMailer($this->bcc);

        // Adds attachments to phpmailer
        $this->addAttachmentMailer($mailable->getAttachments());
    }

    /**
     * Adds an adress to given property
     * 
     * @param string $property
     * @param string|array $address_input
     * @param string $name
     * 
     * @return void
     */
    private function addAddress($property, $address_input, $name = '')
    {
        if( !empty($address_input) ) {
            if( is_string($address_input) ) {
                $this->{$property}[$address_input] = $name;
                return;
            }
    
            if( is_array($address_input) ) {
                foreach($address_input as $to_address => $to_name) {
                    $this->$property[$to_address] = $to_name;
                }
                return;
            }
        }  

        // TODO: Throw exception
    }

    /**
     * Adds 'from' addresses to PHPMailer
     * 
     * @param array $from
     * @throws Exception
     * 
     * @return void
     */
    private function addFromMailer($from)
    {
        if( $this->check($from) ) {
            foreach( $from as $from_address => $from_name ) {
                if( empty($from_name) )
                    $this->mailer->setFrom($from_address);
                else
                    $this->mailer->setFrom($from_address, $from_name);
            }
        }   
    }

    /**
     * Adds 'to' addresses to PHPMailer
     * 
     * @param array $to
     * @throws Exception
     * 
     * @return void
     */
    private function addToMailer($to)
    {
        if( $this->check($to) ) {
            foreach( $to as $to_address => $to_name ) {
                if( empty($to_name) )
                    $this->mailer->addAddress($to_address);
                else
                    $this->mailer->addAddress($to_address, $to_name);
            }
        }   
    }

    /**
     * Adds 'CC' addresses to PHPMailer
     * 
     * @param array $cc
     * @throws Exception
     * 
     * @return void
     */
    private function addCCMailer($cc)
    {
        if( $this->check($cc) ) {
            foreach( $cc as $cc_address => $cc_name ) {
                if( empty($cc_name) )
                    $this->mailer->addCC($cc_address);
                else
                    $this->mailer->addCC($cc_address, $cc_name);
            }
        }    
    }

    /**
     * Adds 'BCC' addresses to PHPMailer
     * 
     * @param array $bcc
     * @throws Exception
     * 
     * @return void
     */
    private function addBCCMailer($bcc)
    {
        if( $this->check($bcc) ) {
            foreach( $bcc as $bcc_address => $bcc_name ) {
                if( empty($bcc_name) )
                    $this->mailer->addBCC($bcc_address);
                else
                    $this->mailer->addBCC($bcc_address, $bcc_name);
            }
        }     
    }

    /**
     * Adds attachments to PHPMailer
     * 
     * @param array $attachments
     * @throws Exception
     * 
     * @return void
     */
    private function addAttachmentMailer($attachments)
    {
        if( $this->check($attachments) ) {
            foreach( $attachments as $attachment_path ) {
                $this->mailer->addAttachment($attachment_path);
            }
        }    
    }

    /**
     * Sets the PHPMailer SMTP method depending on env variable
     * 
     * @return void
     */
    private function isSMTP()
    {
        if( env('MAIL_SMTP') )
            $this->mailer->isSMTP();
    }

    /**
     * Adds PHPMailer Auth info depending on env variable
     * 
     * @return void
     */
    private function addSMTPAuth()
    {
        if( env('MAIL_SMTP_AUTH') ) {
            $this->mailer->Username = env('MAIL_SMTP_USER');
            $this->mailer->Password = env('MAIL_SMTP_PASS');
        }
    }

    /**
     * Adds PHPMailer encryption depending on env variable
     * 
     * @return void
     */
    private function addSMTPSecure()
    {
        if( env('MAIL_SMTP_ENCRYPT') ) {
            switch( env('MAIL_SMTP_ENCRYPT_METHOD') ) {
                case 'tls':
                    $method = PHPMailer::ENCRYPTION_STARTTLS;
                break;

                default:
                    $method = PHPMailer::ENCRYPTION_SMTPS;
                break;
            }
            $this->mailer->SMTPSecure = $method;
        }
    }

    /**
     * Checks if given array is not empty
     * 
     * @param array $array
     * 
     * @return bool
     */
    private function check($array)
    {
       return !empty($array);
    }

    /**
     * Gets the error message if sending the mail was not successful
     * 
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->error_message;    
    }
}
