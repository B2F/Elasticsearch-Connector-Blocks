<?php

/**
 * @file
 * Contains \Drupal\elasticsearch_connector_blocks\Annotation\ElasticUrlProcessor.
 */

namespace Drupal\elasticsearch_connector_blocks\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Elastic url processor item annotation object.
 *
 * @see \Drupal\elasticsearch_connector_blocks\Plugin\ElasticUrlProcessorManager
 * @see plugin_api
 *
 * @Annotation
 */
class ElasticUrlProcessor extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The label of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

}
