<?php

class PostForm extends CFormModel
{

  public $firm_id;
  public $firm;
  public $date;
  public $description;
  public $raw_input;
  public $debitcredits;
  public $currency;
  public $is_closing = false;
  public $is_adjustment = false;
  public $adjustment_checkbox_needed = false;
  public $show_analysis = true;
  
  public $post = null; // the original Post instance
  
  private $is_new = true;
  
  public function rules()
	{
		return array(
			array('date, description, is_closing', 'required'),
      array('raw_input, is_adjustment', 'safe'),
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
			'description' => Yii::t('delt', 'Description / Explanation'),
      'raw_input' => Yii::t('delt', 'Raw input'),
      'options' => Yii::t('delt', 'Options')
      );
	}

  
  public function acquireItems($values)
  {
    $this->debitcredits=array();

    if($this->raw_input)
    {
      $count=0;
      foreach(explode("\n", $this->raw_input) as $line)
      {
        if(sizeof($fields=explode("\t", $line))>=3)
        {
          list($name, $debit, $credit) = $fields;
          if(is_numeric(DELT::currency2decimal($debit, $this->currency)) or is_numeric(DELT::currency2decimal($credit, $this->currency)))
          {
            $this->debitcredits[$count] = new DebitcreditForm();
            foreach(array('name', 'debit', 'credit') as $index=>$property)
            {
              $this->debitcredits[$count]->$property = $$property;
            }
            $count++;
          }
        }
      }
      for($i=$count+1; $i<=2; $i++)
      {
        $this->debitcredits[] = new DebitcreditForm();
      }
      return;
    }
    
    foreach($values as $key => $value)
    {
      $this->debitcredits[$key] = new DebitcreditForm();
      DELT::array2object($value, $this->debitcredits[$key], array('name', 'debit', 'credit'));
    }
  }
  
  public function loadFromPost(Post $post)
  {
    $this->post = $post;
    DELT::object2object($post, $this, array('description', 'is_closing', 'is_adjustment'));
    $this->date = $post->getDateForFormWidget();
    foreach($post->debitcredits as $debitcredit)
    {
      $this->debitcredits[$debitcredit->id] = new DebitcreditForm();
      $this->debitcredits[$debitcredit->id]->name = $debitcredit->account->__toString();
      $this->debitcredits[$debitcredit->id]->debit = $debitcredit->amount > 0 ? DELT::currency_value($debitcredit->amount, $this->currency) : '';
      $this->debitcredits[$debitcredit->id]->credit = $debitcredit->amount < 0 ? DELT::currency_value(-$debitcredit->amount, $this->currency) : '';
      $this->debitcredits[$debitcredit->id]->analysis = $debitcredit->account->getAnalysis($debitcredit->amount, $this->firm->currency);
    }
  }
  
