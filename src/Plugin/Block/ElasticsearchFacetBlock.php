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
use Drupal\elasticsearch_connector_blocks\EventSubscriber\ElasticsearchBlocksEventSubscriber;
use Drupal\elasticsearch_connector_blocks\Plugin\ElasticUrlProcessorManager;

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

  private $definition;

  /**
   * @var ElasticsearchBlocksEventSubscriber
   */
  private $eventSubscriber;

  /**
   * @var ElasticFacetViewManager.
   */
  private $facetViewkManager;

  /**
   * @var ElasticUrlProcessor
   */
  private $elasticUrlProcessor;

  /**
   * Creates a NodeBlock instance.
   *
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param EntityManagerInterface $entity_manager
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ElasticFacetViewManager $facetViewManager, ElasticsearchBlocksEventSubscriber $eventSubscriber, ElasticUrlProcessorManager $elasticUrlProcessorManager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->eventSubscriber = $eventSubscriber;
    $this->facetViewkManager = $facetViewManager;
    $this->definition = $this->getPluginDefinition();
    $this->urlProcessorManager = $elasticUrlProcessorManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('plugin.manager.elastic_facet_view.processor'),
      $container->get('elasticsearch_connector_blocks.event_subscriber'),
      $container->get('plugin.manager.elastic_url_processor.processor')
    );
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

    if ($buckets = $this->getBuckets()) {

      $facets = array();

      // @TODO an admin tab to choose the UrlProcessor plugin globally.
      $urlProcessor = $this->urlProcessorManager->createInstance('defaultProcessor');

      foreach ($buckets as $facet) {
        // A facet item has status (active or not), count, link and title attributes.
        $facets[] = array(
          'active' => TRUE,
          'title' => $facet['key'],
          'count' => $facet['doc_count'],
          'link' => $urlProcessor->filtersToUrl($facet, $this->definition['facet_name']),
        );
      }

      // @TODO a buildForm to choose the facetView plugin and what to show when no results.
      $facetViewPlugin = $this->facetViewkManager->createInstance('basicLinks');
      $build[] = $facetViewPlugin->setLinks($facets);
    }
    return $build;
  }

  public function getBuckets() {
    $buckets = array();
    $aggregations = $this->eventSubscriber->getResponseAggs();
    if (isset($aggregations[$this->definition['facet_name']])) {
      $agg = $aggregations[$this->definition['facet_name']];
      if (count($agg['buckets'])) {
        $buckets = $agg['buckets'];
      }
    }
    return $buckets;
  }

  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    return array();
  }
}