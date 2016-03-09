<?php

/**
 * @file
 * Contains \Drupal\elasticsearch_connector_blocks\Plugin\ElasticUrlProcessorManager.
 */

namespace Drupal\elasticsearch_connector_blocks\Plugin;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Provides the Elastic url processor plugin manager.
 */
class ElasticUrlProcessorManager extends DefaultPluginManager {

  /**
   * Constructor for ElasticUrlProcessorManager objects.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/ElasticUrlProcessor', $namespaces, $module_handler, 'Drupal\elasticsearch_connector_blocks\Plugin\ElasticUrlProcessorInterface', 'Drupal\elasticsearch_connector_blocks\Annotation\ElasticUrlProcessor');

    $this->alterInfo('elasticsearch_connector_blocks_elastic_url_processor_info');
    $this->setCacheBackend($cache_backend, 'elasticsearch_connector_blocks_elastic_url_processor_plugins');
  }

}
