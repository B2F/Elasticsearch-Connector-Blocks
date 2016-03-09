<?php

namespace Drupal\elasticsearch_connector_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityViewBuilderInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Drupal\elasticsearch_connector_blocks\Plugin\ElasticFacetViewManager;

/**
 * Provides a 'ElasticsearchFacetBlock' block plugin.
 *
 * @Block(
 *   id = "elasticsearch_facet_block",
 *   admin_label = @Translation("Elasticsearch Facet Block"),
 *   deriver = "Drupal\elasticsearch_connector_blocks\Plugin\Derivative\ElasticsearchFacetBlock"
 * )
 */
class ElasticsearchFacetBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var ElasticFacetViewManager.
   */
  private $facetViewkManager;

  /**
   * Creates a NodeBlock instance.
   *
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param EntityManagerInterface $entity_manager
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ElasticFacetViewManager $facetViewManager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->facetViewkManager = $facetViewManager;
    $definition = $this->getPluginDefinition();
    $this->index = $definition['facet_index'];
    $this->facetId = $definition['facet_name'];
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('plugin.manager.elastic_facet_view.processor')
    );
  }

  public function getIndex() {
    return $this->index;
  }

  public function getFacetId() {
    return $this->facetId;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = array(
      '#cache' => array(
        'max-age' => 0,
      )
    );
    $facetViewPlugin = $this->facetViewkManager->createInstance('basicLinks');
    $build[] = $facetViewPlugin->setFacets(array('one', 'two', 'three', 'four', 'five'));
    return $build;
  }

  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    return array();
  }
}