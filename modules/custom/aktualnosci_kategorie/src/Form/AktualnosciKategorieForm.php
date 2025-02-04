<?php

namespace Drupal\aktualnosci_kategorie\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the kategorie aktualności entity edit forms.
 */
class AktualnosciKategorieForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $result = parent::save($form, $form_state);

    $entity = $this->getEntity();

    $message_arguments = ['%label' => $entity->toLink()->toString()];
    $logger_arguments = [
      '%label' => $entity->label(),
      'link' => $entity->toLink($this->t('View'))->toString(),
    ];

    switch ($result) {
      case SAVED_NEW:
        $this->messenger()->addStatus($this->t('New kategorie aktualności %label has been created.', $message_arguments));
        $this->logger('aktualnosci_kategorie')->notice('Created new kategorie aktualności %label', $logger_arguments);
        break;

      case SAVED_UPDATED:
        $this->messenger()->addStatus($this->t('The kategorie aktualności %label has been updated.', $message_arguments));
        $this->logger('aktualnosci_kategorie')->notice('Updated kategorie aktualności %label.', $logger_arguments);
        break;
    }

    $form_state->setRedirect('entity.aktualnosci_kategorie.collection');

    return $result;
  }
}
