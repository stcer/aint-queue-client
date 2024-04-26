<?php

declare(strict_types=1);



namespace j\AintQueue\Client;

abstract class AbstractQueue implements QueueInterface
{
    /**
     * @var string
     */
    protected $channelPrefix = 'aint-queue:';

    /**
     * @var string
     */
    protected $channel = 'default';


    /**
     * @var array
     */
    protected $options;


    public function __construct(string $channel, array $options = [])
    {
        $this->channel = $channel;
        $this->options = $options;
    }

    /**
     * Get name of the channel.
     *
     * @return string
     */
    public function getChannel(): string
    {
        return $this->channel;
    }


    public function setMessageEncoder(JobMessageEncoderInterface $messageEncoder): void
    {
        $this->messageEncoder = $messageEncoder;
    }

    public function getMessageEncoder(): JobMessageEncoderInterface
    {
        if (!isset($this->messageEncoder)) {
            $this->messageEncoder = new AliasMessageEncoder();
        }

        return $this->messageEncoder;
    }
}
