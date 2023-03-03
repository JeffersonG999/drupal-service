<?php

use Drupal\user\Entity\User;

// Return User information
$user = \Drupal::currentUser();
$user->id();
$user->getRoles();
$user->getTimezone();

// Return More User information
$user = \Drupal::currentUser()->getAccount();
$user->id();
$user->getRoles();
$user->name;
$user->getTimezone();
$user->langcode;
$user->pass;
$user->status;
$user->created;
$user->changed;
$user->login;
$user->init;
$user->default_langcode;

// Avoid to use
\Drupal::currentUser()->setAccount();
// Avoid to use
\Drupal::currentUser()->setInitialAccountId();

// Return Full User information
User::load(\Drupal::currentUser()->id());
