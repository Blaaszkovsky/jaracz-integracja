<?php

namespace Drupal\spektakle_tags;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a sceny spektakli entity type.
 */
interface SpektakleTagsInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {
}
