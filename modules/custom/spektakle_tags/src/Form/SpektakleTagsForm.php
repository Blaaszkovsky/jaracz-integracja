<?php

namespace Drupal\spektakle_tags\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the sceny spektakli entity edit forms.
 */
class SpektakleTagsForm extends ContentEntityForm {

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
        $this->messenger()->addStatus($this->t('New sceny spektakli %label has been created.', $message_arguments));
        $this->logger('spektakle_tags')->notice('Created new sceny spektakli %label', $logger_arguments);
        break;

      case SAVED_UPDATED:
        $this->messenger()->addStatus($this->t('The sceny spektakli %label has been updated.', $message_arguments));
        $this->logger('spektakle_tags')->notice('Updated sceny spektakli %label.', $logger_arguments);
        break;
    }

    $form_state->setRedirect('entity.spektakle_tags.collection');

    return $result;
  }
}
