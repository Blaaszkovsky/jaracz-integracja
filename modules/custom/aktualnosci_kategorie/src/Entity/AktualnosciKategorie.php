<?php

namespace Drupal\aktualnosci_kategorie\Entity;

use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\aktualnosci_kategorie\AktualnosciKategorieInterface;
use Drupal\user\EntityOwnerTrait;

/**
 * Defines the kategorie aktualności entity class.
 *
 * @ContentEntityType(
 *   id = "aktualnosci_kategorie",
 *   label = @Translation("Kategorie aktualności"),
 *   label_collection = @Translation("Kategorie aktualności"),
 *   label_singular = @Translation("kategorie aktualności"),
 *   label_plural = @Translation("kategorie aktualności"),
 *   label_count = @PluralTranslation(
 *     singular = "@count kategorie aktualności",
 *     plural = "@count kategorie aktualności",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\aktualnosci_kategorie\AktualnosciKategorieListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\aktualnosci_kategorie\Form\AktualnosciKategorieForm",
 *       "edit" = "Drupal\aktualnosci_kategorie\Form\AktualnosciKategorieForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\aktualnosci_kategorie\Routing\AktualnosciKategorieHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "aktualnosci_kategorie",
 *   data_table = "aktualnosci_kategorie_field_data",
 *   revision_table = "aktualnosci_kategorie_revision",
 *   revision_data_table = "aktualnosci_kategorie_field_revision",
 *   show_revision_ui = TRUE,
 *   translatable = TRUE,
 *   admin_permission = "administer article tags",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "revision_id",
 *     "langcode" = "langcode",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *     "owner" = "uid",
 *   },
 *   revision_metadata_keys = {
 *     "revision_user" = "revision_uid",
 *     "revision_created" = "revision_timestamp",
 *     "revision_log_message" = "revision_log",
 *   },
 *   links = {
 *     "collection" = "/admin/content/kategorie-aktualnosci",
 *     "add-form" = "/kategorie-aktualnosci/add",
 *     "canonical" = "/kategorie-aktualnosci/{aktualnosci_kategorie}",
 *     "edit-form" = "/kategorie-aktualnosci/{aktualnosci_kategorie}",
 *     "delete-form" = "/kategorie-aktualnosci/{aktualnosci_kategorie}/delete",
 *   },
 *   field_ui_base_route = "entity.aktualnosci_kategorie.settings",
 * )
 */
class AktualnosciKategorie extends RevisionableContentEntityBase implements AktualnosciKategorieInterface {

  use EntityChangedTrait;
  use EntityOwnerTrait;

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);
    if (!$this->getOwnerId()) {
      // If no owner has been set explicitly, make the anonymous user the owner.
      $this->setOwnerId(0);
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['label'] = BaseFieldDefinition::create('string')
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE)
      ->setLabel(t('Label'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setRevisionable(TRUE)
      ->setLabel(t('Status'))
      ->setDefaultValue(TRUE)
      ->setSetting('on_label', 'Enabled')
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'settings' => [
          'display_label' => FALSE,
        ],
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'boolean',
        'label' => 'above',
        'weight' => 0,
        'settings' => [
          'format' => 'enabled-disabled',
        ],
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE)
      ->setLabel(t('Author'))
      ->setSetting('target_type', 'user')
      ->setDefaultValueCallback(static::class . '::getDefaultEntityOwner')
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => 60,
          'placeholder' => '',
        ],
        'weight' => 15,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'author',
        'weight' => 15,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on'))
      ->setTranslatable(TRUE)
      ->setDescription(t('The time that the kategorie aktualności was created.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setTranslatable(TRUE)
      ->setDescription(t('The time that the kategorie aktualności was last edited.'));

    return $fields;
  }

  // get values from aktualnosci_kategorie field
  public function getId() {
    return $this->id();

    //get this id
    // $id = $this->id();


  }
}
