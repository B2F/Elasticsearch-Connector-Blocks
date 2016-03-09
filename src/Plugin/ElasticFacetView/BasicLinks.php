<?php

namespace Drupal\elasticsearch_connector_blocks\Plugin\ElasticFacetView;

use Drupal\elasticsearch_connector_blocks\Plugin\ElasticFacetViewBase;

/**
 * Provides a basic links facet view.
 *
 * @ElasticFacetView(
 *   id = "basicLinks",
 *   name = @Translation("Basic Links")
 * )
 */
class basicLinks extends ElasticFacetViewBase {}