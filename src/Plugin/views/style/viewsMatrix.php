<?php

namespace Drupal\views_matrix\Plugin\views\style;

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
    $options['path'] = ['default' => 'views_matrix'];
    $options['columns'] = ['default' => '4'];
    $options['automatic_width'] = ['default' => TRUE];
    $options['alignment'] = ['default' => 'horizontal'];
    $options['col_class_custom'] = ['default' => ''];
    $options['col_class_default'] = ['default' => TRUE];
    $options['row_class_custom'] = ['default' => ''];
    $options['row_class_default'] = ['default' => TRUE];
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);

    $form['sticky'] = [
      '#type' => 'checkbox',
      '#title' => t('Enable Drupal style "sticky" table headers (Javascript)'),
      '#default_value' => !empty($this->options['sticky']),
      '#description' => t('The sticky header function only applies to column headers.'),
    ];

    $field_options = $this->displayHandler->getFieldLabels(TRUE);
    $form['xconfig'] = [
      '#type' => 'details',
      '#title' => t('Column header'),
      '#open' => TRUE,
      '#tree' => TRUE,
    ];

    $form['xconfig']['field'] = [
      '#type' => 'select',
      '#title' => t('Field'),
      '#description' => t('Select the field to display in the column header.'),
      '#options' => $field_options,
      '#default_value' => $this->options['xconfig']['field'],
    ];

    $form['xconfig']['sort_field'] = [
      '#type' => 'select',
      '#title' => t('Sort by'),
      '#description' => t('Select the field to sort by. Picking a different field here will only be meaningful if the values correspond to the field used for the header such as sorting by a Taxonomy term or Entity reference relationship field.'),
      '#options' => $field_options,
      '#default_value' => $this->options['xconfig']['sort_field'],
    ];

    $form['xconfig']['sort'] = [
      '#type' => 'select',
      '#title' => t('Sort direction'),
      '#title_display' => 'invisible',
      '#description' => t('Select the sort direction.'),
      '#options' => ['asc' => t('Ascending'), 'dsc' => t('Descending')],
      '#default_value' => $this->options['xconfig']['sort'],
    ];

    $form['xconfig']['first_column'] = [
      '#type' => 'textfield',
      '#title' => t('Header for first column'),
      '#description' => t('Optionally, specify a header for the first column (containing the column headers).'),
      '#default_value' => $this->options['xconfig']['first_column'],
    ];

    $form['xconfig']['class'] = [
      '#type' => 'textfield',
      '#title' => t('CSS class'),
      '#description' => t('You may use token substitutions from the rewriting section in this class.'),
      '#default_value' => $this->options['xconfig']['class'],
    ];

    $form['yconfig'] = [
      '#type' => 'details',
      '#title' => t('Row header'),
      '#open' => TRUE,
      '#tree' => TRUE,
    ];

    $form['yconfig']['field'] = [
      '#type' => 'select',
      '#title' => t('Field'),
      '#description' => t('Select the field to display in the row header.'),
      '#options' => $field_options,
      '#default_value' => $this->options['yconfig']['field'],
    ];

    $form['yconfig']['row_numbers'] = [
      '#type' => 'checkbox',
      '#title' => t('Add row numbers'),
      '#description' => t('Add row numbers to the output for the row header field.'),
      '#default_value' => $this->options['yconfig']['row_numbers'],
    ];

    $form['yconfig']['sort_field'] = [
      '#type' => 'select',
      '#title' => t('Sort by'),
      '#description' => t('Select the field to sort by. Picking a different field here will only be meaningful if the values correspond to the field used for the header such as sorting by a Taxonomy term or Entity reference relationship field.'),
      '#options' => $field_options,
      '#default_value' => $this->options['yconfig']['sort_field'],
    ];

    $form['yconfig']['sort'] = [
      '#type' => 'select',
      '#title' => t('Sort direction'),
      '#title_display' => 'invisible',
      '#description' => t('Select the sort direction.'),
      '#options' => ['asc' => t('Ascending'), 'dsc' => t('Descending')],
      '#default_value' => $this->options['yconfig']['sort'],
    ];

    $form['yconfig']['class'] = [
      '#type' => 'textfield',
      '#title' => t('CSS class'),
      '#description' => t('You may use token substitutions from the rewriting section in this class.'),
      '#default_value' => $this->options['yconfig']['class'],
    ];

    $form['sortable_headers'] = [
      '#type' => 'checkbox',
      '#title' => t('Make column headers sort links'),
      '#description' => t('Emulates the same behavior for the column headers as in an ordinary table. Only makes sense if there will be exactly one result and field value per cell.'),
      '#default_value' => !empty($this->options['sortable_headers']),
    ];

    $form['header_init_sort'] = [
      '#type' => 'select',
      '#title' => t('Inital sorting direction'),
      '#description' => t('Direction of the initial sort when a header is clicked for the first time.'),
      '#options' => ['asc' => t('Ascending'), 'desc' => t('Descending')],
      '#default_value' => isset($this->options['header_init_sort']) ? $this->options['header_init_sort'] : 'asc',
      '#dependency' => [
        'edit-style-options-sortable-headers' => [1],
      ],
    ];

    $form['hide_empty_columns'] = [
      '#type' => 'checkbox',
      '#title' => t('Hide empty columns'),
      '#description' => t('A column will be empty when "hide with empty" is checked for fields that are displayed and there are no results with data.'),
      '#default_value' => $this->options['hide_empty_columns'],
    ];

    $form['hide_empty_rows'] = [
      '#type' => 'checkbox',
      '#title' => t('Hide empty rows'),
      '#description' => t('A row will be empty when "hide with empty" is checked for fields that are displayed and there are no results with data.'),
      '#default_value' => $this->options['hide_empty_rows'],
    ];

    $form['one_result_per_cell'] = [
      '#type' => 'checkbox',
      '#title' => t('Limit to one result per table cell'),
      '#description' => t('If enabled, only the first Views result row encountered will be added to each table cell. Subsequent results with the same values for the column and row header fields will be ignored.'),
      '#default_value' => $this->options['one_result_per_cell'],
    ];

    $form['field_positions'] = [
      '#type' => 'details',
      '#title' => t('Field position'),
      '#description' => t('For each field, choose where to display it.'),
      '#open' => TRUE,
      '#tree' => TRUE,
    ];

    foreach ($field_options as $field_name => $field_label) {
      $form['field_positions'][$field_name] = [
        '#type' => 'select',
        '#title' => $field_label,
        '#default_value' => !empty($this->options['field_positions'][$field_name]) ? $this->options['field_positions'][$field_name] : '',
        '#options' => [
          '' => t('Inside the matrix'),
          'left' => t('On the left of the matrix'),
          'right' => t('On the right of the matrix'),
        ],
      ];
    }
  }

}
