<?php
/**
 * Parses and stores information about a Twitch.tv stream.
 *
 * @package    TwitchTvChecker
 * @subpackage TwitchTvStream 
 * @version    1.1
 * @author     Andreas Lutro
 * @copyright  2013 Andreas Lutro
 */
class TwitchTvStream
{
	public $url;
	public $channel;
	public $live = false;
	public $data;

	/**
	 * Create a new Twitch.tv stream. Provide a data array or object with a url field
	 * and we'll do the rest. The data will be stored for later should you need it.
	 * @param mixed $data object or array. must have url field
	 */
	public function __construct($data)
	{
		// check if passed data is an array or an object
		if (is_array($data)) {
			// check that they have a url field
			if (!isset($data['url'])) {
				throw new InvalidArgumentException('Array argument must have a url array key.');
			}
			$url = $data['url'];
			$data_type = 'Array';
			$this->data = $data;
		} elseif (is_object($data)) {
			if (!isset($data->url)) {
				throw new InvalidArgumentException('Object argument must have a url member.');
			}
			$url = $data->url;
			$data_type = 'Object';
			$this->data = $data;
		} else {
			throw new InvalidArgumentException('Argument must be an array or object.');
		}

		// some obvious checks
		if (strpos($url, 'twitch.tv') === FALSE) {
			throw new InvalidArgumentException('Only twitch.tv URLs are permitted.');
		}

		// $url should be formatted exactly like this:
		// http://www.twitch.tv/channelname

		// split the url into bits separated by the /'s
		$url_array = explode('/', $url);
		$at_channel_name = false;

		// iterate through the parts until we get to the twitch.tv part
		foreach ($url_array as $part) {

			if ($at_channel_name) {
				// this will be triggered when we're at the channel name
				$this->channel = strtolower($part);
				break;
			} else {
				if (strpos($part, 'twitch.tv')) {
					// we know that the next bit will be the channel name
					$at_channel_name = true;
				}
			}
		}

		// recreate the url to strip language etc. from it
		$this->url = 'http://www.twitch.tv/' . $this->channel;

		if ($data_type == 'Object') {
			$data->url = $this->url;
		} else {
			$data['url'] = $this->url;
		}

		return $this;
	}

	/**
	 * Add stream data from the Twitch.tv JSON API.
	 * @param array $data Decoded JSON from a Twitch.tv API call
	 */
	public function addStreamData($data)
	{
		if ($data->channel->login != $this->channel) {
			// this stream's channel and the data's channel does not match
			throw new UnexpectedValueException('Channel mismatch!');
		}

		// because we've received data that can only be fetched while the stream is live...
		$this->live = true;

		// append the live twitch.tv API data to the existing data
		if (is_object($this->data)) {
			$this->data->stream_title = $data->title;
			$this->data->stream_viewers = $data->channel_count;
			$this->data->stream_res_height = $data->video_height;
			$this->data->stream_res_width = $data->video_width;
			$this->data->stream_game = $data->channel->meta_game;
			$this->data->stream_thumb_huge = $data->channel->screen_cap_url_huge;
			$this->data->stream_thumb_large = $data->channel->screen_cap_url_large;
			$this->data->stream_thumb_medium = $data->channel->screen_cap_url_medium;
			$this->data->stream_thumb_small = $data->channel->screen_cap_url_small;
			$this->data->stream_avatar_huge = $data->channel->image_url_huge;
			$this->data->stream_avatar_large = $data->channel->image_url_large;
			$this->data->stream_avatar_medium = $data->channel->image_url_medium;
			$this->data->stream_avatar_small = $data->channel->image_url_small;
			$this->data->stream_avatar_tiny = $data->channel->image_url_tiny;
		} else {
			$this->data['stream_title'] = trim($data->title);
			$this->data['stream_viewers'] = $data->channel_count;
			$this->data['stream_res_height'] = $data->video_height;
			$this->data['stream_res_width'] = $data->video_width;
			$this->data['stream_game'] = $data->channel->meta_game;
			$this->data['stream_thumb_huge'] = $data->channel->screen_cap_url_huge;
			$this->data['stream_thumb_large'] = $data->channel->screen_cap_url_large;
			$this->data['stream_thumb_medium'] = $data->channel->screen_cap_url_medium;
			$this->data['stream_thumb_small'] = $data->channel->screen_cap_url_small;
			$this->data['stream_avatar_huge'] = $data->channel->image_url_huge;
			$this->data['stream_avatar_large'] = $data->channel->image_url_large;
			$this->data['stream_avatar_medium'] = $data->channel->image_url_medium;
			$this->data['stream_avatar_small'] = $data->channel->image_url_small;
			$this->data['stream_avatar_tiny'] = $data->channel->image_url_tiny;
		}

		return true;
	}
}
