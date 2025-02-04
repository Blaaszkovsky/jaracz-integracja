<?php

namespace Drupal\cg2_deklaracja_dostepnosci\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an example block.
 *
 * @Block(
 *   id = "cg2_deklaracja_dostepnosci_example",
 *   admin_label = @Translation("Example"),
 *   category = @Translation("CG2 Deklaracja dostępności")
 * )
 */
class ExampleBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build['content'] = [
      '#markup' => $this->t('It works!'),
    ];
    return $build;
  }

}
