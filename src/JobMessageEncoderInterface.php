<?php
# JobMessageEncoder.php
namespace j\AintQueue\Client;

interface JobMessageEncoderInterface
{
    public function encode($message): string;

}
