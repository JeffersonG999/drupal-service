https://www.drupal.org/docs/contributed-modules/http-client-manager

<?php
$request = \Drupal::httpClient()->request('GET', 'https://api.census.gov/data/2019/acs/acs1?get=NAME,B02015_009E,B02015_009M', [
  'limit' => 10,
  'sort' => 'ASC',
]);
$request = \Drupal::httpClient()->request('GET', 'https://api.census.gov/data/timeseries/intltrade/exports/enduse?get=DISTRICT,DIST_NAME,ALL_VAL_MO,ALL_VAL_YR&YEAR=2016&MONTH=06');
$status_code = $request->getStatusCode();
$headers = $request->getHeaders();
$posts = $request->getBody()->getContents();
