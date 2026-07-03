<?php

namespace Demo;

use GenAI\Web\Attribute\Route;
use GenAI\Web\Attribute\RestController;

/**
 * App code that consumes the bundle: it just type-hints \PDO and the container
 * hands it the connection the genai/pdo bundle registered. No knowledge of how
 * PDO is built or configured.
 *
 * Runtime class (PHP 5.3-safe).
 */
#[RestController]
class PingController
{
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    #[Route('GET', '/db/ping')]
    public function ping()
    {
        $row = $this->pdo->query('SELECT 1 + 1 AS sum')->fetch();

        return array(
            'db'     => 'ok',
            'driver' => $this->pdo->getAttribute(\PDO::ATTR_DRIVER_NAME),
            'sum'    => (int) $row['sum'],
        );
    }
}
