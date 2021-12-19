

namespace {{ $controller_namespace }};


class {{ $controller_name }}
{
\tab/**
\tab\s* {{ $controller_name }} Constructor.
\tab\s*
\tab\s* @param void
\tab\s*/
\tabpublic function __construct()
\tab{
\tab\tab// Constructor content
\tab}

@if( !empty($controller_action) )
\tabpublic function someFunction()
\tab{
\tab\tab// Action Content
\tab}
@endif

@if( $is_resource )
\tab/**
\tab\s* Shows all Entries
\tab\s*/
\tabpublic function index()
\tab{

\tab}

\tab/**
\tab\s* Shows form to create a new entry
\tab\s*/
\tabpublic function new()
\tab{

\tab}

\tab/**
\tab\s* Creates a new entry
\tab\s*/
\tabpublic function create()
\tab{

\tab}

\tab/**
\tab\s* Shows a single entry
\tab\s*/
\tabpublic function show()
\tab{

\tab}

\tab/**
\tab\s* Shows to form to edit a given entry
\tab\s*/
\tabpublic function edit()
\tab{

\tab}

\tab/**
\tab\s* Updates a given entry
\tab\s*/
\tabpublic function update()
\tab{

\tab}

\tab/**
\tab\s* Deletes a given entry
\tab\s*/
\tabpublic function delete()
\tab{

\tab}
@endif
}