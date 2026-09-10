<?php

namespace App\Console\Commands;

use Illuminate\Database\Console\Migrations\MigrateCommand as BaseMigrateCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateCommand extends BaseMigrateCommand
{
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        parent::initialize($input, $output);

        if ($this->getLaravel()->environment() === 'production') {
            $input->setOption('force', true);
            $input->setOption('seed', true);
        }
    }

    public function call($command, array $arguments = [])
    {
        if ($command === 'db:seed') {
            $arguments['--force'] = true;
        }

        return parent::call($command, $arguments);
    }
}
