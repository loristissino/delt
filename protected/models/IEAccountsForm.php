<?php

/**
 * IEAccountsForm class.
 * IEAccountsForm is the data structure for keeping
 * import/export accounts form data. It is used by the 'import' and 'export' actions of 'AccountController'.
 */
class IEAccountsForm extends CFormModel
{
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
  
  public function loadAccounts(Firm $firm)
  {
    $this->content = '';
    foreach($firm->accounts as $account)
    {
      $this->content .= implode("\t", array(
        $account->currentname,
        $account->code,
        $account->position,
        $account->outstanding_balance,
        )) . "\n";
    }
  }
}
