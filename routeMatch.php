https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Routing%21RouteMatchInterface.php/interface/RouteMatchInterface/10
https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Routing%21RouteMatch.php/class/RouteMatch/10
https://api.drupal.org/api/drupal/core%21tests%21Drupal%21Tests%21Core%21Routing%21RouteMatchTest.php/10

<?php

use Drupal\Core\Routing\RouteMatch;
use Drupal\Core\Routing\RouteObjectInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;

// Return a RouteMatch Object from a specific Request not from the current route in url
$route = new Route('/test-route/{foo}');
$request = new Request();
$request->attributes->set(RouteObjectInterface::ROUTE_NAME, 'test_route');
$request->attributes->set(RouteObjectInterface::ROUTE_OBJECT, $route);
$request->attributes->set('foo', '1');
RouteMatch::createFromRequest($request);

// Return the GET param pass in url and {param} in routing.yml, ex: /web/example/{page} in routing.yml and /web/example/1 in url
// /web/example/1
\Drupal::routeMatch()->getParameter('page');
// 1
// /web/example/jeff
\Drupal::routeMatch()->getParameter('page');
// jeff

// Return an array with key, value GET parameters, ex: /web/example/{test}/{testbis}/{testbisbis} in routing.yml and /web/example/1/2/3 in url
// /web/example/1/2/3
\Drupal::routeMatch()->getParameters()->all();
// array([test] => 1 [testbis] => 2 [testbisbis] => 3)

// Return the GET param pass in url and {param} in routing.yml, ex: /web/example/{page} in routing.yml and /web/example/1 in url
// /web/example/1
\Drupal::routeMatch()->getRawParameter('page');
// 1
// /web/example/jeff
\Drupal::routeMatch()->getRawParameter('page');
// jeff

// Return an array with key, value GET parameters, ex: /web/example/{test}/{testbis}/{testbisbis} in routing.yml and /web/example/1/2/3 in url
// /web/example/1/2/3
\Drupal::routeMatch()->getRawParameters()->all();
// array([test] => 1 [testbis] => 2 [testbisbis] => 3)

// Return route key from routing.yml, ex: web.example, system.admin
// Never return route path ex: /web/example, /system/admin
\Drupal::routeMatch()->getRouteName();

// Return an object of the current route
\Drupal::routeMatch()->getRouteObject();
// Can access to object element with get function
\Drupal::routeMatch()->getRouteObject()->getPath();
\Drupal::routeMatch()->getRouteObject()->getHost();
\Drupal::routeMatch()->getRouteObject()->getSchemes();
\Drupal::routeMatch()->getRouteObject()->getMethods();
\Drupal::routeMatch()->getRouteObject()->getDefaults();
\Drupal::routeMatch()->getRouteObject()->getRequirements();
\Drupal::routeMatch()->getRouteObject()->getOptions();
\Drupal::routeMatch()->getRouteObject()->getOption('compiler_class');
\Drupal::routeMatch()->getRouteObject()->getCondition();
\Drupal::routeMatch()->getRouteObject()->compile()->getVariables();


