<?php

/**
 * @file
 * Contains \Drupal\elasticsearch_connector_blocks\ElasticsearchSearchBlockConfigListBuilder.
 */

namespace Drupal\elasticsearch_connector_blocks;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of Elasticsearch search block config entities.
 */
class ElasticsearchSearchBlockConfigListBuilder extends ConfigEntityListBuilder {
  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Elasticsearch search block config');
    $header['id'] = $this->t('Machine name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $entity->label();
    $row['id'] = $entity->id();
    // You probably want a few more properties here...
    return $row + parent::buildRow($entity);
  }

}
