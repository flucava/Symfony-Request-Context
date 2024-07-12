<?php

namespace Flucava\RequestContextBundle\Console;

use Symfony\Component\Console\Input\InputOption;

/**
 * @author Philipp Marien
 */
abstract class Command extends \Symfony\Component\Console\Command\Command
{
    public function __construct(?string $name = null)
    {
        parent::__construct($name);

        $this->addOption('context', null, InputOption::VALUE_REQUIRED);
    }
}
