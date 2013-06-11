twitchtvchecker
===============

PHP Twitch.tv Live Status Checker

Simple class interface for checking if twitch.tv streams are online or not. Provide either:

- an array of associative arrays with a 'url' field containing the stream's URL
- or an array of objects with a 'url' member containing the stream's URL

... and the checker will, when you ask it to, return only the ones which are live with additional data such as stream title, viewer count, resolution and thumbnail URLs.

The checker will only send a request to the twitch.tv servers once in its lifetime.
