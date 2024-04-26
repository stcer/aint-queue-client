<?php

declare(strict_types=1);

namespace j\AintQueue\Client\Connection;

use j\AintQueue\Client\Exception\ConnectorException;

interface Connector
{
    /**
     * Make current connector instance connected.
     *
     * @throws ConnectorException
     */
    public function connect(): void;

    /**
     * Whether current connector is connected.
     *
     * @return bool
     *
     * @throws ConnectorException
     */
    public function isConnected(): bool;

    /**
     * Make current connector instance disconnected.
     *
     * @throws ConnectorException
     */
    public function disConnect(): void;

    /**
     * Get original connector.
     *
     * @return mixed
     */
    public function getConnector();
}
