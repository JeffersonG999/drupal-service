<?php

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

