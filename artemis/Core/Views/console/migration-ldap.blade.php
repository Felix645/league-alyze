

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
\tab\tabif( !Schema::on('{{ $database }}')->hasTable('ldap_settings') ) {
\tab\tab\tabSchema::on('{{ $database }}')->create('ldap_settings', function(Blueprint $table) {
\tab\tab\tab\tab$table->id();
\tab\tab\tab\tab$table->string('name');
\tab\tab\tab\tab$table->string('sys_auth_mech_ldap_server');
\tab\tab\tab\tab$table->string('sys_auth_mech_ldap_dn');
\tab\tab\tab\tab$table->string('sys_auth_mech_ldap_pwd');
\tab\tab\tab\tab$table->string('sys_auth_mech_ldap_basedn');
\tab\tab\tab\tab$table->string('domain');
\tab\tab\tab\tab$table->dateTime('timestamp_insert')->useCurrent();
\tab\tab\tab\tab$table->timestamp('timestamp_lastchange')->useCurrent()->useCurrentOnUpdate();
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
\tab\tabif( Schema::on('{{ $database }}')->hasTable('ldap_settings') ) {
\tab\tab\tabSchema::on('{{ $database }}')->drop('ldap_settings');
\tab\tab}
\tab}
}