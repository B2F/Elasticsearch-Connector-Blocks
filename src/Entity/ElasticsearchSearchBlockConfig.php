<?php

/**
 * @file
 * Contains \Drupal\elasticsearch_connector_blocks\Entity\ElasticsearchSearchBlockConfig.
 */

namespace Drupal\elasticsearch_connector_blocks\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\elasticsearch_connector_blocks\ElasticsearchSearchBlockConfigInterface;

/**
 * Defines the Elasticsearch search block config entity.
 *
 * @ConfigEntityType(
 *   id = "elasticsearch_search_block_conf",
 *   label = @Translation("Elasticsearch search block config"),
 *   handlers = {
 *     "list_builder" = "Drupal\elasticsearch_connector_blocks\ElasticsearchSearchBlockConfigListBuilder",
 *     "form" = {
 *       "add" = "Drupal\elasticsearch_connector_blocks\Form\ElasticsearchSearchBlockConfigForm",
 *       "edit" = "Drupal\elasticsearch_connector_blocks\Form\ElasticsearchSearchBlockConfigForm",
 *       "delete" = "Drupal\elasticsearch_connector_blocks\Form\ElasticsearchSearchBlockConfigDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\elasticsearch_connector_blocks\ElasticsearchSearchBlockConfigHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "elasticsearch_search_block_conf",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/config/search/elasticsearch-connector/elasticsearch_search_block_conf/{elasticsearch_search_block_conf}",
 *     "add-form" = "/admin/config/search/elasticsearch-connector/elasticsearch_search_block_conf/add",
 *     "edit-form" = "/admin/config/search/elasticsearch-connector/elasticsearch_search_block_conf/{elasticsearch_search_block_conf}/edit",
 *     "delete-form" = "/admin/config/search/elasticsearch-connector/elasticsearch_search_block_conf/{elasticsearch_search_block_conf}/delete",
 *     "collection" = "/admin/config/search/elasticsearch-connector/elasticsearch_search_block_conf"
 *   }
 * )
 */
class ElasticsearchSearchBlockConfig extends ConfigEntityBase implements ElasticsearchSearchBlockConfigInterface {
  /**
   * The Elasticsearch search block config ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Elasticsearch search block config label.
   *
   * @var string
   */
  protected $label;

  /**
   * The Elasticsearch search block corresponding server index.
   *
   * @var string
   */
  protected $serverIndex;

  public function getServer() {
    return preg_replace('/^(.*):.*$/', '$1', $this->serverIndex);
  }

  public function getIndex() {
    return preg_replace('/^.*\:(.*)$/', '$1', $this->serverIndex);
  }
}
