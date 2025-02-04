<?php

namespace Drupal\freshmail_integration_cg2\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Freshmail Integration CG2 settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'freshmail_integration_cg2_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['freshmail_integration_cg2.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['token'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Token'),
      '#default_value' => $this->config('freshmail_integration_cg2.settings')->get('token'),
      '#description' => t('Token dostępu do API Freshmaila. Można go wygenerować w panelu Freshmaila w zakładce <a href="https://app.freshmail.com/pl/settings/apismtp/" target="_blank">API & SMPT</a>.'),
    ];

    $form['list'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Hash listy'),
      '#default_value' => $this->config('freshmail_integration_cg2.settings')->get('list'),
      '#description' => t('Hash listy kontaktów. Należy wejść na stronę <a href="https://app.freshmail.com/pl/lists/index/" target="_blank">Odbiorcy</a> w panelu Freshmail, wybrać odpowiednią listę i skopiować link do niej.'),
    ];

    $form['form'] = [
      '#type' => 'textfield',
      '#title' => $this->t('ID formularza'),
      '#default_value' => $this->config('freshmail_integration_cg2.settings')->get('form'),
      '#description' => t('ID formularza możesz uzyskać dodając w MYTHEME_preprocess_webform instrukcję dump($variables[\'attributes\'][\'id\']);'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    foreach (['token', 'list', 'form'] as $v) {
      if (empty($form_state->getValue($v))) {
        $form_state->setErrorByName($v, $this->t('Pole nie może być puste.'));
      }
    }

    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $list = $form_state->getValue('list');

    if (strpos($list, 'id_hash=') !== false) {
      $hashPos = strpos($list, '#') > 0 ? strpos($list, '#') : strlen($list);

      $list = substr($list, 0, $hashPos);

      $list = explode('id_hash=', $list);
      $list = $list[1];
    }

    $form = $form_state->getValue('form');
    $form = str_replace('-', '_', $form);

    $this->config('freshmail_integration_cg2.settings')
      ->set('token', $form_state->getValue('token'))
      ->set('list', $list)
      ->set('form', $form)
      ->save();
    parent::submitForm($form, $form_state);
  }
}
