https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Language%21LanguageManagerInterface.php/interface/LanguageManagerInterface/10

<?php

\Drupal::languageManager()->getCurrentLanguage()->getId();
\Drupal::languageManager()->getCurrentLanguage()->getName();
\Drupal::languageManager()->getCurrentLanguage()->getDirection();
\Drupal::languageManager()->getLanguage('langcode');
\Drupal::languageManager()->getLanguages();
\Drupal::languageManager()->reset();
\Drupal::languageManager()->isMultilingual();
\Drupal::languageManager()->getDefaultLanguage()->getId();
\Drupal::languageManager()->getLanguageConfigOverride('af', 'system.maintenance');
\Drupal::languageManager()->getLanguageConfigOverride('es', 'user.settings');
\Drupal::languageManager()->getCurrentLanguage(LanguageInterface::TYPE_CONTENT);
