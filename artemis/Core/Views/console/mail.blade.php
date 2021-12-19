

namespace {{ $mail_namespace }};


use Artemis\Core\Mail\Mailable;


class {{ $mail_name }} extends Mailable
{
\tab/**
\tab\s* {{ $mail_name }} Constructor.
\tab\s*/
\tabpublic function __construct()
\tab{

\tab}

\tab/**
\tab\s* @inheritDoc
\tab\s*/
\tabpublic function build() : string
\tab{
\tab\tab// TODO: Implement build() method.
\tab}
}