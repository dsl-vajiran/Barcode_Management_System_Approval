<?php

namespace App\Database;

use Illuminate\Database\Connectors\Connector;
use Illuminate\Database\Connectors\ConnectorInterface;
use PDO;

class HanaConnector extends Connector implements ConnectorInterface
{
    /**
     * Establish a database connection.
     *
     * @param  array  $config
     * @return \PDO
     */
    public function connect(array $config)
    {
        $dsn = $this->getDsn($config);

        $options = $this->getOptions($config);

        $connection = $this->createConnection($dsn, $config, $options);

        // Set schema if specified
        if (isset($config['schema']) && !empty($config['schema'])) {
            $connection->exec("SET SCHEMA \"{$config['schema']}\"");
        }

        return $connection;
    }

    /**
     * Create a DSN string from a configuration.
     *
     * @param  array  $config
     * @return string
     */
    protected function getDsn(array $config)
    {
        // Extract just the hostname from the HANA host format (SD1@sdf1.dslcorporate.com)
        $host = $config['host'];
        if (strpos($host, '@') !== false) {
            $host = substr($host, strpos($host, '@') + 1);
        }

        $port = $config['port'] ?? '30015';

        // SAP HANA ODBC DSN format: DRIVER={HDBODBC};SERVERNODE=host:port
        // Credentials (UID/PWD) are passed separately via createConnection
        return sprintf(
            'odbc:DRIVER={HDBODBC};SERVERNODE=%s:%s',
            $host,
            $port
        );
    }
}
