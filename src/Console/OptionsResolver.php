<?php

declare(strict_types=1);

/**
 * This file is part of Larastan.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace NunoMaduro\Larastan\Console;

use PHPStan\Command\AnalyseCommand;
use Illuminate\Contracts\Foundation\Application;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputDefinition;

/**
 * @internal
 */
final class OptionsResolver
{
    /**
     * The default level.
     */
    private const DEFAULT_LEVEL = 5;

    /**
     * The default memory limit.
     */
    private const DEFAULT_MEMORY_LIMIT = '1024M';

    /**
     * @var \Illuminate\Contracts\Foundation\Application
     */
    private $application;

    /**
     * @var \PHPStan\Command\AnalyseCommand
     */
    private $command;

    /**
     * @var \Symfony\Component\Console\Input\InputDefinition
     */
    private $definition;

    /**
     * OptionsResolver constructor.
     *
     * @param \Illuminate\Contracts\Foundation\Application $application
     * @param \PHPStan\Command\AnalyseCommand $command
     */
    public function __construct(Application $application, AnalyseCommand $command)
    {
        $this->application = $application;
        $this->command = $command;
    }

    /**
     * @return \Symfony\Component\Console\Input\InputDefinition
     */
    public function getDefinition(): InputDefinition
    {
        $definition = clone $this->command->getDefinition();
        $definition->setArguments([]);

        $definition->getOption('level')
            ->setDefault(self::DEFAULT_LEVEL);

        $definition->getOption('autoload-file')
            ->setDefault($this->application->basePath('vendor/autoload.php'));

        $definition->getOption('configuration')
            ->setDefault(__DIR__.'/../../extension.neon');

        $definition->getOption('memory-limit')
            ->setDefault(self::DEFAULT_MEMORY_LIMIT);

        $definition->addOption(
            new InputOption(
                'paths',
                'p',
                InputOption::VALUE_REQUIRED,
                'Paths with source code to run analysis on',
                $this->application->make('path')
            )
        );

        return $this->definition = $definition;
    }
}
