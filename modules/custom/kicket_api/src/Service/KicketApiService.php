<?php

namespace Drupal\kicket_api\Service;

use GuzzleHttp\Client;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\repertuar\Entity\Repertuar;

/**
 * Serwis do obsługi Kicket API.
 */
class KicketApiService
{

    protected $client;
    protected $config;
    protected $entityTypeManager;

    public function __construct(Client $client, ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entity_type_manager)
    {
        $this->client = $client;
        $this->config = $config_factory->get('kicket_api.settings');
        $this->entityTypeManager = $entity_type_manager;
    }

    /**
     * Pobiera dane z API Kicket.
     */
    public function fetchOffers()
    {
        $sales_channel_id = $this->config->get('sales_channel_id');
        $venue = $this->config->get('venue');
        $today_date = date('Y-m-d');

        if (!$sales_channel_id || !$venue) {
            \Drupal::logger('kicket_api')->warning('Brak ustawionego Sales Channel ID lub Venue.');
            return NULL;
        }

        $api_url = "https://kicket.com/api/affiliates/{$sales_channel_id}/offers?periodFrom={$today_date}&periodTo=2099-12-31&venue={$venue}";

        try {
            $response = $this->client->request('GET', $api_url, ['timeout' => 10]);
            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody(), true);
            }
        } catch (\Exception $e) {
            \Drupal::logger('kicket_api')->error('API error: @message', ['@message' => $e->getMessage()]);
        }

        return NULL;
    }

    private function checkRelatedEvents($event_show_id)
    {
        $sales_channel_id = $this->config->get('sales_channel_id');
        $venue = $this->config->get('venue');
        $today_date = date('Y-m-d');

        $show_url = "https://kicket.com/api/affiliates/{$sales_channel_id}/offers?periodFrom={$today_date}&periodTo=2099-12-31&venue={$venue}&showId={$event_show_id}";
        try {
            $response = $this->client->request('GET', $show_url, ['timeout' => 10]);
            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
            }
        } catch (\Exception $e) {
            \Drupal::logger('kicket_api')->error('API error: @message', ['@message' => $e->getMessage()]);
        }

        if (empty($data) || count($data) <= 1) {
            return false;
        }

        foreach ($data as $related_event) {
            if (!empty($related_event['availablePlaces']) && $related_event['availablePlaces'] === true) {
                return true; 
            }
        }

        return false;
    }

    /**
     * Aktualizuje lub tworzy nową stronę w encji "Repertuar" na podstawie API.
     */
    public function updateRepertuar($offers)
    {
        if (!$offers) {
            \Drupal::logger('kicket_api')->warning('Brak danych do aktualizacji repertuaru.');
            return;
        }

        $storage = $this->entityTypeManager->getStorage('repertuar');
        $sales_channel_id = $this->config->get('sales_channel_id');

        foreach ($offers as $offer) {
            $api_id = $offer['id'];
            $title = $offer['subtitle'] ? trim(str_replace($offer['subtitle'], '', $offer['title'])) : $offer['title'];
            $existing_entities = $storage->loadByProperties(['field_api_id' => $api_id]);

            if (!empty($existing_entities)) {
                $entity = reset($existing_entities);
            } else {
                $entity = Repertuar::create([
                    'label' => $title,
                    'field_api_id' => $api_id,
                    'status' => 1, // Jeśli wymagane
                    'created' => \Drupal::time()->getRequestTime(),
                ]);

                $node_storage = $this->entityTypeManager->getStorage('node');
                $query = $node_storage->getQuery()
                    ->accessCheck(TRUE)
                    ->condition('title', $title)
                    ->condition('type', 'spektakl')
                    ->execute();

                if (!empty($query)) {
                    $sreferencja_id = reset($query);
                    $entity->set('field_sreferencja', ['target_id' => $sreferencja_id]);
                }
            }

            // Przypisanie wartości do pól.
            $entity->set('field_api_id', $api_id);
            $entity->set('field_price', $offer['price']);
            $entity->set('field_event_title', $title);

            $timestamp = strtotime($offer['date']);
            $date = \Drupal::service('date.formatter')->format($timestamp, 'custom', 'Y-m-d\TH:i:s', 'UTC');
            $entity->set('field_data_i_czas', $date);
        
            $entity->set('field_odnosnik_do_biletow', $offer['reservationForm']);
            $entity->set('field_cancelled', (bool) $offer['cancelled']);
            $entity->set('field_available_places', $offer['availablePlacesQuantity']);
            $entity->set('field_cast', $offer['cast']);
            $entity->set('field_picture_url', $offer['pictureUrl'] ? 'https://kicket.com' . $offer['posterUrl'] : '');
            $entity->set('field_reservation_form', $offer['reservationForm']);
            $entity->set('field_scene_name', $offer['sceneName']);
            $entity->set('field_related_events', $this->checkRelatedEvents($offer['show']['id']));
            $entity->set('field_show_url', "https://kicket.com/embeddables/repertoire?organizerId={$offer['venue']['id']}&showId={$offer['show']['id']}&salesChannelId={$sales_channel_id}");

            $entity->save();
        }
    }
}
