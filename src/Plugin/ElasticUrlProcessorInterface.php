<?php

/**
 * @file
 * Contains \Drupal\elasticsearch_connector_blocks\Plugin\ElasticUrlProcessorInterface.
 */

namespace Drupal\elasticsearch_connector_blocks\Plugin;

use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * Defines an interface for Elastic url processor plugins.
 */
interface ElasticUrlProcessorInterface extends PluginInspectionInterface {

  public function getLink($filters, $facet);
}
