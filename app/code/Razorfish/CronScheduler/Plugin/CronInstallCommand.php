<?php
/**
 * Copyright [first year code created] Adobe
 * All rights reserved.
 */

namespace Razorfish\CronScheduler\Plugin;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CronInstallCommand
{
    public function beforeExecute($subject, InputInterface $input, OutputInterface $output)
    {

        echo $input;
        echo $output;
    }
}
