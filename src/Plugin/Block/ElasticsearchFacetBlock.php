<?php

namespace Drupal\elasticsearch_connector_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityViewBuilderInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'NodeBlock' block plugin.
 *
 * @Block(
 *   id = "elasticsearch_facet_block",
 *   admin_label = @Translation("Elasticsearch Facet Block"),
 *   deriver = "Drupal\elasticsearch_connector_blocks\Plugin\Derivative\ElasticsearchFacetBlock"
 * )
 */
class ElasticsearchFacetBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var EntityViewBuilderInterface.
   */
  private $viewBuilder;

  /**
   * @var NodeInterface.
   */
  private $node;

  /**
   * Creates a NodeBlock instance.
   *
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param EntityManagerInterface $entity_manager
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityManagerInterface $entity_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = array();
    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function blockAccess(AccountInterface $account, $return_as_object = FALSE) {
    return TRUE;
  }
}