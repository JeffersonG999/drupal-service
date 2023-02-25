https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Language%21LanguageManagerInterface.php/interface/LanguageManagerInterface/10

<?php

// Return object of current language and attributes
\Drupal::languageManager()->getCurrentLanguage();
\Drupal::languageManager()->getCurrentLanguage()->getName();
\Drupal::languageManager()->getCurrentLanguage()->getId();
\Drupal::languageManager()->getCurrentLanguage()->getDirection();
\Drupal::languageManager()->getCurrentLanguage()->getWeight();
\Drupal::languageManager()->getCurrentLanguage()->getLocked();

// Return object of default language
\Drupal::languageManager()->getDefaultLanguage();
\Drupal::languageManager()->getDefaultLanguage()->getName();
\Drupal::languageManager()->getDefaultLanguage()->getId();
\Drupal::languageManager()->getDefaultLanguage()->getDirection();
\Drupal::languageManager()->getDefaultLanguage()->getWeight();
\Drupal::languageManager()->getDefaultLanguage()->getLocked();

\Drupal::languageManager()->getLanguage('langcode');
\Drupal::languageManager()->getLanguages();
\Drupal::languageManager()->reset();
\Drupal::languageManager()->isMultilingual();
\Drupal::languageManager()->getDefaultLanguage()->getId();
\Drupal::languageManager()->getLanguageConfigOverride('af', 'system.maintenance');
\Drupal::languageManager()->getLanguageConfigOverride('es', 'user.settings');

