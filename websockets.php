<?php

require "class.PHPWebSocket.php";

class SocketPHP {
    private $socket;

    public function __construct() {
        $this->socket = new PHPWebSocket();
    }

    public function on($event, $callback) {
        if($event === "connection") {
            $this->socket->bind("open", $callback);
            return $this;
        } else if($event === "disconnection") {
            $this->socket->bind("close", $callback);
            return $this;
        } else if($event === "message") {
            $this->socket->bind("message", $callback);
            return $this;
        } else {
            $this->socket->bind($event, $callback);
            return $this;
        }
    }

    public function start(string $host, int $port, Callable $callback) {
        $callback();
        $this->socket->wsstartServer($host, $port);
    }

    public function emit($event, $data) {
        foreach($this->socket->wsClients as $id) {
			$this->socket->wsSend($id, $data);
        }
    }
}