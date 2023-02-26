https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Language%21LanguageManagerInterface.php/interface/LanguageManagerInterface/10

<?php

// Returns the current language for the given type.
\Drupal::languageManager()->getCurrentLanguage();
\Drupal::languageManager()->getCurrentLanguage()->getName();
\Drupal::languageManager()->getCurrentLanguage()->getId();
\Drupal::languageManager()->getCurrentLanguage()->getDirection();
\Drupal::languageManager()->getCurrentLanguage()->getWeight();
//\Drupal::languageManager()->getCurrentLanguage()->getLocked();

// Returns a language object representing the site's default language.
\Drupal::languageManager()->getDefaultLanguage();
\Drupal::languageManager()->getDefaultLanguage()->getName();
\Drupal::languageManager()->getDefaultLanguage()->getId();
\Drupal::languageManager()->getDefaultLanguage()->getDirection();
\Drupal::languageManager()->getDefaultLanguage()->getWeight();
//\Drupal::languageManager()->getDefaultLanguage()->getLocked();

// Returns a list of the default locked languages.
$lockedLanguages = \Drupal::languageManager()->getDefaultLockedLanguages();
foreach ($lockedLanguages as $id => $lockedLanguage) {
  $lockedLanguage->getName();
  $lockedLanguage->getId();
  $lockedLanguage->getDirection();
  $lockedLanguage->getWeight();
  //$lockedLanguage->getLocked();
}

// Returns information about all defined language types.
$languageTypes = \Drupal::languageManager()->getDefinedLanguageTypesInfo();
$languageTypes['language_interface']['name'];
$languageTypes['language_interface']['description'];
$languageTypes['language_interface']['locked'];
$languageTypes['language_content']['name'];
$languageTypes['language_content']['description'];
$languageTypes['language_content']['locked'];
$languageTypes['language_url'];

// Returns a language object from the given language code.
\Drupal::languageManager()->getLanguage('fr');
\Drupal::languageManager()->getLanguage('fr')->getName();
\Drupal::languageManager()->getLanguage('fr')->getId();
\Drupal::languageManager()->getLanguage('fr')->getDirection();
\Drupal::languageManager()->getLanguage('fr')->getWeight();
//\Drupal::languageManager()->getLanguage('fr')->getLocked();

// Produced the printed name for a language for display.
\Drupal::languageManager()->getLanguageName('fr');

// Returns a list of languages set up on the site.
$languages = \Drupal::languageManager()->getLanguages();
foreach ($languages as $language) {
  $language->getName();
  $language->getId();
  $language->getDirection();
  $language->getWeight();
  //$anguage->getLocked();
}

// Returns an array of the available language types.
\Drupal::languageManager()->getLanguageTypes()

// Returns a list of languages set up on the site in their native form.
$languages = \Drupal::languageManager()->getNativeLanguages();
foreach ($languages as $language) {
  $language->getEntityTypeId();
  $language->getTypedData();
  $language->getCacheContexts();
  $language->getCacheTags();
  $language->getCacheMaxAge();
  $language->getOriginalId();
  $language->id();
  $language->label();
  $language->getDirection();
  $language->getWeight();
}

// Some common languages with their English and native names
$languages = \Drupal::languageManager()->getStandardLanguageList();
foreach ($languages as $language_code => $language_value) {
}

// Checks whether a language is locked.
$isLanguageLocked = \Drupal::languageManager()->isLanguageLocked('fr');

// Returns whether or not the site has more than one language added.
\Drupal::languageManager()->isMultilingual();
