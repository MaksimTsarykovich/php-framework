<?php

namespace Tmi\Framework\Dbal;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class ConnectionFactory
{
    public function __construct(
        private readonly array $databaseUrl
    )
    {
    }

    public function create():Connection
    {
        $connection = DriverManager::getConnection( $this->databaseUrl);


        return $connection;
    }

}