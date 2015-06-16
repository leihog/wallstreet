<?php

namespace Gomitech\Wallstreet\Server;

class WampServer {

    protected $loop;
    protected $server;

    public function __construct($loop = null) {
        if (!$loop) {
            $loop = \React\EventLoop\Factory::create();
        }
        $this->loop = $loop;
    }

    public function serve($service, $port, $host = '127.0.0.1') {

        $server = new \React\Socket\Server($this->loop);
        $server->listen($port, $host);

        $server = new \Ratchet\Server\IoServer(
            new \Ratchet\Http\HttpServer(
                new \Ratchet\WebSocket\WsServer(
                    new \Ratchet\Wamp\WampServer($service)
                )
            ),
            $server
        );

        $this->server = $server;
    }

    public function start() {
        $this->loop->run();
    }
}
