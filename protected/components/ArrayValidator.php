<?php
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

