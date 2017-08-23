<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2017-08
 */

namespace Runner\FastdSeeder;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SeederConsole extends Command
{

    public function configure()
    {
        $this->setName('seed:dataset');
        $this->addArgument('connection', InputArgument::OPTIONAL);
        $this->addArgument('tables', InputArgument::OPTIONAL);
        $this->addOption('excepts', 'e', InputOption::VALUE_OPTIONAL);
        $this->addOption('force', 'f', InputOption::VALUE_NONE);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $seeder = new Seeder();

        $seeder->setConnection($input->getArgument('connection'));

        $tables = $input->getArgument('tables');
        $tables = array_map('strtolower', $tables ? explode(',', $tables) : $seeder->getTables());

        if ($exceptTables = $input->getOption('excepts')) {
            $exceptTables = array_map('strtolower', explode(',', $exceptTables));
            $tables = array_diff($tables, $exceptTables);
        }

        if (0 === count($tables)) {
            $output->writeln('<error>no table to run</error>');
            return 1;
        }

        $output->writeln('<info>generating data sets...</info>');

        $output->writeln('');

        $force = (bool)$input->getOption('force');

        $seeder->mkdirIfNotExists();

        foreach ($tables as $table) {
            $output->writeln("running table: {$table}");

            if (!$force && $seeder->datasetExists($table)) {
                $output->writeln("               <error>data set exists</error>");
                continue;
            }

            $count = $seeder->generateDataSet($table, (bool)$input->getOption('force'));

            $output->writeln("               <info>{$count} records</info>");
        }

        return 0;
    }


}
