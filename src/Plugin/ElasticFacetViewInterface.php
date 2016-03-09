<?php

/**
 * @file
 * Contains \Drupal\elasticsearch_connector_blocks\Plugin\ElasticFacetViewInterface.
 */

namespace Drupal\elasticsearch_connector_blocks\Plugin;

use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * Defines an interface for Elastic facet view plugins.
 */
interface ElasticFacetViewInterface extends PluginInspectionInterface {

  public function setFacets($buckets);
}