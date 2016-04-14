<?php

/**
 * @file
 * Contains \Drupal\elasticsearch_connector_blocks\Form\ElasticsearchSearchBlockConfigForm.
 */

namespace Drupal\elasticsearch_connector_blocks\Form;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ElasticsearchSearchBlockConfigForm.
 *
 * @package Drupal\elasticsearch_connector_blocks\Form
 */
class ElasticsearchSearchBlockConfigForm extends EntityForm {
  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $elasticsearch_search_block_conf = $this->entity;
    $form['label'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $elasticsearch_search_block_conf->label(),
      '#description' => $this->t("Label for the Elasticsearch search block config."),
      '#required' => TRUE,
    );

    $form['id'] = array(
      '#type' => 'machine_name',
      '#default_value' => $elasticsearch_search_block_conf->id(),
      '#machine_name' => array(
        'exists' => '\Drupal\elasticsearch_connector_blocks\Entity\ElasticsearchSearchBlockConfig::load',
      ),
      '#disabled' => !$elasticsearch_search_block_conf->isNew(),
    );

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $elasticsearch_search_block_conf = $this->entity;
    $status = $elasticsearch_search_block_conf->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Elasticsearch search block config.', [
          '%label' => $elasticsearch_search_block_conf->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Elasticsearch search block config.', [
          '%label' => $elasticsearch_search_block_conf->label(),
        ]));
    }
    $form_state->setRedirectUrl($elasticsearch_search_block_conf->urlInfo('collection'));
  }

}
