<?php


namespace Artemis\Core\Database\Migration;


use Artemis\Core\Models\Migration as MigrationModel;
use Artemis\Support\FileSystem;
use Symfony\Component\Console\Helper\TableSeparator;


class MigrationRepository
{
    /**
     * Migration directory.
     *
     * @var string
     */
    private $migration_dir;

    /**
     * Database key.
     *
     * @var string
     */
    private $db;

    /**
     * MigrationRepository constructor.
     *
     * @param string $db
     */
    public function __construct($db)
    {
        $this->migration_dir = ROOT_PATH . 'database/migrations/' . $db;
        $this->db = $db;
    }

    /**
     * Gets all migrations.
     *
     * @return array
     */
    public function getAllMigrations()
    {
        $migrations = $this->getMigrationFiles();
        $migrations = $this->getMigrationClasses($migrations);
        $migrations = $this->getMatchingClasses($migrations);

        $migration_models = MigrationModel::on($this->db)
            ->where('state', 1)
            ->orderBy('migration')
            ->get();

        $relevant_migrations = [];
        foreach( $migrations as $migration_info ) {
            $migration_model = $migration_models->where('migration', $migration_info['name'])->first();

            if( $migration_model ) {
                $relevant_migrations[] = $migration_info;
            }
        }

        return $relevant_migrations;
    }

    /**
     * Gets info for migration status table.
     *
     * @return array
     */
    public function getMigrationTable()
    {
        $migrations = $this->getMigrationFiles();

        $migration_models = MigrationModel::on($this->db)->orderBy('migration')->get();

        $latest_batch = MigrationModel::on($this->db)->select('batch')->max('batch');

        $table_content = [];

        $length = count($migrations);
        $index = 1;

        foreach( $migrations as $migration_info ) {
            $migration_model = $migration_models->where('migration', $migration_info['name'])->first();

            if( $migration_model ) {
                if( $migration_model->state ) {
                    $state = '<fg=green>Up</>';
                } else {
                    $state = '<fg=#c0392b>Down</>';
                }

                $row = [$migration_model->migration, $migration_model->batch, $state];
                $table_content[] = $row;

                if( $index < $length ) {
                    $table_content[] = new TableSeparator();
                }

                $index++;
                continue;
            }

            $row = [$migration_info['name'], $latest_batch + 1, '<fg=#c0392b>Down</>'];
            $table_content[] = $row;

            if( $index < $length ) {
                $table_content[] = new TableSeparator();
            }

            $index++;
        }

        return $table_content;
    }

    /**
     * Gets all new migrations.
     *
     * @return array
     */
    public function getNewMigrations()
    {
        $migrations = $this->getMigrationFiles();
        $migrations = $this->getUpMigrations($migrations);
        $migrations = $this->getMigrationClasses($migrations);

        return $this->getMatchingClasses($migrations);
    }

    public function getLatestMigrations()
    {
        $migrations = $this->getMigrationFiles();
        $migrations = $this->getDownMigrations($migrations);
        $migrations = $this->getMigrationClasses($migrations);

        return $this->getMatchingClasses($migrations);
    }

    /**
     * Gets migrations in down state.
     *
     * @param $migrations
     *
     * @return array
     */
    private function getDownMigrations($migrations)
    {
        $migrations = array_reverse($migrations);

        $latest_batch = MigrationModel::on($this->db)
            ->select('batch')
            ->max('batch');

        $latest_migration_models = MigrationModel::on($this->db)
            ->where('batch', $latest_batch)
            ->where('state', 1)
            ->get();

        while( $latest_migration_models->count() <= 0 && $latest_batch > 0 ) {
            $latest_batch--;
            $latest_migration_models = MigrationModel::on($this->db)
                ->where('batch', $latest_batch)
                ->where('state', 1)
                ->get();
        }

        $latest_migrations = [];
        foreach( $migrations as $migration_info ) {
            $migration = $migration_info['name'];

            foreach($latest_migration_models as $model) {
                if( $model->migration === $migration ) {
                    $latest_migrations[] = $migration_info;
                }
            }
        }

        return $latest_migrations;
    }

    /**
     * Filters migrations by abstract migration class.
     *
     * @param $migrations
     *
     * @return array
     */
    private function getMatchingClasses($migrations)
    {
        $matching_migrations = [];
        foreach( $migrations as $migration_info ) {
            if( !is_subclass_of($migration_info['class'], Migration::class) ) {
                continue;
            }

            $matching_migrations[] = $migration_info;
        }

        return $matching_migrations;
    }

    /**
     * Gets the class string for given migrations.
     *
     * @param $migrations
     *
     * @return array
     */
    private function getMigrationClasses($migrations)
    {
        $classes = [];
        foreach( $migrations as $migration_info ) {
            $file_path = $this->migration_dir . '/' . $migration_info['file'];

            $file_name = $migration_info['name'];
            $exploded = explode('-', $file_name);
            $migration_name = $exploded[1];

            $name_exploded = explode('_', $migration_name);
            $class = '';

            foreach($name_exploded as $name_part) {
                $class .= ucfirst($name_part);
            }

            include $file_path;
            $classes[] = $class;
        }

        foreach( $migrations as $key => $migration_info ) {
            $migration_class = $classes[$key];
            $migrations[$key]['class'] = $migration_class;
        }

        return $migrations;
    }

    /**
     * Gets all migrations in up state.
     *
     * @param $migration_files
     *
     * @return array
     */
    private function getUpMigrations($migration_files)
    {
        $relevant_migrations = [];

        foreach( $migration_files as $migration_info ) {
            $migration = MigrationModel::on($this->db)
                ->where('migration', $migration_info['name'])
                ->first();

            if( is_null($migration) ) {
                $relevant_migrations[] = $migration_info;
                continue;
            }

            if( $migration->state ) {
                continue;
            }

            $relevant_migrations[] = $migration_info;
        }

        return $relevant_migrations;
    }

    /**
     * Scans migration directory.
     *
     * @return array
     */
    private function getMigrationFiles()
    {
        if( !FileSystem::dirExists($this->migration_dir) ) {
            return [];
        }

        $files = scandir($this->migration_dir);

        $migrations = [];

        foreach( $files as $file ) {
            $file_parts = pathinfo($this->migration_dir . '/' .$file);

            if( $file_parts['extension'] === 'php') {
                $migrations[] = [
                    'path' => $file_parts['dirname'],
                    'file' => $file_parts['basename'],
                    'name' => $file_parts['filename']
                ];
            }
        }

        return $migrations;
    }
}