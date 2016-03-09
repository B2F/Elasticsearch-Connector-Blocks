<?php

/**
 * @file
 * Contains \Drupal\elasticsearch_connector_blocks\Annotation\ElasticFacetView.
 */

namespace Drupal\elasticsearch_connector_blocks\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Elastic facet view item annotation object.
 *
 * @see \Drupal\elasticsearch_connector_blocks\Plugin\ElasticFacetViewManager
 * @see plugin_api
 *
 * @Annotation
 */
class ElasticFacetView extends Plugin {

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
