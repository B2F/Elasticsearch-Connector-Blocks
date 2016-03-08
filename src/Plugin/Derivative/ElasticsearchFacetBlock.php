<?php

/**
 * @file
 * Contains \Drupal\elasticsearch_connector_blocks\Plugin\Derivative\ElasticsearchFacetBlock.
 */

namespace Drupal\elasticsearch_connector_blocks\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides block plugin definitions for ElasticsearchFacetBlock.
 */
class ElasticsearchFacetBlock extends DeriverBase implements ContainerDeriverInterface {

  /*
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
        $container->get('entity.manager')->getStorage('node')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
//        $nodes = $this->nodeStorage->loadByProperties(['type' => 'article']);
//        foreach ($nodes as $node) {
//            $this->derivatives[$node->id()] = $base_plugin_definition;
//            $this->derivatives[$node->id()]['admin_label'] = t('Node block: ') . $node->label();
//            $this->derivatives[$node->id()]['properties'] = array('random data');
//        }
//        return $this->derivatives;
  }
}