<?php
namespace Application\Modules\Notificacao\Service;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Notificacao implements MessageComponentInterface
{
    protected $clients;

    function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);

        echo "Nova conexão! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $numRecv = count($this->clients) - 1;

        foreach  ($this->clients as $client){
            if($from !== $client)
            {
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }
}
