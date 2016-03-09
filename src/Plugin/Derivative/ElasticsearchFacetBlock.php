<?php

/**
 * @file
 * Contains \Drupal\elasticsearch_connector_blocks\Plugin\Derivative\ElasticsearchFacetBlock.
 */

namespace Drupal\elasticsearch_connector_blocks\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides block plugin definitions for ElasticsearchFacetBlock.
 */
class ElasticsearchFacetBlock extends DeriverBase implements ContainerDeriverInterface {

  private $configStorage;

  /**
   * Creates a ElasticSearchFacetBlock instance.
   *
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param EntityManagerInterface $entity_manager
   */
  public function __construct(EntityTypeManager $entity_manager) {
    $this->configStorage = $entity_manager->getStorage('elasticsearch_facet_block_config');
  }

  /*
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {

    $facetBlockConfig = $this->configStorage->loadMultiple();

    foreach ($facetBlockConfig as $block_definition) {
      $this->derivatives[$block_definition->id()] = $base_plugin_definition;
      $this->derivatives[$block_definition->id()]['facet_index'] = $block_definition->getIndex();
      $this->derivatives[$block_definition->id()]['facet_name'] = $block_definition->getFacetField();
      $this->derivatives[$block_definition->id()]['admin_label'] = t('Elasticsearch facet block: ') . $block_definition->id();
    }
    return $this->derivatives;
  }
}