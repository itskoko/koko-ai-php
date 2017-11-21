<?php

namespace Koko;
use GuzzleHttp\Client;

class Tracker {
  const ENDPOINT = 'https://api.koko.ai';

  public function __construct($options) {
    $this->client = new \GuzzleHttp\Client();
    $this->headers = [
      'Authorization' => $options['auth'],
      'Content-Type' => 'application/json'
    ];
  }

  protected function request($pathname, $options, $request_options) {
    $url = self::ENDPOINT . $pathname;

    $request_options_copy = $request_options;
    $request_options_copy['headers'] = $this->headers;
    $request_options_copy['http_errors'] = false;
    $request_options_copy['json'] = $options;

    $response = $this->client->request('POST', $url, $request_options_copy);
    $contents = $response->getBody()->getContents();

    $status = $response->getStatusCode();
    if ($status >= 500) {
      throw new \Exception($contents);
    }

    $data = json_decode($contents, true);
    if (!is_array($data)) {
        throw new \Exception('No response data returned.');
    }

    if (array_key_exists('errors', $data)) {
      throw new \Exception(join('\n', $data['errors']));
    }

    return $data;
  }

  public function trackContent($options, $request_options = NULL) {
    return self::request('/track/content', $options, $request_options);
  }

  public function trackFlag($options, $request_options = NULL) {
    return self::request('/track/flag', $options, $request_options);
  }

  public function trackModeration($options, $request_options = NULL) {
    return self::request('/track/moderation', $options, $request_options);
  }
}
