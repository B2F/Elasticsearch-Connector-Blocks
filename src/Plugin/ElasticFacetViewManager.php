<?php

/**
 * @file
 * Contains \Drupal\elasticsearch_connector_blocks\Plugin\ElasticFacetViewManager.
 */

namespace Drupal\elasticsearch_connector_blocks\Plugin;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Provides the Elastic facet view plugin manager.
 */
class ElasticFacetViewManager extends DefaultPluginManager {

  /**
   * Constructor for ElasticFacetViewManager objects.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   *
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   *
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/ElasticFacetView', $namespaces, $module_handler, 'Drupal\elasticsearch_connector_blocks\Plugin\ElasticFacetViewInterface', 'Drupal\elasticsearch_connector_blocks\Annotation\ElasticFacetView');

    $this->alterInfo('elasticsearch_connector_blocks_elastic_facet_view_info');
    $this->setCacheBackend($cache_backend, 'elasticsearch_connector_blocks_elastic_facet_view_plugins');
  }
}