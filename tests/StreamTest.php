<?php
require_once __DIR__."/../src/anlutro/TwitchTv/Stream.php";

use anlutro\TwitchTv\Stream;

class StreamTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $stream = new Stream;

        $this->assertInstanceOf('anlutro\TwitchTv\Stream', $stream);
    }

    public function testDataIsPreserved()
    {
        $data = new StdClass;
        $data->test = 'test';
        $stream = new Stream($data);

        $this->assertSame($data->test, $stream->test);
    }

    public function testDataIsConvertedFromArrayToObject()
    {
        $data = array();
        $data['test'] = 'test';
        $stream = new Stream($data);

        $this->assertSame($data['test'], $stream->test);
    }

    public function testSettingOfChannel()
    {
        $stream = new Stream;
        $stream->channel = 'test';

        $this->assertSame('test', $stream->channel);
    }

    public function testSettingChannelAlsoSetsUrl()
    {
        $stream = new Stream;
        $stream->channel = 'test';

        $this->assertSame('http://www.twitch.tv/test', $stream->url);
    }

    public function testSettingUrl()
    {
        $stream = new Stream;
        $stream->url = 'http://www.twitch.tv/test';

        $this->assertSame('http://www.twitch.tv/test', $stream->url);
        $this->assertSame('test', $stream->channel);
    }

    public function testSettingOddUrls()
    {
        $stream = new Stream;
        $stream->url = 'www.twitch.tv/test';
        $this->assertSame('http://www.twitch.tv/test', $stream->url);
        $stream = null;

        $stream = new Stream;
        $stream->url = 'de.twitch.tv/test';
        $this->assertSame('http://www.twitch.tv/test', $stream->url);
        $stream = null;

        $stream = new Stream;
        $stream->url = 'www.twitch.tv/test/';
        $this->assertSame('http://www.twitch.tv/test', $stream->url);
        $stream = null;

        $stream = new Stream;
        $stream->url = 'twitch.tv/test';
        $this->assertSame('http://www.twitch.tv/test', $stream->url);
        $stream = null;

        $stream = new Stream;
        $stream->url = 'http://twitch.tv/test';
        $this->assertSame('http://www.twitch.tv/test', $stream->url);
        $stream = null;

        $stream = new Stream;
        $stream->url = 'http://www.twitch.tv/test/';
        $this->assertSame('http://www.twitch.tv/test', $stream->url);
        $stream = null;

        $stream = new Stream;
        $stream->url = 'http://de.twitch.tv/test';
        $this->assertSame('http://www.twitch.tv/test', $stream->url);
        $stream = null;
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSettingNonTwitchTvUrl()
    {
        $stream = new Stream;
        $stream->url = 'http://www.twtch.tv/test';
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSettingInvalidUrl()
    {
        $stream = new Stream;
        $stream->url = 'http://www.twitch.tv/ test';
    }

    /**
     * This test is fucking ridiculous I know
     */
    public function testLiveData()
    {
        $streams = array();
        $streams[] = new Stream(array('channel' => 'snipealot2'));
        $streams[] = new Stream(array('channel' => 'qlrankstv'));

        foreach ($streams as $stream) {
            if ($stream->isLive()) {
                $stream_title = $stream->stream_title;
                $this->assertTrue(isset($stream_title));
                $stream_title = $stream->stream_title;
                $this->assertTrue(isset($stream_title));
                $stream_viewers = $stream->stream_viewers;
                $this->assertTrue(isset($stream_viewers));
                $stream_res_height = $stream->stream_res_height;
                $this->assertTrue(isset($stream_res_height));
                $stream_res_width = $stream->stream_res_width;
                $this->assertTrue(isset($stream_res_width));
                $stream_bitrate = $stream->stream_bitrate;
                $this->assertTrue(isset($stream_bitrate));
                $stream_game = $stream->stream_game;
                $this->assertTrue(isset($stream_game));
                $stream_thumb_huge = $stream->stream_thumb_huge;
                $this->assertTrue(isset($stream_thumb_huge));
                $stream_thumb_large = $stream->stream_thumb_large;
                $this->assertTrue(isset($stream_thumb_large));
                $stream_thumb_medium = $stream->stream_thumb_medium;
                $this->assertTrue(isset($stream_thumb_medium));
                $stream_thumb_small = $stream->stream_thumb_small;
                $this->assertTrue(isset($stream_thumb_small));
                $stream_avatar_huge = $stream->stream_avatar_huge;
                $this->assertTrue(isset($stream_avatar_huge));
                $stream_avatar_large = $stream->stream_avatar_large;
                $this->assertTrue(isset($stream_avatar_large));
                $stream_avatar_medium = $stream->stream_avatar_medium;
                $this->assertTrue(isset($stream_avatar_medium));
                $stream_avatar_small = $stream->stream_avatar_small;
                $this->assertTrue(isset($stream_avatar_small));
                $stream_avatar_tiny = $stream->stream_avatar_tiny;
                $this->assertTrue(isset($stream_avatar_tiny));

                $nonexistant_stream_data = $stream->nonexistant_data;
                $this->assertFalse(isset($nonexistant_stream_data));
            }
        }
    }

}
