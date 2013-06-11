<?php
class TwitchTvChecker
{
	protected $streams = array();
	protected $streams_csl;
	protected $checked = false;
	protected $live_streams;

	/**
	 * Add a stream to the checker.
	 * @param mixed $stream array or object. must have 'url' field
	 */
	public function addStream($stream)
	{
		// @todo consider catching an exception here. for now, just pass it on
		$this->streams[] = new TwitchTvStream($stream);

		// because we've added a new stream, make sure to tell the system we need to
		// re-check the streams' live status
		if ($this->checked) {
			$this->checked = false;
		}
	}

	/**
	 * Add multiple streams at once.
	 * @param array $streams
	 */
	public function addStreams(array $streams)
	{
		foreach ($streams as $stream) {
			$this->addStream($stream);
		}
	}

	/**
	 * Return only live streams. Will only check with twitch.tv once in the class
	 * object's lifetime
	 * @return array
	 */
	public function getLiveStreams()
	{
		if (!$this->checked) {
			$this->getLiveStreamData();
		}
		return $this->live_streams;
	}

	/**
	 * Return all streams regardless of state
	 * @return array
	 */
	public function getAllStreams()
	{
		return $this->streams;
	}

	/**
	 * Ask twitch.tv if any of our streams are online, and if they are, add them to
	 * $this->live_streams after appending the live data to them
	 * @return boolean returns false if we couldn't get a twitch.tv API response
	 */
	private function getLiveStreamData()
	{
		// get a comma-separated list of stream channels for our request
		$list_array = array();
		foreach ($this->streams as $stream) {
			$list_array[] = $stream->channel;
		}
		$list = implode(',', $list_array);

		// retrieve the JSON data from the justin.tv API
		$url = 'http://api.justin.tv/api/stream/list.json?channel=' . $list;

		// @todo implement a safer way to fetch the remote file
		echo "Waiting for Twitch.tv server... ";
		$json = file_get_contents($url);
		echo "Response received!\n\n";

		if (!$json) {
			return false;
		}

		$live_data = json_decode($json);

		// clear and prepare the live streams array
		$this->live_streams = array();

		for ($i=0; $i < count($this->streams); $i++) {
			for ($j=0; $j < count($live_data); $j++) {

				if ($live_data[$j]->channel->login == $this->streams[$i]->channel) {

					// add the twitch.tv API data to the existing stream data
					// and add it by reference to the live_streams array
					if ($this->streams[$i]->addStreamData($live_data[$j])) {
						$this->live_streams[] = $this->streams[$i]->data;
					}

				}
				elseif ($this->streams[$i]->live) {

					// make sure that offline streams are marked as such
					$this->streams[$i]->live = false;

				}

			}
		}

		return true;
	}
}
