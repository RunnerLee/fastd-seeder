<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2017-08
 */

namespace Runner\FastdSeeder;

use FastD\Model\Database;
use Runner\FastdSeeder\Exceptions\ConnectionNotFoundException;
use PDO;
use Symfony\Component\Yaml\Yaml;

class Seeder
{

    /**
     * @var Database
     */
    protected $connection;

    public function setConnection($name = 'default')
    {
        $name = $name ?: 'default';

        if (!config()->has("database.{$name}")) {
            throw new ConnectionNotFoundException("can not found configuare for {$name}");
        }

        $this->connection = $name;
    }

    public function mkdirIfNotExists()
    {
        !file_exists($this->getDataSetPath()) && mkdir($this->getDataSetPath(), 0755, true);
    }

    public function getDataSetPath($file = null)
    {
        return app()->getPath()."/database/dataset/{$this->connection}/{$file}";
    }

    public function getTables()
    {
        $database = config()->get("database.{$this->connection}.name");

        return database($this->connection)
            ->query("SHOW TABLES WHERE Tables_in_{$database} <> 'phinxlog'")
            ->fetchAll(PDO::FETCH_COLUMN);
    }

    public function generateDataSet($table)
    {
        if (0 === count($data = database($this->connection)->select($table, '*'))) {
            return 0;
        }
        file_put_contents($this->getDataSetPath("{$table}.yml"), Yaml::dump($data));

        return count($data);
    }

    public function datasetExists($table)
    {
        return file_exists($this->getDataSetPath("{$table}.yml"));
    }
}
