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

        $input->setOption('force', true);
    }
}
