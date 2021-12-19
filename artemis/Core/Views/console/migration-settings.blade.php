

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
\tab\tabif( !Schema::on('{{ $database }}')->hasTable('settings') ) {
\tab\tab\tabSchema::on('{{ $database }}')->create('settings', function(Blueprint $table) {
\tab\tab\tab\tab$table->increments('ID');
\tab\tab\tab\tab$table->string('key');
\tab\tab\tab\tab$table->longText('value');
\tab\tab\tab});
\tab\tab}
\tab}

\tab/**
\tab\s* Reverse the migrations.
\tab\s*
\tab\s* @return void
\tab\s*/
\tabpublic function down() : void
\tab{
\tab\tabif( Schema::on('{{ $database }}')->hasTable('settings') ) {
\tab\tab\tabSchema::on('{{ $database }}')->drop('settings');
\tab\tab}
\tab}
}