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
    private $sslCa;
    private $sslVerify;

    public function bindData(Map $data)
    {
        $this->dsn      = $data->get('dsn');
        $this->username = $data->get('username');
        $this->password = $data->get('password');
        // MySQL/TLS options — ignored by SQLite. ssl_ca is a path to the CA cert
        // (a hosted MySQL like Aiven ships a ca.pem and requires TLS); ssl_verify
        // = "0" skips server-cert verification (leave unset/anything else to verify).
        $ca = $data->get('ssl_ca');
        $this->sslCa = ($ca === null) ? '' : $ca;
        $verify = $data->get('ssl_verify');
        $this->sslVerify = !($verify === '0' || $verify === 0 || $verify === false);
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

    /** Path to the TLS CA certificate (MySQL); '' = none. */
    public function getSslCa()
    {
        return $this->sslCa;
    }

    /** Whether to verify the server certificate (MySQL); default true. */
    public function getSslVerify()
    {
        return $this->sslVerify;
    }
}
