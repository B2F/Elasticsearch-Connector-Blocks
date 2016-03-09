<?php

namespace Drupal\elasticsearch_connector_blocks\Plugin\ElasticUrlProcessor;

use Drupal\elasticsearch_connector_blocks\Plugin\ElasticUrlProcessorBase;

/**
 * Provides a basic links facet view.
 *
 * @ElasticUrlProcessor(
 *   id = "defaultProcessor",
 *   name = @Translation("Default Processor")
 * )
 */
class DefaultProcessor extends ElasticUrlProcessorBase {}