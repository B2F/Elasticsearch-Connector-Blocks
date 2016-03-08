<?php

/**
 * @file
 * Contains \Drupal\elasticsearch_connector_blocks\ElasticsearchFacetBlockConfigListBuilder.
 */

namespace Drupal\elasticsearch_connector_blocks;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of Elasticsearch facet block config entities.
 */
class ElasticsearchFacetBlockConfigListBuilder extends ConfigEntityListBuilder {
  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Elasticsearch facet block config');
    $header['id'] = $this->t('Machine name');
    $header['facet_key'] = $this->t('Facet Key');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $entity->label();
    $row['id'] = $entity->id();
    $row['facet_key'] = $entity->get('facetKey');
    // You probably want a few more properties here...
    return $row + parent::buildRow($entity);
  }
}