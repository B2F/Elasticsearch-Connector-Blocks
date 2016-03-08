<?php

/**
 * @file
 * Contains \Drupal\elasticsearch_connector_blocks\EventSubscriber\ElasticsearchBlocksEventSubscriber.
 */

namespace Drupal\elasticsearch_connector_blocks\EventSubscriber;

use Drupal\block\BlockRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Drupal\elasticsearch_connector\ElasticSearch\ClientManager;
use Drupal\elasticsearch_connector\Entity\Cluster;

class ElasticsearchBlocksEventSubscriber implements EventSubscriberInterface {

  public $clusterId = 'elastic';
  protected $client;
  protected $blockRepository;
  protected $results;
  protected $alive;
  protected $connection;
  protected $buckets;

  public function __construct(ClientManager $ClientManager, BlockRepository $BlockRepository) {
    $cluster = Cluster::load($this->clusterId);
    $this->client = $ClientManager->getClientForCluster($cluster);
    $this->connection = $this->client->transport->getConnection();
    $this->blockRepository = $BlockRepository;
  }

  public function isAlive() {
    return $this->connection->ping();
  }

  public function handleSearchRequest(GetResponseEvent $event) {

    if ($this->isAlive()) {

      //    $params = array(
      //        'index' => 'elasticsearch_index_demod8_elastic_idnex',
      //        'type' => 'elastic_idnex',
      //        'body' => array(
      //            'query' => array(
      //                'match_phrase_prefix' => array(
      //                    'title' => 'Article',
      //                ),
      //            ),
      //        ),
      //    );

      $params = array(
          'index' => 'elasticsearch_index_demod8_elastic_idnex',
          'type' => 'elastic_idnex',
          'body' => array(
              'query' => array(
                  'match_all' => array(),
              ),
              'aggs' => array(
                  'f_title' => array(
                      'terms' => array(
                          'field' => 'title',
                          'size' => 0
                      ),
                  ),
              ),
          ),
      );

      $resp = $this->client->search($params);

    //    $params = array(
    //        'index' => 'elasticsearch_index_demod8_elastic_idnex',
    //        'type' => 'elastic_idnex',
    //    );
    //    $resp = $this->client->indices()->getMapping($params);
    //
    //    dpm($this->blockRepository->getVisibleBlocksPerRegion());
    //    dpm($resp);
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = array('handleSearchRequest', 0);
    return $events;
  }
}