<?php

namespace Tmi\Framework\Console;

use Psr\Container\ContainerInterface;
use Tmi\Framework\Console\ConsoleException;

class Application
{
    public function __construct(
        private ContainerInterface $container,

    )
    {
    }

    public function run():int
    {

        $argv = $_SERVER['argv'];
        $commandName = $argv[1] ?? null;

        if (!$commandName){
            throw new ConsoleException('Command not found');
        }
        /** @var CommandInterface $command */
        $command = $this->container->get("console:$commandName");

        $argc = array_slice($argv, 2);
        $options = $this->parseOptions($argc);

        $status = $command->execute($options);

        return $status;
    }

    private function parseOptions(array $args): array
    {
        $options = [];
        foreach ($args as $arg) {
            if (str_starts_with($arg, '--')) {
                $option = explode('=', substr($arg, 2));
                $options[$option[0]] = $option[1] ?? true;
            }
        }
        return $options;
    }
}