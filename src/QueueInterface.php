<?php

declare(strict_types=1);


namespace j\AintQueue\Client;

interface QueueInterface
{
    /**
     * Get channel name of current queue.
     *
     * @return string
     */
    public function getChannel(): string;


    /**
     * Push an executable job message into queue.
     *
     * @param string|array $message
     * @param int                   $delay
     *
     * @throws \Throwable
     */
    public function push($message, int $delay = 0): void;


    /**
     * Reset connection.
     *
     * @throws \Throwable
     */
    public function initConnection(): void;

    /**
     * Disconnect the connection.
     *
     * @throws \Throwable
     */
    public function destroyConnection(): void;

}
