<?php

namespace Drupal\kicket_api\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\kicket_api\Service\KicketApiService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\node\Entity\Node;

/**
 * Formularz ustawień API Kicket z opcją odświeżania repertuaru.
 */
class KicketApiSettingsForm extends ConfigFormBase
{

    /**
     * Serwis API Kicket.
     */
    protected $kicketApiService;

    /**
     * {@inheritdoc}
     */
    public function __construct(KicketApiService $kicket_api_service)
    {
        $this->kicketApiService = $kicket_api_service;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('kicket_api.service')
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames()
    {
        return ['kicket_api.settings'];
    }

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'kicket_api_settings_form';
    }

    /**
     * Tworzy formularz ustawień API i przycisk odświeżania repertuaru.
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->config('kicket_api.settings');

        $default_node = $config->get('target_page');
        $default_value = NULL;
        if ($default_node && $node = Node::load($default_node)) {
            $default_value = $node;
        }

        // Sales Channel ID
        $form['sales_channel_id'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Sales Channel ID'),
            '#default_value' => $config->get('sales_channel_id'),
            '#required' => TRUE,
        ];

        // Venue ID
        $form['venue'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Venue ID'),
            '#default_value' => $config->get('venue'),
            '#required' => TRUE,
        ];

        // Kolory nakładki sprzedażowej
        $form['primary_color'] = [
            '#type' => 'color',
            '#title' => $this->t('Kolor podstawowy'),
            '#default_value' => $config->get('primary_color', '#FFFFFF'),
            '#description' => $this->t('Zmienia kolor w nakładce sprzedażowej.'),
            '#required' => TRUE,
        ];

        $form['secondary_color'] = [
            '#type' => 'color',
            '#title' => $this->t('Kolor pośredni'),
            '#default_value' => $config->get('secondary_color', '#CCCCCC'),
            '#description' => $this->t('Zmienia kolor w nakładce sprzedażowej.'),
            '#required' => TRUE,
        ];

        $form['background_color'] = [
            '#type' => 'color',
            '#title' => $this->t('Kolor tła'),
            '#default_value' => $config->get('background_color', '#000000'),
            '#description' => $this->t('Zmienia kolor tła w nakładce sprzedażowej.'),
            '#required' => TRUE,
        ];

        // Obsługa WCAG
        $form['enable_WCAG'] = [
            '#type' => 'checkbox',
            '#title' => $this->t('Obsługa WCAG'),
            '#default_value' => $config->get('enable_WCAG', FALSE),
            '#description' => $this->t('Włącza na nakładce sprzedażowej widżet do obsługi niepełnosprawności.'),
        ];

        // Wybór strony docelowej dla JavaScript
        $form['target_page'] = [
            '#type' => 'entity_autocomplete',
            '#title' => $this->t('Strona docelowa dla JavaScript'),
            '#target_type' => 'node',
            '#selection_handler' => 'default',
            '#default_value' => $default_value,
            '#description' => $this->t('Wybierz stronę, na której ma być dodany niestandardowy JavaScript.'),
            '#required' => FALSE,
        ];

        // Przycisk odświeżania repertuaru
        $form['refresh_repertuar'] = [
            '#type' => 'submit',
            '#value' => $this->t('Odśwież repertuar'),
            '#submit' => ['::refreshRepertuar'],
            '#attributes' => ['class' => ['button--primary']],
        ];

        return parent::buildForm($form, $form_state);
    }

    /**
     * Zapisuje ustawienia API.
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $this->config('kicket_api.settings')
            ->set('sales_channel_id', $form_state->getValue('sales_channel_id'))
            ->set('venue', $form_state->getValue('venue'))
            ->set('primary_color', $form_state->getValue('primary_color'))
            ->set('secondary_color', $form_state->getValue('secondary_color'))
            ->set('background_color', $form_state->getValue('background_color'))
            ->set('enable_WCAG', $form_state->getValue('enable_WCAG'))
            ->set('target_page', $form_state->getValue('target_page'))
            ->save();

        parent::submitForm($form, $form_state);
    }

    /**
     * Pobiera dane z API Kicket i aktualizuje repertuar.
     */
    public function refreshRepertuar(array &$form, FormStateInterface $form_state)
    {
        $offers = $this->kicketApiService->fetchOffers();

        if ($offers) {
            $this->kicketApiService->updateRepertuar($offers);
            $this->messenger()->addStatus($this->t('Repertuar został zaktualizowany.'));
        } else {
            $this->messenger()->addError($this->t('Nie udało się pobrać danych z API.'));
        }
    }

}
