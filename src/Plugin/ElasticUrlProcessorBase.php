<?php

/**
 * @file
 * Contains \Drupal\elasticsearch_connector_blocks\Plugin\ElasticUrlProcessorBase.
 */

namespace Drupal\elasticsearch_connector_blocks\Plugin;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Url;

/**
 * Base class for Elastic url processor plugins.
 */
abstract class ElasticUrlProcessorBase extends PluginBase implements ElasticUrlProcessorInterface {

  protected $base_url;
  protected $request_uri;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    global $base_url;
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->base_url = $base_url;
    $this->requestUri = \Drupal::request()->getRequestUri();
  }

  public function filtersToUrl($facet, $field) {

    $filters = array();
    $options['query'] = $_GET;

    if (!isset($options['query'][$field]) || !is_array($options['query'][$field])) {
      $options['query'][$field] = array();
    }

    $array_key = array_search($facet['key'], $options['query'][$field]);

    if ($array_key !== FALSE) {
      unset($options['query'][$field][$array_key]);
    }
    else {
      $options['query'][$field][] = $facet['key'];
    }

    return Url::fromUri('internal:/', $options);
  }

  public function urlToFilters($fields) {
    $filters = array();
    foreach ($fields as $field) {
      if (isset($_GET[$field])) {
        $filters[$field] = $_GET[$field];
      }
    }
    var_dump($filters);
    return $filters;
  }
}