<?php

/**
 * @file
 * Contains \Drupal\elasticsearch_connector_blocks\Form\ElasticsearchFacetBlockConfigForm.
 */

namespace Drupal\elasticsearch_connector_blocks\Form;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ElasticsearchFacetBlockConfigForm.
 *
 * @package Drupal\elasticsearch_connector_blocks\Form
 */
class ElasticsearchFacetBlockConfigForm extends EntityForm {
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

    /* You will need additional form elements for your custom properties. */

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
