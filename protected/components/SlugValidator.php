<?php
/**
 * SlugValidator class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2017 Loris Tissino
 * @since 1.9
 */
/**
 * A customized slug validator.
 *
 * @package application.components
 * 
 */
class SlugValidator extends CValidator
{
  public $model;
  public $message = 'You must choose a valid slug';

  protected function validateAttribute($object,$attribute)
  {
    $value=$object->$attribute;

    if(strlen($value)>32)
    {
      $this->addError($object, $attribute, Yii::t('delt', 'The maximum length of a slug is of 32 characters.'));
      return;
    }
    
    if(preg_match('/[^0-9a-z\-]/', $value))
    {
      $this->addError($object, $attribute, Yii::t('delt', 'Only lowercase letters, digits and minus sign are allowed.'));
      return;
    }
    
    $f=$this->model->findByAttributes(array('slug'=>$value));
    if($f and $f->id != $object->id)
    {
      $this->addError($object, $attribute, Yii::t('delt', 'This slug is already in use.'));
    }
  }
}



    
    
    
