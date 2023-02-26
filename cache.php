<?php

// Request default cache bin
\\Drupal::cache();

// Request a specific cache bin
\\Drupal::cache('bin2');

//
\Drupal::cache()->set('jeff', 'goven');
$cache = \Drupal::cache()->get('jeff');
$cache->cid; // jeff
$cache->data; // goven
$cache->created; // 1677422420.598
$cache->expire; // -1
$cache->serialized; // 0
$cache->tags; // array()
$cache->checksum; // 0
$cache->valid; // 1

// 
$items = array(
  'jeff' => array(
    'data' => 'gov'
  ),
  'jefferson' => array(
    'data' => 'goven'
  ),
);
$cids = array(
  'jeff',
  'jefferson'
);
\Drupal::cache()->setMultiple($items);
\Drupal::cache()->get('jeff');
\Drupal::cache()->get('jefferson');
\Drupal::cache()->getMultiple($cids);

