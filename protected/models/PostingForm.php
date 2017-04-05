<?php

/**
 * PostingForm class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013-2017 Loris Tissino
 * @since 1.0
 */
/**  PostingForm class.
 * PostingForm is the data structure for keeping
 * posting form data.
 * 
 * @package application.forms
 * 
 */

class PostingForm extends CFormModel
{

  public $name;
  public $debit=0;
  public $credit=0;
  public $name_errors=false;
  public $debit_errors=false;
  public $credit_errors=false;
  public $account_id;
  public $account;
  public $guessed=false;
  public $debitfromtemplate=false;
  public $creditfromtemplate=false;
  public $analysis = 'none';
  public $comment = '';
  public $subchoice = '';
  public $subchoice_list = '';
  public $subchoice_text = '';
  
  public function rules()
  {
    return array(
      array('name', 'required'),
    );
  }
  
  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels()
  {
    return array(
      'name' => Yii::t('delt', 'Account'),
      'debit' => Yii::t('delt', 'Debit'),
      'credit' => Yii::t('delt', 'Credit'),
      'comment' => Yii::t('delt', 'Comment'),
      'subchoice' => Yii::t('delt', 'Subchoice'),
    );
  }
  
  public function __toString()
  {
    return $this->name;
  }

}

