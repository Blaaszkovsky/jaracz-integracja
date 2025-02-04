<?php

namespace Drupal\repertuar;

use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a list controller for the tagi repertuaru entity type.
 */
class RepertuarListBuilder extends EntityListBuilder {

  /**
   * The date formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * Constructs a new RepertuarListBuilder object.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type definition.
   * @param \Drupal\Core\Entity\EntityStorageInterface $storage
   *   The entity storage class.
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The date formatter service.
   */
  public function __construct(EntityTypeInterface $entity_type, EntityStorageInterface $storage, DateFormatterInterface $date_formatter) {
    parent::__construct($entity_type, $storage);
    $this->dateFormatter = $date_formatter;
  }

  public function load() {

    $storage = $this->getStorage();

    // Utwórz zapytanie do pobrania identyfikatorów encji posortowanych według ostatnio edytowanych.
    $query = $storage->getQuery()
      ->accessCheck(TRUE)
      ->pager(50)
      ->sort('changed', 'DESC');

    // Pobierz identyfikatory encji.
    $entity_ids = $query->execute();

    // Pobierz encje na podstawie identyfikatorów.
    return $storage->loadMultiple($entity_ids);
  }

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('entity_type.manager')->getStorage($entity_type->id()),
      $container->get('date.formatter')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function render() {
    $build['table'] = parent::render();

    $total = $this->getStorage()
      ->getQuery()
      ->accessCheck(FALSE)
      ->count()
      ->execute();

    $build['summary']['#markup'] = $this->t('Total repertuar: @total', ['@total' => $total]);

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('ID');
    $header['label'] = $this->t('Label');
    $header['data1'] = $this->t('Data');
    $header['status'] = $this->t('Status');
    $header['kicket'] = $this->t('Kicket API');

    // $header['changed'] = [
    //   'data' => $this->t('Edytowano'),
    //   // 'field' => 'id',
    //   // 'specifier' => 'id',
    //   'class' => array(RESPONSIVE_PRIORITY_LOW),
    // ];





    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\repertuar\RepertuarInterface $entity */

    $dates = [];

    foreach ($entity->get('field_data_i_czas')->getValue() as $k => $v) {
      $dates[] = date('d.m.Y H:i', strtotime($v['value'] . ' +0000'));
    }

    // dump($entity->get('field_sreferencja')->getValue());

    // get node by nid
    $field_sreferencja = $entity->get('field_sreferencja')->getValue();
    $event_title = $entity->get('field_event_title')->getValue();

    $node = NULL;

    if (!empty($field_sreferencja) && isset($field_sreferencja[0]['target_id'])) {
      $node = \Drupal\node\Entity\Node::load($field_sreferencja[0]['target_id']);
    }

    // dump($node);
    // get node title

    if (isset($event_title[0]['value']) && !$node) {
      $title = $event_title[0]['value'] . " (brak powiązania)";
    } elseif(!$node) {
      $title = '-- Brak zasobu --';
    } else {
      $title = $node->title->value;
    }

    usort($dates, function ($a, $b) {
      return strtotime($a) <=> strtotime($b);
    });

    $row['id'] = $entity->id();
    $row['label'] = $title;
    $row['data1'] = implode(', ', $dates);

    $row['status'] = $entity->get('status')->value ? $this->t('Enabled') : $this->t('Disabled');
    $row['kicket'] = $entity->get('field_api_id')->value ? $this->t('Enabled') : $this->t('Disabled');

    // $row['changed'] = $this->dateFormatter->format($entity->getChangedTime(), 'short');


    return $row + parent::buildRow($entity);
  }
}
