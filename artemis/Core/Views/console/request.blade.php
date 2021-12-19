

namespace {{ $request_namespace }};


use Artemis\Client\Http\FormRequest;


class {{ $request_name }} extends FormRequest
{
\tab/**
\tab\s* @inheritDoc
\tab\s*/
\tabprotected function rules() : array
\tab{
\tab\tab// TODO: Implement rules() method.
\tab}

\tab/**
\tab\s* @inheritDoc
\tab\s*/
\tabprotected function fails() : void
\tab{
\tab\tab// TODO: Implement fails() method.
\tab}
}
