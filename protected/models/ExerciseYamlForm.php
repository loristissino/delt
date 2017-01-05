<?php

/**
 * ExerciseYamlForm class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2017 Loris Tissino
 * @since 1.9.2
 */
/** ExerciseYamlForm class.
 * ExerciseYamlForm is the data structure for importing data from YAML files to Exercises.
 * It is used by the 'import' action of 'ExerciseController'.
 * 
 * @package application.forms
 * 
 * 
 */
class ExerciseYamlForm extends CFormModel
{
  /** 
   * @var string $content represents the content of the text area 
   */
  public $content;

  /**
   * Declares the validation rules.
   */
  public function rules()
  {
    return array(
      // content is required
      array('content', 'required'),
    );
  }

  /**
   * Declares customized attribute labels.
   * If not declared here, an attribute would have a label that is
   * the same as its name with the first letter in upper case.
   */
  public function attributeLabels()
  {
    return array(
      'content'=>Yii::t('delt', 'Content'),
    );
  }
  
}
