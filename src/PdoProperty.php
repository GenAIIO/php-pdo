<?php

namespace GenAI\Bundle\Pdo;

use GenAI\Property\AbstractProperty;
use GenAI\Property\Attribute\Property;
use GenAI\Property\Util\Map;

/**
 * The bundle's own typed config (like Spring's @ConfigurationProperties). The
 * #[Property] line binds it, at build time, to the [pdo] group of the app's
 * database.ini; the class body runs on PHP 5.3.
 *
 * The app provides the values (its own config/database.ini); the bundle just
 * declares what it needs. boot() registers this as a container bean, so PdoConfig
 * can type-hint it.
 */
#[Property(group: 'pdo', file: 'database.ini')]
class PdoProperty extends AbstractProperty
{
    private $dsn;
    private $username;
    private $password;

    public function bindData(Map $data)
    {
        $this->dsn      = $data->get('dsn');
        $this->username = $data->get('username');
        $this->password = $data->get('password');
    }

    public function getDsn()
    {
        return $this->dsn;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }
}
