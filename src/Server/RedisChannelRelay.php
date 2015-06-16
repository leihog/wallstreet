<?php

namespace Gomitech\Wallstreet\Server;

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;

class RedisChannelRelay implements WampServerInterface {

    protected $topics;

    public function __construct($loop, $channel) {
        $this->topics = [];

        $redis = new \Predis\Async\Client('tcp://127.0.0.1:6379', $loop);
        $redis->connect(function($redis) use($channel) {
            $redis->pubSubLoop($channel, [$this, 'relay']);
        });
    }

    public function onSubscribe(ConnectionInterface $conn, $topic) {
        $this->topics[$topic->getId()] = $topic;
    }

    public function relay($event, $pubsub) {
        $channel = $event->channel;
        if (!array_key_exists($channel, $this->topics)) {
            return;
        }

        $this->topics[$channel]->broadcast($event->payload);
    }

    public function onUnSubscribe(ConnectionInterface $conn, $topic) {
        unset($this->topics[$topic->getId()]);
    }

    public function onOpen(ConnectionInterface $conn) {
    }

    public function onClose(ConnectionInterface $conn) {
    }

    public function onCall(ConnectionInterface $conn, $id, $topic, array $params) {
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }

    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible) {
        $conn->callError($id, $topic, 'You are not allowed to push')->close();
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
    }
}
