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

class ElasticsearchBlocksEventSubscriber implements EventSubscriberInterface {

  public $clusterId = 'elastic';
  protected $client;
  protected $blockRepository;
  protected $entityManager;
  protected $queryFactory;
  protected $results;
  protected $alive;
  protected $connection;
  protected $response;
  protected $mappings = FALSE;

  public function __construct(ClientManager $ClientManager, BlockRepository $blockRepository, EntityTypeManager $entityTypeManager, QueryFactory $queryFactory) {
    $cluster = Cluster::load($this->clusterId);
    $this->client = $ClientManager->getClientForCluster($cluster);
    $this->connection = $this->client->transport->getConnection();
    $this->blockRepository = $blockRepository;
    $this->entityManager = $entityTypeManager;
    $this->queryFactory = $queryFactory;
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

  public function getResponseFilters() {
    return $this->response['aggregations'];
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

      if ($params) {

        $aggs = array();
        foreach ($aggsParams['facet_fields'] as $facet_field) {
          $aggs[$facet_field] = array(
            'terms' => array(
              'field' => $facet_field,
              'size' => 0,
            )
          );
        }

        $params['body'] = array(
          'query' => array(
            'match_all' => array(),
          ),
          'aggs' => $aggs
        );

        $this->response = $this->client->search($params);
      }
    }
  }

  private function getAggsParams() {

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
          $config = $facetBlockConfigStorage->load('title');
          if (!in_array($aggsParams['search_api_indexes'], $config->getIndex())) {
            $aggsParams['search_api_indexes'][] = $config->getIndex();
          }
          if (!in_array($aggsParams['search_api_indexes'], $config->getFacetField())) {
            $aggsParams['facet_fields'][] = $config->getFacetField();
          }
        }
      }
    }

    return $aggsParams;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = array('handleSearchRequest', 0);
    return $events;
  }
}