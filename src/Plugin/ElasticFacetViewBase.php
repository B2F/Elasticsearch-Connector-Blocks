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
use Drupal\facets\Exception\Exception;

/**
 * Base class for Elastic facet view plugins.
 */
abstract class ElasticFacetViewBase extends PluginBase implements ElasticFacetViewInterface {

  public function setLinks($facets) {

    $links = array(
      '#theme' => 'item_list',
      '#items' => array(),
    );
    if ($facets) {
      foreach ($facets as $facet) {
        $label = $facet['title'] . ' (' . $facet['count'] . ')';
        $link = Link::fromTextAndUrl($label, Url::fromUri($facet['link']));
        $links['#items'][] = $link->toRenderable();
      }
    }
    return $links;
  }
}