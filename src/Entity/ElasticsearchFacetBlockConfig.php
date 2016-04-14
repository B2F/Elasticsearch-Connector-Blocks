<?php

/**
 * @file
 * Contains \Drupal\elasticsearch_connector_blocks\Entity\ElasticsearchFacetBlockConfig.
 */

namespace Drupal\elasticsearch_connector_blocks\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\elasticsearch_connector_blocks\ElasticsearchFacetBlockConfigInterface;

/**
 * Defines the Elasticsearch facet block config entity.
 *
 * @ConfigEntityType(
 *   id = "elasticsearch_facet_block_config",
 *   label = @Translation("Elasticsearch facet block config"),
 *   handlers = {
 *     "list_builder" = "Drupal\elasticsearch_connector_blocks\ElasticsearchFacetBlockConfigListBuilder",
 *     "form" = {
 *       "add" = "Drupal\elasticsearch_connector_blocks\Form\ElasticsearchFacetBlockConfigForm",
 *       "edit" = "Drupal\elasticsearch_connector_blocks\Form\ElasticsearchFacetBlockConfigForm",
 *       "delete" = "Drupal\elasticsearch_connector_blocks\Form\ElasticsearchFacetBlockConfigDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\elasticsearch_connector_blocks\ElasticsearchFacetBlockConfigHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "elasticsearch_facet_block_config",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/config/search/elasticsearch-connector/elasticsearch_facet_block_config/{elasticsearch_facet_block_config}",
 *     "add-form" = "/admin/config/search/elasticsearch-connector/elasticsearch_facet_block_config/add",
 *     "edit-form" = "/admin/config/search/elasticsearch-connector/elasticsearch_facet_block_config/{elasticsearch_facet_block_config}/edit",
 *     "delete-form" = "/admin/config/search/elasticsearch-connector/elasticsearch_facet_block_config/{elasticsearch_facet_block_config}/delete",
 *     "collection" = "/admin/config/search/elasticsearch-connector/elasticsearch_facet_block_config"
 *   }
 * )
 */
class ElasticsearchFacetBlockConfig extends ConfigEntityBase implements ElasticsearchFacetBlockConfigInterface {

  /**
   * The Elasticsearch facet block config ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Elasticsearch facet block config label.
   *
   * @var string
   */
  protected $label;

  /**
   * The Elasticsearch facet block config corresponding facet name.
   *
   * @var string
   */
  protected $indexField;

  public function getFacetField() {
    return preg_replace('/^.*\:(.*)$/', '$1', $this->indexField);
  }

  public function getIndex() {
    return preg_replace('/^(.*):.*$/', '$1', $this->indexField);
  }
}
