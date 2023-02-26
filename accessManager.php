<?php

use Drupal\Core\Routing\RouteMatch;
use Drupal\Core\Routing\RouteObjectInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;

// Checks a route against applicable access check services.
\Drupal::accessManager()->checkNamedRoute('web.example', array(), NULL, FALSE);
// Return 1 if access or 0 
\Drupal::accessManager()->checkNamedRoute('web.example', array(), NULL, TRUE);
// Return object AccessResult

// Execute access checks against the incoming request
$route = new Route('/web/example');
$request = new Request();
$request->attributes->set(RouteObjectInterface::ROUTE_OBJECT, $route);
\Drupal::accessManager()->checkRequest($request, NULL, FALSE);

// Checks a route against applicable access check services.
$route = new Route('/web/example');
$request = new Request();
$request->attributes->set(RouteObjectInterface::ROUTE_OBJECT, $route);
$route_match = RouteMatch::createFromRequest($request);
\Drupal::accessManager()->check($route_match, NULL, NULL, FALSE);
