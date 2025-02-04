<?php

namespace Drupal\aktorzy_tags;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a kategorie aktorów entity type.
 */
interface AktorzyTagsInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {
}
