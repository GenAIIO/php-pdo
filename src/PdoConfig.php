<?php

namespace GenAI\Bundle\Pdo;

use GenAI\Di\Bean;
use GenAI\Di\Configuration;

/**
 * The bundle's auto-configuration: it registers a ready \PDO as a container bean,
 * so any controller/service can type-hint \PDO and get a connection. The pure
 * ext-pdo is untouched — this class is the wiring.
 *
 * Connection settings come from the bundle's own typed config, PdoProperty, which
 * is bound from the app's database.ini at build time. The dependency is injected
 * by type: boot() registers every #[Property] as a container bean, so a #[Bean]
 * method can simply ask for one. (A #[Bean] method runs on the PHP 5.3 runtime,
 * so it stays 5.3-safe: object type hints are fine; #[Value] on a parameter is
 * not, which is exactly why config arrives as an injected Property object.)
 *
 * To override, the app declares its own #[Bean(\PDO::class)] — but note bean ids
 * are unique, so you'd remove this bundle's registration rather than shadow it.
 *
 * Runtime class (PHP 5.3-safe); the #[...] lines are comments on 5.3.
 */
#[Configuration]
class PdoConfig
{
    #[Bean(\PDO::class)]
    public function pdo(PdoProperty $config)
    {
        $pdo = new \PDO($config->getDsn(), $config->getUsername(), $config->getPassword());
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

        return $pdo;
    }
}
