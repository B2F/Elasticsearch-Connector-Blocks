<?php

/**
 * @file
 * Contains \Drupal\elasticsearch_connector_blocks\Form\ElasticsearchFacetBlockConfigForm.
 */

namespace Drupal\elasticsearch_connector_blocks\Form;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\search_api\Entity;

/**
 * Class ElasticsearchFacetBlockConfigForm.
 *
 * @package Drupal\elasticsearch_connector_blocks\Form
 */
class ElasticsearchFacetBlockConfigForm extends EntityForm {

  private function getAvailableSearchBlock() {
    $searchBlocks = array();
    $searchBlocksEntities = $this->entityTypeManager->getStorage('elasticsearch_search_block_conf')->loadMultiple();
    foreach ($searchBlocksEntities as $searchBlock) {
      $searchBlocks[$searchBlock->getIndex()][$searchBlock->id()] = $searchBlock->label();
    }
    return $searchBlocks;
  }

  private function getAllAvailableFacets($serverIndex) {

    $indexFields = array();
    $indexes = $this->entityTypeManager->getStorage('search_api_index')->loadMultiple();

    foreach ($indexes as $index) {
      foreach ($index->getDatasources() as $datasource_id => $datasource) {
        $fields = $index->getFieldsByDatasource($datasource_id);
        foreach ($fields as $field) {
          $fieldName = $field->getFieldIdentifier();
          $index = $field->getIndex()->id();
          $facetId = $index . ':' . $fieldName;
          $indexFields[$index][$facetId] = $fieldName;
        }
      }
    }

    return $indexFields;
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {

    $form = parent::form($form, $form_state);

    $elasticsearch_facet_block_config = $this->entity;
    $form['label'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $elasticsearch_facet_block_config->label(),
      '#description' => $this->t("Label for the Elasticsearch facet block config."),
      '#required' => TRUE,
    );

    $form['id'] = array(
      '#type' => 'machine_name',
      '#default_value' => $elasticsearch_facet_block_config->id(),
      '#machine_name' => array(
        'exists' => '\Drupal\elasticsearch_connector_blocks\Entity\ElasticsearchFacetBlockConfig::load',
      ),
      '#disabled' => !$elasticsearch_facet_block_config->isNew(),
    );

    // @todo: adds a warning message on top of the page when no search blocks found.
    $form['searchBlock'] = array(
      '#title' => 'Search block',
      '#required' => TRUE,
      '#description' => 'Elasticsearch Connector search block executing the search request and display results. Search blocks above are grouped by server index.',
      '#type' => 'select',
      '#options' => array(),
      '#default_value' => $elasticsearch_facet_block_config->get('searchBlock'),
    );
    $form['searchBlock']['#options'] = $this->getAvailableSearchBlock();

    $serverIndex = NULL;
    $indexFields = $this->getAllAvailableFacets($serverIndex);

    // @todo: only display optgroups indexes matching the selected search block.
    $form['indexField'] = array(
      '#title' => 'Index field',
      '#required' => TRUE,
      '#type' => 'select',
      '#options' => $indexFields,
      '#default_value' => $elasticsearch_facet_block_config->get('indexField'),
    );

    // @todo: adds the facetView display plugin select options.

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $elasticsearch_facet_block_config = $this->entity;
    $status = $elasticsearch_facet_block_config->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Elasticsearch facet block config.', [
          '%label' => $elasticsearch_facet_block_config->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Elasticsearch facet block config.', [
          '%label' => $elasticsearch_facet_block_config->label(),
        ]));
    }
    $form_state->setRedirectUrl($elasticsearch_facet_block_config->urlInfo('collection'));
  }

}
