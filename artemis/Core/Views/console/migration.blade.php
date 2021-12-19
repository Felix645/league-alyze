

use Artemis\Core\Database\Migration\Migration;
use Artemis\Client\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;


class {{ $class_name }} extends Migration
{
\tab/**
\tab\s* Run the migrations.
\tab\s*
\tab\s* @return void
\tab\s*/
\tabpublic function up() : void
\tab{
\tab\tabSchema::on('{{ $database }}');
\tab}

\tab/**
\tab\s* Reverse the migrations.
\tab\s*
\tab\s* @return void
\tab\s*/
\tabpublic function down() : void
\tab{
\tab\tabSchema::on('{{ $database }}');
\tab}
}