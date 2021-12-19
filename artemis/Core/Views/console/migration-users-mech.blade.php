

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
\tab\tab$has_mech_user = Schema::on('{{ $database }}')->hasTable('users_auth_mech');
\tab\tab$has_users = Schema::on('{{ $database }}')->hasTable('users');
\tab\tab$has_ldap = Schema::on('{{ $database }}')->hasTable('ldap_settings');

\tab\tabif( !$has_mech_user && $has_users && $has_ldap ) {
\tab\tab\tabSchema::on('{{ $database }}')->create('users_auth_mech', function(Blueprint $table) {
\tab\tab\tab\tab$table->id();
\tab\tab\tab\tab$table->unsignedBigInteger('users_id')->unique();
\tab\tab\tab\tab$table->boolean('mechanism_id')->default(0);
\tab\tab\tab\tab$table->unsignedBigInteger('ldap_settings_id')->nullable()->default(null);
\tab\tab\tab\tab$table->unsignedInteger('status')->default(101);
\tab\tab\tab\tab$table->boolean('active')->default(1);
\tab\tab\tab\tab$table->dateTime('timestamp_insert')->useCurrent();
\tab\tab\tab\tab$table->timestamp('timestamp_lastchange')->useCurrent()->useCurrentOnUpdate();
\tab\tab\tab\tab$table->foreign('users_id')->references('ID')->on('users');
\tab\tab\tab\tab$table->foreign('ldap_settings_id')->references('id')->on('ldap_settings');
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
\tab\tabif( Schema::on('{{ $database }}')->hasTable('users_auth_mech') ) {
\tab\tab\tabSchema::on('{{ $database }}')->drop('users_auth_mech');
\tab\tab}
\tab}
}