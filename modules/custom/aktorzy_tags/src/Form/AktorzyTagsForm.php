<?php

namespace Drupal\aktorzy_tags\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the kategorie aktorów entity edit forms.
 */
class AktorzyTagsForm extends ContentEntityForm {

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
        $this->messenger()->addStatus($this->t('New kategorie aktorów %label has been created.', $message_arguments));
        $this->logger('aktorzy_tags')->notice('Created new kategorie aktorów %label', $logger_arguments);
        break;

      case SAVED_UPDATED:
        $this->messenger()->addStatus($this->t('The kategorie aktorów %label has been updated.', $message_arguments));
        $this->logger('aktorzy_tags')->notice('Updated kategorie aktorów %label.', $logger_arguments);
        break;
    }

    $form_state->setRedirect('entity.aktorzy_tags.collection');

    return $result;
  }
}
