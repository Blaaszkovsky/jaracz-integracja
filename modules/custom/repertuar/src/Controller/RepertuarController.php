<?php

/**
 * @file
 * Contains \Drupal\repertual\Controller\RepertuarController.
 */

namespace Drupal\repertuar\Controller;

use Drupal\Core\Controller\ControllerBase;

class RepertuarController extends ControllerBase {
  public function welcome() {
    // return array(
    //   '#markup' => 'Welcome to our Website.'
    // );
  }

  public function content() {

    // die('asdasdasdasdasdasdasd');
    return [
      '#theme' => 'repertuar',
      '#test_variable' => 'Test Value',
    ];
  }
}
