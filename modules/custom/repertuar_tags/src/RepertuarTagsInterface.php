<?php

namespace Drupal\repertuar_tags;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a tagi repertuaru entity type.
 */
interface RepertuarTagsInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {
}
