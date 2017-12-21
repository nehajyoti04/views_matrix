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



//   $form['columns'] = [
//     '#type' => 'number',
//     '#title' => $this->t('Number of columns'),
//     '#default_value' => $this->options['columns'],
//     '#required' => TRUE,
//     '#min' => 1,
//   ];
//    $form['automatic_width'] = [
//      '#type' => 'checkbox',
//      '#title' => $this->t('Automatic width'),
//      '#description' => $this->t('The width of each column will be calculated automatically based on the number of columns provided. If additional classes are entered or a theme injects classes based on a grid system, disabling this option may prove beneficial.'),
//      '#default_value' => $this->options['automatic_width'],
//    ];
//    $form['alignment'] = [
//      '#type' => 'radios',
//      '#title' => $this->t('Alignment'),
//      '#options' => ['horizontal' => $this->t('Horizontal'), 'vertical' => $this->t('Vertical')],
//      '#default_value' => $this->options['alignment'],
//      '#description' => $this->t('Horizontal alignment will place items starting in the upper left and moving right. Vertical alignment will place items starting in the upper left and moving down.'),
//    ];
//    $form['col_class_default'] = [
//      '#title' => $this->t('Default column classes'),
//      '#description' => $this->t('Add the default views column classes like views-col, col-1 and clearfix to the output. You can use this to quickly reduce the amount of markup the view provides by default, at the cost of making it more difficult to apply CSS.'),
//      '#type' => 'checkbox',
//      '#default_value' => $this->options['col_class_default'],
//    ];
//    $form['col_class_custom'] = [
//      '#title' => $this->t('Custom column class'),
//      '#description' => $this->t('Additional classes to provide on each column. Separated by a space.'),
//      '#type' => 'textfield',
//      '#default_value' => $this->options['col_class_custom'],
//    ];
//    if ($this->usesFields()) {
//      $form['col_class_custom']['#description'] .= ' ' . $this->t('You may use field tokens from as per the "Replacement patterns" used in "Rewrite the output of this field" for all fields.');
//    }
//    $form['row_class_default'] = [
//      '#title' => $this->t('Default row classes'),
//      '#description' => $this->t('Adds the default views row classes like views-row, row-1 and clearfix to the output. You can use this to quickly reduce the amount of markup the view provides by default, at the cost of making it more difficult to apply CSS.'),
//      '#type' => 'checkbox',
//      '#default_value' => $this->options['row_class_default'],
//    ];
//    $form['row_class_custom'] = [
//      '#title' => $this->t('Custom row class'),
//      '#description' => $this->t('Additional classes to provide on each row. Separated by a space.'),
//      '#type' => 'textfield',
//      '#default_value' => $this->options['row_class_custom'],
//    ];
//    if ($this->usesFields()) {
//      $form['row_class_custom']['#description'] .= ' ' . $this->t('You may use field tokens from as per the "Replacement patterns" used in "Rewrite the output of this field" for all fields.');
//    }
  }

  /**
   * Return the token-replaced row or column classes for the specified result.
   *
   * @param int $result_index
   *   The delta of the result item to get custom classes for.
   * @param string $type
   *   The type of custom grid class to return, either "row" or "col".
   *
   * @return string
   *   A space-delimited string of classes.
   */
  // public function getCustomClass($result_index, $type) {
  //   $class = $this->options[$type . '_class_custom'];
  //   if ($this->usesFields() && $this->view->field) {
  //     $class = strip_tags($this->tokenizeValue($class, $result_index));
  //   }

  //   $classes = explode(' ', $class);
  //   foreach ($classes as &$class) {
  //     $class = Html::cleanCssIdentifier($class);
  //   }
  //   return implode(' ', $classes);
  // }

}
