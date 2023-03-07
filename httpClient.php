https://www.drupal.org/docs/contributed-modules/http-client-manager
https://www.census.gov/data/developers/guidance/api-user-guide/example-api-queries.html
https://swapi.dev/api/people

<?php
$request = \Drupal::httpClient()->request('GET', 'https://api.census.gov/data/2019/acs/acs1?get=NAME,B02015_009E,B02015_009M', [
  'limit' => 10,
  'sort' => 'ASC',
]);
$request = \Drupal::httpClient()->request('GET', 'https://api.census.gov/data/timeseries/intltrade/exports/enduse?get=DISTRICT,DIST_NAME,ALL_VAL_MO,ALL_VAL_YR&YEAR=2016&MONTH=06');
$request = \Drupal::httpClient()->get('https://api.census.gov/data/timeseries/intltrade/exports/enduse?get=DISTRICT,DIST_NAME,ALL_VAL_MO,ALL_VAL_YR&YEAR=2016&MONTH=06');
$status_code = $request->getStatusCode();
$headers = $request->getHeaders();
$body = $request->getBody();
$body_json = json_decode($request->getBody(), TRUE);
$posts = $request->getBody()->getContents();
$posts_json = json_decode($request->getBody()->getContents(), TRUE);
$reason = $request->getReasonPhrase();
