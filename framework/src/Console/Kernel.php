<?php

namespace Tmi\Framework\Console;



use Psr\Container\ContainerInterface;

class Kernel
{

    public function __construct(
        private ContainerInterface $container,
        private Application $application,
    )
    {

    }

    public function handle(): int
    {
        $this->registerCommands();

        $status = $this->application->run();

        dd($status);

        return 0;
    }

    private function registerCommands(): void
    {

        $commandFile = new \DirectoryIterator(__DIR__ . '/Commands');
        $namespace = $this->container->get('framework-commands-namespace');
        foreach ($commandFile as $file) {
            if (!$commandFile->isFile()) {
                continue;
            }

            $command = $namespace . pathinfo($commandFile, PATHINFO_FILENAME);

            if (is_subclass_of($command, CommandInterface::class)) {
                $name = (new \ReflectionClass($command))
                    ->getProperty('name')
                    ->getDefaultValue();

                $this->container->add("console:$name", $command);
            }
        }
    }
}