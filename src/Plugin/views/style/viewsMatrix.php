<?php

namespace Drupal\views_matrix\Plugin\views\style;

use Drupal\Component\Utility\Html;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Annotation\ViewsStyle;
use Drupal\views\Plugin\views\style\StylePluginBase;

/**
 * Style plugin to render each item in a grid cell.
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "views_matrix",
 *   title = @Translation("Views Matrix"),
 *   help = @Translation("Displays a table sorted by row and column.."),
 *   theme = "views_view_matrix",
 *   display_types = {"normal"}
 * )
 */
class viewsMatrix extends StylePluginBase {

  /**
   * {@inheritdoc}
   */
  protected $usesRowPlugin = TRUE;

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['path'] = array('default' => 'views_matrix');
//    $options['columns'] = ['default' => '4'];
//    $options['automatic_width'] = ['default' => TRUE];
//    $options['alignment'] = ['default' => 'horizontal'];
//    $options['col_class_custom'] = ['default' => ''];
//    $options['col_class_default'] = ['default' => TRUE];
//    $options['row_class_custom'] = ['default' => ''];
//    $options['row_class_default'] = ['default' => TRUE];
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);

    $form['sticky'] = array(
      '#type' => 'checkbox',
      '#title' => t('Enable Drupal style "sticky" table headers (Javascript)'),
      '#default_value' => !empty($this->options['sticky']),
      '#description' => t('The sticky header function only applies to column headers.'),
    );

    $field_options = $this->displayHandler->getFieldLabels(TRUE);
    $form['xconfig'] = array(
      '#type' => 'details',
      '#title' => t('Column header'),
      '#open' => TRUE,
      '#tree' => TRUE,
    );

    $form['xconfig']['field'] = array(
      '#type' => 'select',
      '#title' => t('Field'),
      '#description' => t('Select the field to display in the column header.'),
      '#options' => $field_options,
      '#default_value' => $this->options['xconfig']['field'],
    );

    $form['xconfig']['sort_field'] = array(
      '#type' => 'select',
      '#title' => t('Sort by'),
      '#description' => t('Select the field to sort by. Picking a different field here will only be meaningful if the values correspond to the field used for the header such as sorting by a Taxonomy term or Entity reference relationship field.'),
//      '#options' => $sort_field_options,
      '#options' => $field_options,
      '#default_value' => $this->options['xconfig']['sort_field'],
    );

    $form['xconfig']['sort'] = array(
      '#type' => 'select',
      '#title' => t('Sort direction'),
      '#title_display' => 'invisible',
      '#description' => t('Select the sort direction.'),
      '#options' => array('asc' => t('Ascending'), 'dsc' => t('Descending')),
      '#default_value' => $this->options['xconfig']['sort'],
    );

    $form['xconfig']['first_column'] = array(
      '#type' => 'textfield',
      '#title' => t('Header for first column'),
      '#description' => t('Optionally, specify a header for the first column (containing the column headers).'),
      '#default_value' => $this->options['xconfig']['first_column'],
    );

    $form['xconfig']['class'] = array(
      '#type' => 'textfield',
      '#title' => t('CSS class'),
      '#description' => t('You may use token substitutions from the rewriting section in this class.'),
      '#default_value' => $this->options['xconfig']['class']
    );

    $form['yconfig'] = array(
      '#type' => 'details',
      '#title' => t('Row header'),
      '#open' => TRUE,
      '#tree' => TRUE,
    );

    $form['yconfig']['field'] = array(
      '#type' => 'select',
      '#title' => t('Field'),
      '#description' => t('Select the field to display in the row header.'),
      '#options' => $field_options,
      '#default_value' => $this->options['yconfig']['field'],
    );

    $form['yconfig']['row_numbers'] = array(
      '#type' => 'checkbox',
      '#title' => t('Add row numbers'),
      '#description' => t('Add row numbers to the output for the row header field.'),
      '#default_value' => $this->options['yconfig']['row_numbers'],
    );

//    $form['yconfig']['row_number_format'] = array(
//      '#type' => 'textfield',
//      '#title' => t('Row number format'),
//      '#description' => t('Set the complete text to be displayed for the row header. Use "[counter]" as a token for the row number, and "[label]" as the output generated from the field.'),
//      '#default_value' => $this->options['yconfig']['row_number_format'],
//      '#required' => TRUE,
//      '#dependency' => array(
//        'edit-style-options-yconfig-row-numbers' => array(1),
//      ),
//    );

    $form['yconfig']['sort_field'] = array(
      '#type' => 'select',
      '#title' => t('Sort by'),
      '#description' => t('Select the field to sort by. Picking a different field here will only be meaningful if the values correspond to the field used for the header such as sorting by a Taxonomy term or Entity reference relationship field.'),
//      '#options' => $sort_field_options,
      '#options' => $field_options,
      '#default_value' => $this->options['yconfig']['sort_field'],
    );

    $form['yconfig']['sort'] = array(
      '#type' => 'select',
      '#title' => t('Sort direction'),
      '#title_display' => 'invisible',
      '#description' => t('Select the sort direction.'),
      '#options' => array('asc' => t('Ascending'), 'dsc' => t('Descending')),
      '#default_value' => $this->options['yconfig']['sort'],
    );

    $form['yconfig']['class'] = array(
      '#type' => 'textfield',
      '#title' => t('CSS class'),
      '#description' => t('You may use token substitutions from the rewriting section in this class.'),
      '#default_value' => $this->options['yconfig']['class']
    );

  }

}
