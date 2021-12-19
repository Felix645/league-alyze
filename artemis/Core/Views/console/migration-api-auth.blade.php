

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
\tab\tabif( !Schema::on('{{ $database }}')->hasTable('api_auth') && Schema::on('{{ $database }}')->hasTable('users') ) {
\tab\tab\tabSchema::on('{{ $database }}')->create('api_auth', function(Blueprint $table) {
\tab\tab\tab\tab$table->id();
\tab\tab\tab\tab$table->unsignedBigInteger('id_users');
\tab\tab\tab\tab$table->string('token');
\tab\tab\tab\tab$table->dateTime('token_expires');
\tab\tab\tab\tab$table->timestamp('timestamp_lastchange')->useCurrent()->useCurrentOnUpdate();
\tab\tab\tab\tab$table->foreign('id_users')->references('ID')->on('users');
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
\tab\tabif( Schema::on('{{ $database }}')->hasTable('api_auth') ) {
\tab\tab\tabSchema::on('{{ $database }}')->drop('api_auth');
\tab\tab}
\tab}
}