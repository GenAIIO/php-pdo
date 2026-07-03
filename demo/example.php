<?php

/**
 * Bundle (auto-configure) flow.
 *
 *   composer install      (pulls genai/pdo -> the \PDO bean wiring)
 *   php example.php        (needs ext-pdo_sqlite for this in-memory demo)
 *
 * We never write any PDO wiring. The genai/pdo bundle declares extra.genai.scan,
 * so the Kernel scans its #[Configuration] + PdoProperty, registers a \PDO bean,
 * and PingController gets it injected by type. The connection settings come from
 * our own config/database.ini ([pdo] group) bound into the bundle's PdoProperty —
 * swap the dsn there for a real mysql:/pgsql: connection.
 */

use GenAI\Boot\Kernel;
use GenAI\Http\Request;

$loader = require __DIR__ . '/vendor/autoload.php';

$kernel = new Kernel();
$compiler = $kernel->compile(__DIR__, array('Demo'), $loader);

echo "Bundles auto-discovered (extra.genai.scan):\n";
foreach ($compiler->discoverScanNamespaces() as $namespace) {
    echo "  - " . $namespace . "\n";
}

$dispatcher = $kernel->boot();

$response = $dispatcher->dispatch(new Request('GET', '/db/ping'));
echo "\nGET /db/ping -> " . $response->getStatusCode() . "\n";
echo "    " . trim((string) $response->getBody()) . "\n";
