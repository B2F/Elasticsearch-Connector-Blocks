<?php

/**
 * @file
 * Contains \Drupal\elasticsearch_connector_blocks\EventSubscriber\ElasticsearchBlocksEventSubscriber.
 */

namespace Drupal\elasticsearch_connector_blocks\EventSubscriber;

use Drupal\block\BlockRepository;
use Drupal\Core\Config\Entity\ConfigEntityStorage;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Entity\Entity;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Drupal\elasticsearch_connector\ElasticSearch\ClientManager;
use Drupal\elasticsearch_connector\Entity\Cluster;
use Drupal\Core\Database\Database;
use Drupal\elasticsearch_connector_blocks\Plugin\ElasticUrlProcessorManager;


class ElasticsearchBlocksEventSubscriber implements EventSubscriberInterface {

  // @TODO replace the clusterId with a backoffice or dynamic setting or detection.
  public $clusterId = 'elastic';

  protected $client;
  protected $blockRepository;
  protected $entityManager;
  protected $queryFactory;
  protected $elasticUrlProcessorManager;
  protected $results;
  protected $alive;
  protected $connection;
  protected $response;

  private $mappings = FALSE;
  private $aggsParams = FALSE;

  public function __construct(ClientManager $ClientManager, BlockRepository $blockRepository, EntityTypeManager $entityTypeManager, QueryFactory $queryFactory, ElasticUrlProcessorManager $elasticUrlProcessorManager) {
    $cluster = Cluster::load($this->clusterId);
    $this->client = $ClientManager->getClientForCluster($cluster);
    $this->connection = $this->client->transport->getConnection();
    $this->blockRepository = $blockRepository;
    $this->entityManager = $entityTypeManager;
    $this->queryFactory = $queryFactory;
    $this->elasticUrlProcessorManager = $elasticUrlProcessorManager;
  }

  public function isAlive() {
    return $this->connection->ping();
  }

  public function getMappings() {
    if (!$this->mappings) {
      $this->mappings = $this->client->indices()->getMapping();
    }
    return $this->mappings;
  }

  public function getResponseHits() {
    return $this->response['hits'];
  }

  public function getResponseAggs() {
    return $this->response['aggregations'];
  }

  /**
   * @param $search_api_index
   *   A search_api_index as defined in the backoffice configuration.
   *
   * @return array|bool
   *   An array such as $params mapping data given to the $this->client->search method.
   */
  public function getIndexMapping($search_api_index)
  {
    $mappings = $this->getMappings();
    foreach ($mappings as $index => $value) {
      if (isset($value['mappings'][$search_api_index])) {
        return array(
          'index' => $index,
          'type' => $search_api_index
        );
      }
    }
    return FALSE;
  }

  public function handleSearchRequest(GetResponseEvent $event) {

    if ($this->isAlive()) {

      $aggsParams = $this->getAggsParams();
      foreach ($aggsParams['search_api_indexes'] as $seach_index) {
        // @TODO manage multiple indexes and/or types.
        $params = $this->getIndexMapping('elastic_idnex');
      }

      // @TODO an admin tab to choose the UrlProcessor plugin globally.
      $urlProcessor = $this->elasticUrlProcessorManager->createInstance('defaultProcessor');
      $filters = $urlProcessor->urlToFilters($aggsParams['facet_fields']);

      if ($params) {

        // @TODO provide a hook for altering the search query. Decide what to do when no search query in Url.
        $params['body'] = array();
//          'query' => array(
//            'match_all' => array(),
//          ),
//        );

        foreach ($aggsParams['facet_fields'] as $facet_field) {
          $params['body']['aggs'][$facet_field] = array(
            'terms' => array(
              'field' => $facet_field,
              'size' => 0,
              'min_doc_count' => 0
            )
          );
        }

        if ($filters) {
          $params['body']['query']['filtered']['filter']['terms'] = $filters;
        }

        $this->response = $this->client->search($params);
        dpm($this->response);
      }
    }
  }

  private function getAggsParams() {

    if (!$this->aggsParams) {

      $aggsParams = array(
        'search_api_indexes' => array(),
        'facet_fields' => array()
      );

      $availableBlocks = $this->blockRepository->getVisibleBlocksPerRegion();
      $facetBlockConfigStorage = $this->entityManager->getStorage('elasticsearch_facet_block_config');

      foreach ($availableBlocks as $key => $region) {
        foreach ($region as $blockId => $blockObj) {

          if (strpos($blockId, 'elasticsearchfacetblock') === 0) {
            $facetBlockConfigName = str_replace('elasticsearchfacetblock', '', $blockId);
            $query = $this->queryFactory->get('elasticsearch_facet_block_config');
            $query->condition('id', $facetBlockConfigName);
            $results = $query->execute();
            $config = $facetBlockConfigStorage->load($facetBlockConfigName);
            if (!in_array($aggsParams['search_api_indexes'], $config->getIndex())) {
              $aggsParams['search_api_indexes'][] = $config->getIndex();
            }
            if (!in_array($aggsParams['search_api_indexes'], $config->getFacetField())) {
              $aggsParams['facet_fields'][] = $config->getFacetField();
            }
          }
        }
      }
      $this->aggsParams = $aggsParams;
    }

    return $this->aggsParams;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = array('handleSearchRequest', 0);
    return $events;
  }
}