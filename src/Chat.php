<?php
namespace MyApp;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $client->send($msg);
            }
        }
        
        // Lambayı kontrol etme kısmı
        if ($msg == "lamp_on") {
            $this->turnOnLamp();
        } elseif ($msg == "lamp_off") {
            $this->turnOffLamp();
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    private function turnOnLamp() {
        // Burada gerçek dünya kodu kullanarak lambayı açmalısınız
        // Örneğin GPIO pinleri ile Raspberry Pi kullanıyorsanız:
        // shell_exec("gpio -g write 17 1");
        echo "Lamp turned on\n";
    }

    private function turnOffLamp() {
        // Burada gerçek dünya kodu kullanarak lambayı kapatmalısınız
        // Örneğin GPIO pinleri ile Raspberry Pi kullanıyorsanız:
        // shell_exec("gpio -g write 17 0");
        echo "Lamp turned off\n";
    }
}
