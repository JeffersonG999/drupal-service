//https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Routing%21RouteMatch.php/class/RouteMatch/8.2.x

<?php

// Return route key from routing.yml, ex: web.example, system.admin
// Never return route path ex: /web/example, /system/admin
\Drupal::routeMatch()->getRouteName();

// Return the GET param pass in url and {param} in routing.yml, ex: /web/example/{page} in routing.yml and /web/example/1 in url
// /web/example/1
\Drupal::routeMatch()->getParameter('page');
// 1
// /web/example/jeff
\Drupal::routeMatch()->getParameter('page');
// jeff

RouteMatch::getRawParameter	
RouteMatch::getRawParameters

// Return an array with key, value GET parameters, ex: /web/example/{test}/{testbis}/{testbisbis} in routing.yml and /web/example/1/2/3 in url
// /web/example/1/2/3
\Drupal::routeMatch()->getParameters()->all();
// array([test] => 1 [testbis] => 2 [testbisbis] => 3)

\Drupal::routeMatch()->getRouteObject()->getOption('option_name');
