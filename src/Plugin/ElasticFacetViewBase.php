<?php

/**
 * @file
 * Contains \Drupal\elasticsearch_connector_blocks\Plugin\ElasticFacetViewBase.
 */

namespace Drupal\elasticsearch_connector_blocks\Plugin;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use \Drupal\Core\Link;
use \Drupal\Core\Url;

/**
 * Base class for Elastic facet view plugins.
 */
abstract class ElasticFacetViewBase extends PluginBase implements ElasticFacetViewInterface {

  public function setFacets($buckets) {
    $links = array(
      '#theme' => 'item_list',
      '#items' => array(),
    );
    if ($buckets) {
      foreach ($buckets as $key => $facet) {
        $link = Link::fromTextAndUrl($facet, Url::fromUri('http://example.com'));
        $links['#items'][] = $link->toRenderable();
      }
    }
    return $links;
  }
}