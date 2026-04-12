<?php

use Drupal\user\Entity\User;

// Return User information
$user = \Drupal::currentUser();
$user->id();
$user->getRoles();
$user->getTimezone();
$user->getAccountName();
$user->getDisplayName();
$user->getEmail();

// Statut
$user->isAuthenticated();
$user->isAnonymous();

// Return More User information
$user = \Drupal::currentUser()->getAccount();
$user->id();

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

// Rôles et permissions
$user->getRoles();
$user->hasPermission('administer nodes');
$user->hasRole('administrator');

// Langue
$user->getPreferredLangcode();
$user->getPreferredAdminLangcode();

// Avoid to use
\Drupal::currentUser()->setAccount();
// Avoid to use
\Drupal::currentUser()->setInitialAccountId();

// Return Full User information
User::load(\Drupal::currentUser()->id());

// Charger l'entité User complète
use Drupal\user\UserInterface;

$account = \Drupal::currentUser();

/** @var UserInterface $user */
$user = \Drupal::entityTypeManager()->getStorage('user')->load($account->id());

// Champs disponibles uniquement sur l'entité complète
$user->get('field_prenom')->value;
$user->get('field_avatar')->entity;
$user->getCreatedTime();
$user->getLastAccessedTime();
$user->get('field_organisation')->value;

