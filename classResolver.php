https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21DependencyInjection%21ClassResolverInterface.php/interface/ClassResolverInterface/10

<?php

use Drupal\user\Controller\UserController;

$userController = \Drupal::classResolver()->getInstanceFromDefinition(UserController::class);
$userController->logout();

$userController = \Drupal::classResolver(UserController::class);
$userController->logout();
 
