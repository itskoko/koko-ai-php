<?php

include 'vendor/autoload.php';

use Koko\Tracker;

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$koko = new Tracker(['auth' => $_ENV['KOKO_AUTH']]);

date_default_timezone_set('UTC');

function testTrackContent($koko) {
  $request = [
    'id' => '123',
    'user_id' => '123',
    'type' => 'post',
    'content_type' => 'text',
    'content' => ['text' => 'Some content'],
    'created_at' => date('c')
  ];

  $koko->trackContent($request);

  try {
    unset($request['type']);
    $koko->trackContent($request);
    throw new Exception('API should require `type` parameter.');
  }

  catch (Exception $e) {
    assert($e->getMessage() === 'Required property type was not present.');
  }
}

function testTrackFlag($koko) {
  $request = [
    'id' => '123',
    'flagger_id' => '123',
    'reasons' => ['crisis'],
    'targets' => [['content_id' => '123']],
    'created_at' => date('c')
  ];

  $koko->trackFlag($request);

  try {
    unset($request['flagger_id']);
    $koko->trackFlag($request);
    throw new Exception('API should require `flagger_id` parameter.');
  }

  catch (Exception $e) {
    assert($e->getMessage() === 'Required property flagger_id was not present.');
  }
}

function testTrackModeration($koko) {
  $request = [
    'id' => '123',
    'moderator_id' => '123',
    'action' => 'user_warned',
    'targets' => [['content_id' => '123']],
    'created_at' => date('c')
  ];

  $koko->trackModeration($request);

  try {
    unset($request['moderator_id']);
    $koko->trackModeration($request);
    throw new Exception('API should require `moderator_id` parameter.');
  }

  catch (Exception $e) {
    assert($e->getMessage() === 'Required property moderator_id was not present.');
  }
}

testTrackContent($koko);
testTrackFlag($koko);
testTrackModeration($koko);
