<?php
require_once 'twitchtvstream.class.php';
require_once 'twitchtvchecker.class.php';

if (php_sapi_name() != 'cli') {
	header('content-type:text/plain');
}

echo "\nTwitch.tv API by Andreas Lutro (C) 2013\n\n";

try {
	$checker = new TwitchTvChecker;
	$streams = array(
		array(
			'url' => 'http://www.twitch.tv/qlrankstv',
			'name' => 'QLRanks TV',
			'category' => 'Quake Live'
		),
		array(
			'url' => 'www.twitch.tv/liquidtlo',
			'name' => 'Liquid`TLO',
			'category' => 'StarCraft 2'
		),
		array(
			'url' => 'http://de.twitch.tv/snipealot2/',
			'name' => 'Snipealot\'s 24/7 Afreeca stream',
			'category' => 'StarCraft: Brood War'
		)
	);

	$checker->addStreams($streams);

	$streams = $checker->getLiveStreams();

	foreach ($streams as $stream) {
		echo $stream['name'] . ' - ' . $stream['url'] . "\n";
		echo $stream['stream_title'] . ' - game: ' . $stream['stream_game'] . "\n";
		echo 'Resolution: ' . $stream['stream_res_width'] . 'x' . $stream['stream_res_height'] . ' - viewers: ' . $stream['stream_viewers'] . "\n\n";
	}
}
catch(Exception $e) {
	die($e);
}


