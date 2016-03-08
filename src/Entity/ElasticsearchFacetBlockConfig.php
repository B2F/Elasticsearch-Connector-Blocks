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
 *     "canonical" = "/admin/structure/elasticsearch_facet_block_config/{elasticsearch_facet_block_config}",
 *     "add-form" = "/admin/structure/elasticsearch_facet_block_config/add",
 *     "edit-form" = "/admin/structure/elasticsearch_facet_block_config/{elasticsearch_facet_block_config}/edit",
 *     "delete-form" = "/admin/structure/elasticsearch_facet_block_config/{elasticsearch_facet_block_config}/delete",
 *     "collection" = "/admin/structure/elasticsearch_facet_block_config"
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

}
