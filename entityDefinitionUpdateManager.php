<?php

// https://www.flocondetoile.fr/blog/converting-content-entity-type-make-it-translatable-drupal-8

// Change an entity settings
// Entity become translatable
$definition_update_manager = \Drupal::entityDefinitionUpdateManager();
$entity_type = $definition_update_manager->getEntityType('my_entity');
$entity_type->set('translatable', TRUE);
$entity_type->set('data_table', 'my_entity_field_data');

// We need to update the field storage definitions, for the langcode field, and for all
// the fields we updated on the entity Class. Add here all the fields you updated in the
// entity Class by adding setTranslatable(TRUE).
/** @var \Drupal\Core\Entity\EntityLastInstalledSchemaRepositoryInterface $last_installed_schema_repository */
$last_installed_schema_repository = \Drupal::service('entity.last_installed_schema.repository');
$field_storage_definitions = $last_installed_schema_repository->getLastInstalledFieldStorageDefinitions('my_entity');
$field_storage_definitions['title']->setTranslatable(TRUE);
$field_storage_definitions['langcode']->setTranslatable(TRUE);

// We need to add a new field, default langcode.
$storage_definition = BaseFieldDefinition::create('boolean')
  ->setName('default_langcode')
  ->setLabel(t('Default translation'))
  ->setDescription(t('A flag indicating whether this is the default translation.'))
  ->setTargetEntityTypeId('my_entity')
  ->setTargetBundle(NULL)
  ->setTranslatable(TRUE)
  ->setRevisionable(TRUE)
  ->setDefaultValue(TRUE);
$field_storage_definitions['default_langcode'] = $storage_definition;

// And now we can launch the process for updating the entity type and the database 
// schema, including data migration.
$definition_update_manager->updateFieldableEntityType($entity_type, $field_storage_definitions, $sandbox);
