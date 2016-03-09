<?php

/**
 * @file
 * Contains \Drupal\elasticsearch_connector_blocks\Plugin\ElasticUrlProcessorBase.
 */

namespace Drupal\elasticsearch_connector_blocks\Plugin;

use Drupal\Component\Plugin\PluginBase;

/**
 * Base class for Elastic url processor plugins.
 */
abstract class ElasticUrlProcessorBase extends PluginBase implements ElasticUrlProcessorInterface {

  public function getLink($filters, $bucket) {
    global $base_url;
    return $base_url;
  }
}