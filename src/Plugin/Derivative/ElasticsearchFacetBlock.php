<?php

/**
 * @file
 * Contains \Drupal\elasticsearch_connector_blocks\Plugin\Derivative\ElasticsearchFacetBlock.
 */

namespace Drupal\elasticsearch_connector_blocks\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
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
  public function __construct(EntityManagerInterface $entity_manager) {
    $this->configStorage = $entity_manager->getStorage('elasticsearch_facet_block_config');
  }

  /*
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('entity.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {

    $facetBlockConfig = $this->configStorage->loadMultiple();

    foreach ($facetBlockConfig as $block_definition) {
      $key_tokens = explode(':', $block_definition->get('facetKey'));
      $this->derivatives[$block_definition->id()] = $base_plugin_definition;
      $this->derivatives[$block_definition->id()]['facet_index'] = $key_tokens[0];
      $this->derivatives[$block_definition->id()]['facet_name'] = $key_tokens[1];
      $this->derivatives[$block_definition->id()]['admin_label'] = t('Elasticsearch facet block: ') . $block_definition->id();
    }
    return $this->derivatives;
  }
}