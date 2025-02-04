<?php

namespace Drupal\aktualnosci_kategorie;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a kategorie aktualności entity type.
 */
interface AktualnosciKategorieInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {
}
