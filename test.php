<?php
require_once 'Stream.php';

use anlutro\TwitchTv\Stream;

if (php_sapi_name() != 'cli') {
    header('content-type:text/plain');
}

echo "\nTwitch.tv API by Andreas Lutro (C) 2013\n\n";

try {
    $streams[0] = new Stream( array(
        'url' => 'http://www.twitch.tv/qlrankstv',
        'name' => 'QLRanks TV'
    ) );
    $streams[1] = new Stream( array(
        'url' => 'www.twitch.tv/liquidtlo', // will parse fine without http://
        'name' => 'Liquid`TLO'
    ) );
    $streams[2] = new Stream( array(
        'url' => 'http://de.twitch.tv/snipealot2/', // .. and trailing slash
        'name' => 'Snipealot\'s 24/7 Afreeca stream'
    ) );

    $mystream = new StdClass; // this is inefficient but works as well
    $mystream->channel = 'riotgames'; // set channel instead of url to save resources
    $mystream->name = 'Riot Games';
    $streams[3] = new Stream( $mystream );

    $streams[4] = new Stream;
    $streams[4]->url = 'http://www.twitch.tv/tobiwandota';
    $streams[4]->name = 'TobiWan';

    foreach ($streams as $stream) {
        if ($stream->isLive()) {
            echo $stream->name . ' - ' . $stream->url . "\n";
            echo $stream->stream_title . ' - game: ' . $stream->stream_game . "\n";
            echo 'Resolution: ' . $stream->stream_res_width . 'x' . $stream->stream_res_height . ' - viewers: ' . $stream->stream_viewers . "\n\n";
        }
    }

    echo "Peak memory usage: " . memory_get_peak_usage() . " bytes\n";
} catch(Exception $e) {
    die($e);
}