  public function save()
  {
    $this->is_new = !isset($this->post);
    $post = $this->is_new ? new Post() : $this->post;
    
    $transaction = $post->getDbConnection()->beginTransaction();
    try
    {
      // we must convert the date from the user input
      // since we use jquery.ui.datepicker and its i18n features, we
      // use the browser locale to know the format used
      
      DELT::object2object($this, $post, array('firm_id', 'description', 'is_closing', 'is_adjustment'));
      $date=DateTime::createFromFormat(DELT::getConvertedJQueryUIDateFormat(), $this->date);
      $post->date= $date ? $date->format('Y-m-d'): $this->date;
      
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
          
          
          if(substr($debitcreditform->account_id, 0, 1)=='!')
          {
            $debitcreditform->account->cleanup($this->firm);
            $debitcreditform->account->basicSave(false);
            $debitcreditform->account_id = $debitcreditform->account->id;
          }
          
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
    $bang_accounts = array();

    $line_number = 0;
    foreach($this->debitcredits as $row => $debitcredit)
    {
      $last_line = $row;  //we use this later, for an amount proposal...
      $row_message = Yii::t('delt', 'Row {row}: ', array('{row}'=> ++$line_number));
      
      if($debitcredit['name']=='')
      {
        // when the account name is not given, the whole line is completely ignored...
        continue;
      }

      if(strpos($debitcredit['name'], '!')!==false)
      {
        // the bang sign means we have to create one (in the final transaction)
        $account = $this->firm->createBangAccount($debitcredit['name']);
      }
      else
      {
        $info = explode(' - ', $debitcredit['name']);
        $code = trim($info[0]);
        $account = Account::model()->findByAttributes(array('code'=>$code, 'firm_id'=>$this->firm_id, 'is_selectable'=>true));
      }
      
      if(!$account)
      {
        $this->addError('debitcredits', $row_message . Yii::t('delt', 'the account with code "{code}" is not available (you can add it on the fly to the Chart of Accounts by adding an exclamation mark to the name, like in "{code}!").', array('{code}'=>$code)));
        $this->debitcredits[$row]->name_errors=true;
        continue;
      }
      else
      {
        /*
        if(!in_array($account->id, $used_accounts))
        {
          */
          $this->debitcredits[$row]->account_id = $account->id;
          $this->debitcredits[$row]->account = $account;
          $used_accounts[] = $account->id;
        /*
         * }
        else
        {
          $this->addError('debitcredits', $row_message . Yii::t('delt', 'the account with code "{code}" makes the row a duplicate.', array('{code}'=>$code)));
        }
        */
      }
      
      $errors=false;
      $question=false;
      
      foreach(array('debit', 'credit') as $type)
      {
        $question = trim($debitcredit[$type])=='?' ? true : $question;

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
        if($question)
        {
          $v = $this->debitcredits[$row]->account->consolidatedBalance;
          if($v > 0)
          {
            $credit = $v;
            $this->debitcredits[$row]['credit']=$credit;
            $this->debitcredits[$row]->guessed = true;
            $this->addError('debitcredits', $row_message . Yii::t('delt', 'the amount of the credit has been computed as a balance for the account;'). ' ' . Yii::t('delt', 'it must be checked.'));
          }
          if($v < 0)
          {
            $debit = -$v;
            $this->debitcredits[$row]['debit']=$debit;
            $this->debitcredits[$row]->guessed = true;
            $this->addError('debitcredits', $row_message . Yii::t('delt', 'the amount of the debit has been computed as a balance for the account;'). ' ' . Yii::t('delt', 'it must be checked.'));
          }
        }
        elseif($line_number==(sizeof($this->debitcredits)))
        {
          // it is the last line, we can make a guess...
          if($grandtotal_debit > $grandtotal_credit)
          {
            $credit = $grandtotal_debit - $grandtotal_credit;
            $this->debitcredits[$row]['credit']=$credit;
            $this->debitcredits[$row]->guessed = true;
            
            $this->addError('debitcredits', $row_message . Yii::t('delt', 'the amount of the credit has been computed by difference;'). ' ' . Yii::t('delt', 'it must be checked.'));
          }
          if($grandtotal_debit < $grandtotal_credit)
          {
            $debit = $grandtotal_credit - $grandtotal_debit;
            $this->debitcredits[$row]['debit']=$debit;
            $this->debitcredits[$row]->guessed = true;

            $this->addError('debitcredits', $row_message . Yii::t('delt', 'the amount of the debit has been computed by difference;'). ' ' . Yii::t('delt', 'it must be checked.'));
          }
        }
        else
        {
          $this->addError('debitcredits', $row_message . Yii::t('delt', 'you must have a debit or a credit.'));
          $this->debitcredits[$row]->debit_errors=true;
          $this->debitcredits[$row]->credit_errors=true;
          $errors=true;
        }
      }
      
      if(!$this->is_closing && !$this->is_adjustment && $this->debitcredits[$row]->account && $this->debitcredits[$row]->account->position=='E')
      {
        if($this->debitcredits[$row]->account->outstanding_balance == 'D' && $credit>0)
        {
          $this->addError('debitcredits', $row_message . 
            Yii::t('delt', 'you cannot do a credit to this kind of account') . ' ' .
            Yii::t('delt', '(unless the post is marked as adjustment)') . '.'
            );
          $this->debitcredits[$row]->credit_errors=true;
          $errors=true;
          $this->adjustment_checkbox_needed = true;
        }
        if($this->debitcredits[$row]->account->outstanding_balance == 'C' && $debit>0)
        {
          $this->addError('debitcredits', $row_message . 
            Yii::t('delt', 'you cannot do a debit to this kind of account') . ' ' .
            Yii::t('delt', '(unless the post is marked as adjustment)') . '.'
            );
          $this->debitcredits[$row]->debit_errors=true;
          $errors=true;
          $this->adjustment_checkbox_needed = true;
        }
      }
      
      
      if(!$errors)
      {
        $grandtotal_debit += $debit;
        $grandtotal_credit += $credit;
        
        $this->debitcredits[$row]->analysis = $this->debitcredits[$row]->account->getAnalysis($debit - $credit, $this->firm->currency);
        
      }
      
    }

    if($errors)
    {
      $this->_fixAmounts();
      return;
    }
    
    if($grandtotal_debit==0 and $grandtotal_credit==0)
    {
       $this->addError('debitcredits', Yii::t('delt', 'No amounts specified.'));
    }
    
    if(abs($grandtotal_debit - $grandtotal_credit)>0.001) // floating point operations may yeld differences like -5.8207660913467E-11...
    {
       $this->addError('debitcredits',
        Yii::t('delt', 'The total amount of debits ({debits}) does not match the total amounts of credits ({credits}).', array('{debits}'=>DELT::currency_value($grandtotal_debit, $this->currency, false, true), '{credits}'=>DELT::currency_value($grandtotal_credit, $this->currency, false, true)))
        . ' ' .
        Yii::t('delt', 'The imbalance is: {amount}.', array('{amount}'=>DELT::currency_value($grandtotal_debit - $grandtotal_credit, $this->currency, true)))
        );
    }
    
    if($this->hasErrors())
    {
      $this->_fixAmounts();
    }
    
  }
  
  private function _fixAmounts()
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

