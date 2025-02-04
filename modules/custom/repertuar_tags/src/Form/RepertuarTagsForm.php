<?php

namespace Drupal\repertuar_tags\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the tagi repertuaru entity edit forms.
 */
class RepertuarTagsForm extends ContentEntityForm {

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
        $this->messenger()->addStatus($this->t('New tagi repertuaru %label has been created.', $message_arguments));
        $this->logger('repertuar_tags')->notice('Created new tagi repertuaru %label', $logger_arguments);
        break;

      case SAVED_UPDATED:
        $this->messenger()->addStatus($this->t('The tagi repertuaru %label has been updated.', $message_arguments));
        $this->logger('repertuar_tags')->notice('Updated tagi repertuaru %label.', $logger_arguments);
        break;
    }

    $form_state->setRedirect('entity.repertuar_tags.collection');

    return $result;
  }
}
