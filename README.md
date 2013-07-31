PHP Twitch.tv Live Checker
==========================

Simple class interface for checking if twitch.tv streams are online or not.

# Usage

Create one or several instances of anlutro/TwitchTv/Stream and do the isLive() on any of the objects to see to see if it's live or not. If it's live, you'll be able to get extra info gathered from the Twitch.TV official API - such as viewer count, resolution and what the stream's title is.

The Stream class needs a URL or channel field. If you pass it an URL, it will automatically parse that URL into a channel, but passing it the channel name is more efficient since the class won't have to do any parsing.

Simple example:

    <?php
    use Raz\TwitchTv\Stream;
    $mystreams = array();

    $mystreams[] = new Stream(array(
        'url' => 'http://www.twitch.tv/qlrankstv', // parses URLs just fine...
        'myname' => 'QLRanks TV'
    ));
    $mystreams[] = new Stream(array(
        'channel' => 'snipealot2' // ... but channels are more efficient
        'myname' => 'Snipealot\'s 24/7 Afreeca stream'
    ));

    foreach ($mystreams as $stream) {
        if ($stream->isLive()) {
            var_dump($stream);
        }
    }
    ?>

Any additional fields you pass when instanciating a TwitchTvStream object will be preserved and you can access it when doing your live check. In this example, you would do this by doing `echo $stream->myname`.

When checking if a stream is live, the class gathers the channels of ALL existing Stream objects and sends a request to TwitchTV to see which ones are live. Measures are in place so that this request is only sent once in a script's lifetime UNLESS you instanciate more Stream objects after doing an isLive() check.

# Performance

It is recommended that you write a separate script not accessible to the public and run it as a cron job every minute or so to check if streams in your database are live or not and either save the data to a database or cache it in a file. If you do the live-check on your live website, you will have some users having to wait for the TwitchTV API to respond before the website loads, and if you have a spike of traffic, your server will be spamming the TwitchTV API with requests.

# Additional information

These fields are automatically added to a stream that's live:

* `$stream->stream_title`
* `$stream->stream_game`
* `$stream->stream_viewers`
* `$stream->stream_res_height`
* `$stream->stream_res_width`
* `$stream->stream_bitrate`
* `$stream->stream_thumb_huge`
* `$stream->stream_thumb_large`
* `$stream->stream_thumb_medium`
* `$stream->stream_thumb_small`
* `$stream->stream_avatar_huge`
* `$stream->stream_avatar_large`
* `$stream->stream_avatar_medium`
* `$stream->stream_avatar_small`
* `$stream->stream_avatar_tiny`

You also, of course, have access to the URL and channel fields:

* `$stream->channel`
* `$stream->url`

... as well as any additional fields you passed to it when instanciating.

# License

Do whatever you want with this code, but at your own risk.