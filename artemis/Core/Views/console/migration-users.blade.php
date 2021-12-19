

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
\tab\tabif( !Schema::on('{{ $database }}')->hasTable('users') ) {
\tab\tab\tabSchema::on('{{ $database }}')->create('users', function(Blueprint $table) {
\tab\tab\tab\tab$table->id('ID');
\tab\tab\tab\tab$table->string('username');
\tab\tab\tab\tab$table->string('password_hash');
\tab\tab\tab\tab$table->string('email');
\tab\tab\tab\tab$table->boolean('active')->default(1);
\tab\tab\tab\tab$table->boolean('admin')->default(0);
\tab\tab\tab\tab$table->boolean('banned')->default(0);
\tab\tab\tab\tab$table->boolean('deleted')->default(0);
\tab\tab\tab\tab$table->string('reset_key');
\tab\tab\tab\tab$table->timestamp('reset_expires')->useCurrent()->useCurrentOnUpdate();
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
\tab\tabif( Schema::on('{{ $database }}')->hasTable('users') ) {
\tab\tab\tabSchema::on('{{ $database }}')->drop('users');
\tab\tab}
\tab}
}