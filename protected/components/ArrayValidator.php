<?php
/**
 * ArrayValidator class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013-2017 Loris Tissino
 * @since 1.0
 */
/**
 * A customized array validator.
 *
 * @package application.components
 * 
 */
class ArrayValidator extends CValidator
{
  public $values = array();
  public $message = 'You must choose a valid item';

  protected function validateAttribute($object,$attribute)
  {
    $value=$object->$attribute;
    if(!in_array($value, array_keys($this->values)))
    {
      $this->addError($object,$attribute,Yii::t('delt', $this->message));
    }
  }
}

