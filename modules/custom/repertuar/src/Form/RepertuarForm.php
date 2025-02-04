<?php

namespace Drupal\repertuar\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the tagi repertuaru entity edit forms.
 */
class RepertuarForm extends ContentEntityForm {

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
        $this->messenger()->addStatus($this->t('New pozycja repertuaru %label has been created.', $message_arguments));
        $this->logger('repertuar')->notice('Created new pozycja repertuaru %label', $logger_arguments);
        break;

      case SAVED_UPDATED:
        $this->messenger()->addStatus($this->t('The pozycja repertuaru %label has been updated.', $message_arguments));
        $this->logger('repertuar')->notice('Updated pozycja repertuaru %label.', $logger_arguments);
        break;
    }

    $form_state->setRedirect('entity.repertuar.collection');

    return $result;
  }
}
