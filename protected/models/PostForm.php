<?php

class PostForm extends CFormModel
{

  public $firm_id;
  public $date;
  public $description;
  public $debitcredits;
  public $currency;
  
  public $post = null; // the original Post instance
  
  private $is_new = true;
  
  public function rules()
	{
		return array(
			array('date, description', 'required'),
      array('debitcredits', 'checkDebitcredits'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'date' => Yii::t('delt', 'Date'),
			'description' => Yii::t('delt', 'Description'),
		);
	}

  
  public function acquireItems($values)
  {
    $this->debitcredits=array();
    
    foreach($values as $key => $value)
    {
      $this->debitcredits[$key] = new DebitcreditForm();
      $this->debitcredits[$key]->name = $value['name'];
      $this->debitcredits[$key]->debit = $value['debit'];
      $this->debitcredits[$key]->credit = $value['credit'];
    }
  }
  
  public function loadFromPost(Post $post)
  {
    $this->post = $post;
    $this->description = $post->description;
    $this->date = Yii::app()->dateFormatter->formatDateTime($post->date, 'short', null);
    foreach($post->debitcredits as $debitcredit)
    {
      $this->debitcredits[$debitcredit->id] = new DebitcreditForm();
      $this->debitcredits[$debitcredit->id]->name = $debitcredit->account;
      $this->debitcredits[$debitcredit->id]->debit = $debitcredit->amount > 0 ? DELT::currency_value($debitcredit->amount, $this->currency) : '';
      $this->debitcredits[$debitcredit->id]->credit = $debitcredit->amount < 0 ? DELT::currency_value(-$debitcredit->amount, $this->currency) : '';
    }
  }
  
  public function save()
  {
    $this->is_new = !isset($this->post);
    $post = $this->is_new ? new Post() : $this->post;
    
    $transaction = $post->getDbConnection()->beginTransaction();
    
    try
    {
      $post->date = $this->date;
      $post->firm_id = $this->firm_id;
      $post->description = $this->description;
      if($this->is_new)
      {
        $post->rank = $post->getCurrentMaxRank() + 1;
      }
      
      $post->save(true);
      
      $post->deleteDebitcredits();
      
      $rank = 1;
      
      foreach($this->debitcredits as $debitcreditform)
      {
        if($debitcreditform->account_id)
        {
          $Debitcredit = new Debitcredit();
          $Debitcredit->post_id = $post->id;
          $Debitcredit->account_id = $debitcreditform->account_id;
          $Debitcredit->amount = $debitcreditform->debit - $debitcreditform->credit;
          $Debitcredit->rank = $rank++;
          
          $Debitcredit->save(true);
        }
      }
      
      
      $transaction->commit();
      return true;
      
    }
    catch(Exception $e)
    {
      Yii::app()->user->setFlash('delt_failure',$e->getMessage()); 
      $transaction->rollback();
      return false;
    }
    
  }
  
  public function checkDebitcredits() // $attribute,$params)
  {
    $grandtotal_debit = 0;
    $grandtotal_credit = 0;
    $errors=false;
    
    $used_accounts = array();

    foreach($this->debitcredits as $row => $debitcredit)
    {
      
      $row_message = Yii::t('delt', 'Row {row}: ', array('{row}'=> $row+1));
      
      if($debitcredit['name']=='')
      {
        // when the account name is not given, the whole line is completely ignored...
        continue;
      }
      
      $info = explode(' - ', $debitcredit['name']);
      $code = trim($info[0]);
      
      $account = Account::model()->findByAttributes(array('code'=>$code, 'firm_id'=>$this->firm_id, 'is_selectable'=>true));
      if(!$account)
      {
        $this->addError('debitcredits', $row_message . Yii::t('delt', 'the account with code "{code}" is not available.', array('{code}'=>$code)));
        $this->debitcredits[$row]->name_errors=true;
        continue;
      }
      else
      {
        if(!in_array($account->id, $used_accounts))
        {
          $this->debitcredits[$row]->account_id = $account->id;
          $used_accounts[] = $account->id;
        }
        else
        {
          $this->addError('debitcredits', $row_message . Yii::t('delt', 'the account with code "{code}" makes the row a duplicate.', array('{code}'=>$code)));
        }
      }
      
      $errors=false;
      foreach(array('debit', 'credit') as $type)
      {

        $debitcredit[$type]=DELT::currency2decimal($debitcredit[$type], $this->currency);
        $value=$debitcredit[$type];

        $error=$type . '_errors';
        if($value!='' and !is_numeric($value))
        {
          $this->addError('debitcredits', $row_message . Yii::t('delt', 'the value "{value}" is not numeric.', array('{value}'=>$value)));
          $this->debitcredits[$row]->$error=true;
          $errors=true;
        }
        if($value<0)
        {
          $this->addError('debitcredits', $row_message . Yii::t('delt', 'the value "{value}" cannot be negative.', array('{value}'=>$value)));
          $this->debitcredits[$row]->$error=true;
          $errors=true;
        }
      }
      
      if($errors)
      {
        continue;
      }
      
      $debit = $this->debitcredits[$row]['debit'];
      $credit = $this->debitcredits[$row]['credit'];
      
      if($debit and $credit)
      {
          $this->addError('debitcredits', $row_message . Yii::t('delt', 'you cannot have both a debit and a credit.'));
          $this->debitcredits[$row]->debit_errors=true;
          $this->debitcredits[$row]->credit_errors=true;
          $errors=true;
      }
      
      if(!$debit and !$credit)
      {
          $this->addError('debitcredits', $row_message . Yii::t('delt', 'you must have a debit or a credit.'));
          $this->debitcredits[$row]->debit_errors=true;
          $this->debitcredits[$row]->credit_errors=true;
          $errors=true;
      }
      
      if(!$errors)
      {
        $grandtotal_debit += $debit;
        $grandtotal_credit += $credit;
      }
    }

    if($errors)
    {
      return;
    }
    
    if($grandtotal_debit==0 and $grandtotal_credit==0)
    {
       $this->addError('debitcredits', Yii::t('delt', 'No amounts specified.'));
    }
    
    if($grandtotal_debit != $grandtotal_credit)
    {
       $this->addError('debitcredits',
        Yii::t('delt', 'The total amount of debits ({debits}) does not match the total amounts of credits ({credits}).', array('{debits}'=>DELT::currency_value($grandtotal_debit, $this->currency), '{credits}'=>DELT::currency_value($grandtotal_credit, $this->currency)))
        . ' ' .
        Yii::t('delt', 'The imbalance is: {amount}.', array('{amount}'=>DELT::currency_value($grandtotal_debit - $grandtotal_credit, $this->currency, true)))
        );
    }
    
    if($this->hasErrors())
    {
      foreach($this->debitcredits as $row => $debitcredit)
      {
        foreach(array('debit', 'credit') as $type)
        {
          $this->debitcredits[$row][$type]=$debitcredit[$type] ? DELT::currency_value($debitcredit[$type], $this->currency) : '';
        }
      }
    }
    
  }

}

