<?php

namespace Gomitech\Wallstreet;

use Aura\Cli\CliFactory;
use Aura\Cli\Status;
use Aura\Cli\Context\OptionFactory;
use Aura\Cli\Help;

require 'vendor/autoload.php';

$cli_factory = new CliFactory;
$context = $cli_factory->newContext($GLOBALS);
$stdio = $cli_factory->newStdio();

$options = array(
    'd' => "Run as websocket daemon",
    'update,u' => "fetch new financial data, then exit.",
    'p:' => "define a listening port for the websocket daemon.",
    'track,t*:' => "start tracking a new symbol."
);

$help = new Help(new OptionFactory);
$help->setSummary('Wallstreet');
$help->setOptions($options);
$help->setDescr("Wallstreet description bla bla ...");

$getopt = $context->getopt(array_keys($options));
if ($getopt->get('-d')) {
    $port = $getopt->get('-p', 9000);

    // run daemon
    $stdio->outln("Starting websocket daemon, listening on port {$port}.");

    $loop = \React\EventLoop\Factory::create();
    $service = new Server\RedisChannelRelay($loop, 'wallstreet');
    $server = new Server\WampServer($loop);
    $server->serve($service, $port, '0.0.0.0');
    $loop->run();

    exit(Status::SUCCESS);
}

if ($getopt->get('--update')) {

    $fetcher = new YahooFetcher(['userAgent' => 'Mozilla/5.0 (Windows NT 6.3; rv:36.0) Gecko/20100101 Firefox/36.0']);
    $storage = new RedisStorage();
    /* $storage = new DummyStorage([ */
    /*     new Symbol(['symbol' => 'APTA.ST']), */
    /*     new Symbol(['symbol' => 'star.st']) */
    /* ]); */

    $command = new Command\Update($fetcher, $storage);
    $command->run();

    exit(Status::SUCCESS);
}

if ($keys = $getopt->get('--track')) {
    $fetcher = new YahooFetcher(['userAgent' => 'Mozilla/5.0 (Windows NT 6.3; rv:36.0) Gecko/20100101 Firefox/36.0']);
    $storage = new RedisStorage();

    $command = new Command\TrackSymbol($fetcher, $storage);
    $result = $command->run($keys);
    if ($result->isOK()) {
        $stdio->outln('OK.');
        exit(Status::SUCCESS);
    } else {
        $failed = $result->getData("failed");
        if (!empty($failed)) {
            $stdio->errln('Failed to track symbols: '. implode(",", $failed));
            exit(Status::DATAERR);
        }

        exit(Status::SOFTWARE);
    }
}

$stdio->outln($help->getHelp('wallstreet'));
exit(Status::USAGE);
