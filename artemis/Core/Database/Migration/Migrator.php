<?php


namespace Artemis\Core\Database\Migration;



use Artemis\Client\Facades\Database;
use Artemis\Core\Models\Migration as MigrationModel;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


class Migrator
{
    /**
     * Database key.
     *
     * @var string
     */
    private $db;

    /**
     * Migration Repository instance.
     *
     * @var MigrationRepository
     */
    private $migration_repository;

    /**
     * Symfony console input interface.
     *
     * @var InputInterface
     */
    private $input;

    /**
     * Symfony console output interface.
     *
     * @var OutputInterface
     */
    private $output;

    /**
     * Line seperator.
     *
     * @var string
     */
    private $seperator = '------------------------------';

    /**
     * Migrator constructor.
     *
     * @param string $db
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function __construct($db,InputInterface $input, OutputInterface $output)
    {
        $this->db = $db;
        $this->migration_repository = new MigrationRepository($this->db);
        $this->input = $input;
        $this->output = $output;
        $this->output->writeln($this->seperator);
    }

    /**
     * Performs a fresh migration.
     *
     * @return void
     */
    public function fresh()
    {
        $io = new SymfonyStyle($this->input, $this->output);
        $io->title('Start Fresh Migration');

        $this->output->writeln('Rolling back migrations.');
        $this->output->writeln($this->seperator);

        $migrations = $this->migration_repository->getAllMigrations();
        $migrations_reversed = array_reverse($migrations);

        foreach( $migrations_reversed as $migration_info ) {
            $this->performRollback($migration_info);
        }

        $this->output->writeln('Drop migration table.');
        $this->output->writeln($this->seperator);
        $this->dropMigrationTable();

        $this->output->writeln('Create migration table.');
        $this->output->writeln($this->seperator);
        $this->createMigrationTable();

        $batch = MigrationModel::on($this->db)->select('batch')->max('batch') + 1;

        foreach( $migrations as $migration_info ) {
            $this->performMigration($migration_info, $batch);
        }

        $this->output->writeln('Migration complete!');
        $this->output->writeln($this->seperator);
    }

    /**
     * Gets migration status.
     *
     * @return void
     */
    public function status()
    {
        $this->createMigrationTable();

        $table_head = ['Migration', 'Batch', 'State'];
        $table_content = $this->migration_repository->getMigrationTable();

        $io = new SymfonyStyle($this->input, $this->output);

        $io->title('Migration Status');

        $io->table($table_head, $table_content);
    }

    /**
     * Performs migration.
     *
     * @return string
     */
    public function migrate()
    {
        $this->createMigrationTable();

        $io = new SymfonyStyle($this->input, $this->output);
        $io->title('Start Migration');

        $migrations = $this->migration_repository->getNewMigrations();

        if( empty($migrations) ) {
            return 'Nothing to migrate.';
        }

        $batch = MigrationModel::on($this->db)->select('batch')->max('batch') + 1;

        foreach( $migrations as $migration_info ) {
            $this->performMigration($migration_info, $batch);
        }

        return 'Migration complete!';
    }

    /**
     * Performs a rollback.
     *
     * @return string
     */
    public function rollback()
    {
        $this->createMigrationTable();

        $io = new SymfonyStyle($this->input, $this->output);
        $io->title('Start Rollback');

        $migrations = $this->migration_repository->getLatestMigrations();

        if( empty($migrations) ) {
            return 'Nothing to rollback.';
        }

        foreach( $migrations as $migration_info ) {
           $this->performRollback($migration_info);
        }

        return 'Rollback completed!';
    }

    /**
     * Performs migration for given migrations and batch.
     *
     * @param array $migration_info
     * @param int $batch
     *
     * @return void
     */
    private function performMigration($migration_info, $batch)
    {
        $this->output->writeln("Migrating " . $migration_info['name'] . " ...");
        /* @var Migration $instance */
        $instance = container($migration_info['class']);
        $instance->up();

        /* @var MigrationModel $migration_model */
        $migration_model = MigrationModel::on($this->db)
            ->where('migration', $migration_info['name'])
            ->first();

        if( is_null($migration_model) ) {
            MigrationModel::on($this->db)->create([
                'migration' => $migration_info['name'],
                'batch' => $batch,
                'state' => 1
            ]);

            $this->output->writeln("Migration " . $migration_info['name'] . " complete.");
            $this->output->writeln($this->seperator);
            return;
        }

        if( !$migration_model->state) {
            $migration_model->update(['state' => 1]);
            $this->output->writeln("Migration " . $migration_info['name'] . " complete.");
            $this->output->writeln($this->seperator);
        }
    }

    /**
     * Performs rollback on given migrations.
     *
     * @param $migration_info
     *
     * @return void
     */
    private function performRollback($migration_info)
    {
        $this->output->writeln("Rolling back " . $migration_info['name'] . " ...");
        /* @var Migration $instance */
        $instance = container($migration_info['class']);
        $instance->down();

        /* @var MigrationModel $migration_model */
        $migration_model = MigrationModel::on($this->db)
            ->where('migration', $migration_info['name'])
            ->first();

        $migration_model->update(['state' => 0]);
        $this->output->writeln("Rollback " . $migration_info['name'] . " complete.");
        $this->output->writeln($this->seperator);
    }

    /**
     * Creates the migration table to track migrations.
     *
     * @return void
     */
    private function createMigrationTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS artemis_migrations (
            id int(8) NOT NULL AUTO_INCREMENT,
            migration varchar(255) NOT NULL,
            batch int(8) NOT NULL,
            state tinyint(1) NOT NULL,
            PRIMARY KEY (id)
        );";

        Database::connect($this->db)->unprepared($sql);
    }

    /**
     * Drops migration tracking table.
     *
     * @return void
     */
    private function dropMigrationTable()
    {
        $sql = "DROP TABLE IF EXISTS artemis_migrations;";

        Database::connect($this->db)->unprepared($sql);
    }
}