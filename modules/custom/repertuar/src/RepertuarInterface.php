<?php

namespace Drupal\repertuar;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a tagi repertuaru entity type.
 */
interface RepertuarInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {
}
