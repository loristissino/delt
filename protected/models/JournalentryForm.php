<?php

/**
 * JournalentryForm class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013 Loris Tissino
 * @since 1.0
 */
/** JournalentryForm class.
 * JournalentryForm is the data structure for keeping
 * journal entry form data.
 * 
 * @package application.forms
 * 
 */
class JournalentryForm extends CFormModel
{

  public $firm_id;
  public $firm;
  public $date;
  public $description;
  public $raw_input;
  public $postings;
  public $currency;
  public $is_closing = false;
  public $is_adjustment = false;
  public $adjustment_checkbox_needed = false;
  public $show_analysis = true;
  
  public $total_debit = 0;
  public $total_credit = 0;
  
  public $journalentry = null; // the original Journalentry instance
  public $identifier = null;  // used by client-side software
  public $identifier_code = null;  // used to store a cookie
  
  private $is_new = true;
  
  
  public function rules()
  {
    return array(
      array('date, description, is_closing', 'required'),
      array('raw_input, is_adjustment', 'safe'),
      array('postings', 'checkPostings'),
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
    $this->postings=array();

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
            $this->postings[$count] = new PostingForm();
            foreach(array('name', 'debit', 'credit') as $index=>$property)
            {
              $this->postings[$count]->$property = $$property;
            }
            
            $count++;
          }
        }
      }
      for($i=$count+1; $i<=2; $i++)
      {
        $this->postings[] = new PostingForm();
      }
      return;
    }
    
    foreach($values as $key => $value)
    {
      $this->postings[$key] = new PostingForm();
      DELT::array2object($value, $this->postings[$key], array('name', 'debit', 'credit'));
      $this->total_debit += DELT::currency2decimal($this->postings[$key]->debit, $this->firm->currency);
      $this->total_credit += DELT::currency2decimal($this->postings[$key]->credit, $this->firm->currency);
    }
  }
  
  public function loadFromJournalentry(Journalentry $journalentry)
  {
    $this->journalentry = $journalentry;
    DELT::object2object($journalentry, $this, array('description', 'is_closing', 'is_adjustment'));
    $this->date = $journalentry->getDateForFormWidget();
    foreach($journalentry->postings as $posting)
    {
      $this->postings[$posting->id] = new PostingForm();
      $this->postings[$posting->id]->name = $posting->account->getCodeAndName($this->firm);
      if($posting->comment)
      {
        $this->postings[$posting->id]->name .= ' # ' . $posting->comment;
      }
      $this->postings[$posting->id]->debit = $posting->amount > 0 ? DELT::currency_value($posting->amount, $this->currency) : '';
      $this->postings[$posting->id]->credit = $posting->amount < 0 ? DELT::currency_value(-$posting->amount, $this->currency) : '';
      $this->postings[$posting->id]->analysis = $posting->account->getAnalysis($posting->amount, $this->firm->currency);
      
      $this->total_debit += $posting->amount > 0 ? $posting->amount : 0;
      $this->total_credit += $posting->amount < 0 ? -$posting->amount : 0;
      
    }
  }
  
  public function save()
  {
    $this->is_new = !isset($this->journalentry);
    $this->journalentry = $this->is_new ? new Journalentry() : $this->journalentry;
    
    $transaction = $this->journalentry->getDbConnection()->beginTransaction();
    try
    {
      // we must convert the date from the user input
      // since we use jquery.ui.datepicker and its i18n features, we
      // use the browser locale to know the format used
      
      DELT::object2object($this, $this->journalentry, array('firm_id', 'description', 'is_closing', 'is_adjustment'));
      $date=DateTime::createFromFormat(DELT::getConvertedJQueryUIDateFormat(), $this->date);
      $this->journalentry->date= $date ? $date->format('Y-m-d'): $this->date;
      
      if($this->is_new)
      {
        $this->journalentry->rank = $this->journalentry->getCurrentMaxRank() + 1;
      }
      
      $this->journalentry->save(true);
      
      $this->journalentry->deletePostings();
      
      $rank = 1;
      foreach($this->postings as $postingform)
      {
        if($postingform->account_id)
        {
          $posting = new Posting();
          
          
          if(substr($postingform->account_id, 0, 1)=='!')
          {
            $postingform->account->cleanup($this->firm);
            $postingform->account->basicSave(false);
            $postingform->account_id = $postingform->account->id;
          }
          
          $posting->journalentry_id = $this->journalentry->id;
          $posting->account_id = $postingform->account_id;
          $posting->amount = $postingform->debit - $postingform->credit;
          $posting->comment = $postingform->comment;
          $posting->rank = $rank++;
          
          $posting->save(true);
        }
      }
      
      $transaction->commit();
      
      Yii::app()->getUser()->setState($this->identifier_code, null);

      return true;
      
    }
    catch(Exception $e)
    {
      Yii::app()->user->setFlash('delt_failure',$e->getMessage()); 
      $transaction->rollback();
      return false;
    }
    
  }
  
  public function checkPostings() // $attribute,$params)
  {
    
    $grandtotal_debit = 0;
    $grandtotal_credit = 0;
    $errors=false;
    
    $used_accounts = array();
    $bang_accounts = array();

    $line_number = 0;
    foreach($this->postings as $row => $posting)
    {
      $last_line = $row;  //we use this later, for an amount proposal...
      $row_message = Yii::t('delt', 'Row {row}: ', array('{row}'=> ++$line_number));
      
      if($posting['name']=='')
      {
        // when the account name is not given, the whole line is completely ignored...
        continue;
      }

      if(strpos($posting['name'], '!')!==false)
      {
        // the bang sign means we have to create one (in the final transaction)
        $account = $this->firm->createBangAccount($posting['name']);
      }
      else
      {
        $info = explode(' - ', $posting['name']);
        $code = trim($info[0]);
        $account = $this->firm->findAccount($code); //Account::model()->findByAttributes(array('code'=>$code, 'firm_id'=>$this->firm_id, 'is_selectable'=>true));
      }
      
      if(!$account)
      {
        $this->addError('postings', $row_message . Yii::t('delt', 'the account with code "{code}" is not available (you can add it on the fly to the Chart of Accounts by adding an exclamation mark to the name, like in "{code}!").', array('{code}'=>$code)));
        $this->postings[$row]->name_errors=true;
        continue;
      }
      else
      {
        /*
        if(!in_array($account->id, $used_accounts))
        {
          */
          $this->postings[$row]->account_id = $account->id;
          $this->postings[$row]->account = $account;
          $used_accounts[] = $account->id;
          
          if(($pos=mb_strpos($posting['name'], '#'))!=false)
          {
            $this->postings[$row]->comment = trim(mb_substr($posting['name'], $pos+1));
          }

          
          
        /*
         * }
        else
        {
          $this->addError('postings', $row_message . Yii::t('delt', 'the account with code "{code}" makes the row a duplicate.', array('{code}'=>$code)));
        }
        */
      }
      
      $errors=false;
      $question=false;
      
      foreach(array('debit', 'credit') as $type)
      {
        $question = trim($posting[$type])=='?' ? true : $question;

        $posting[$type]=DELT::currency2decimal($posting[$type], $this->currency);
        $value=$posting[$type];

        $error=$type . '_errors';
        if($value!='' and !is_numeric($value))
        {
          $this->addError('postings', $row_message . Yii::t('delt', 'the value "{value}" is not numeric.', array('{value}'=>$value)));
          $this->postings[$row]->$error=true;
          $errors=true;
        }
        if($value<0)
        {
          $this->addError('postings', $row_message . Yii::t('delt', 'the value "{value}" cannot be negative.', array('{value}'=>$value)));
          $this->postings[$row]->$error=true;
          $errors=true;
        }
      }
      
      if($errors)
      {
        continue;
      }
      
      $debit = $this->postings[$row]['debit'];
      $credit = $this->postings[$row]['credit'];
      
      if($debit and $credit)
      {
          $this->addError('postings', $row_message . Yii::t('delt', 'you cannot have both a debit and a credit.'));
          $this->postings[$row]->debit_errors=true;
          $this->postings[$row]->credit_errors=true;
          $errors=true;
      }
      
      if(!$debit and !$credit)
      {
        if($question)
        {
          $v = $this->postings[$row]->account->consolidatedBalance;
          if($v > 0)
          {
            $credit = $v;
            $this->postings[$row]['credit']=$credit;
            $this->postings[$row]->guessed = true;
            $this->addError('postings', $row_message . Yii::t('delt', 'the amount of the credit has been computed as a balance for the account;'). ' ' . Yii::t('delt', 'it must be checked.'));
          }
          if($v < 0)
          {
            $debit = -$v;
            $this->postings[$row]['debit']=$debit;
            $this->postings[$row]->guessed = true;
            $this->addError('postings', $row_message . Yii::t('delt', 'the amount of the debit has been computed as a balance for the account;'). ' ' . Yii::t('delt', 'it must be checked.'));
          }
        }
        elseif($line_number==(sizeof($this->postings)))
        {
          // it is the last line, we can make a guess...
          if($grandtotal_debit > $grandtotal_credit)
          {
            $credit = $grandtotal_debit - $grandtotal_credit;
            $this->postings[$row]['credit']=$credit;
            $this->postings[$row]->guessed = true;
            
            $this->addError('postings', $row_message . Yii::t('delt', 'the amount of the credit has been computed by difference;'). ' ' . Yii::t('delt', 'it must be checked.'));
          }
          if($grandtotal_debit < $grandtotal_credit)
          {
            $debit = $grandtotal_credit - $grandtotal_debit;
            $this->postings[$row]['debit']=$debit;
            $this->postings[$row]->guessed = true;

            $this->addError('postings', $row_message . Yii::t('delt', 'the amount of the debit has been computed by difference;'). ' ' . Yii::t('delt', 'it must be checked.'));
          }
        }
        else
        {
          $this->addError('postings', $row_message . Yii::t('delt', 'you must have a debit or a credit.'));
          $this->postings[$row]->debit_errors=true;
          $this->postings[$row]->credit_errors=true;
          $errors=true;
        }
      }
      
      if(!$this->is_closing && !$this->is_adjustment && $this->postings[$row]->account && strpos($this->firm->checked_positions, $this->postings[$row]->account->position)!==false)
      {
        if($this->postings[$row]->account->outstanding_balance == 'D' && $credit>0)
        {
          $this->addError('postings', $row_message . 
            Yii::t('delt', 'you cannot do a credit to this kind of account') . ' ' .
            Yii::t('delt', '(unless the journal entry is marked as adjustment)') . '.'
            );
          $this->postings[$row]->credit_errors=true;
          $errors=true;
          $this->adjustment_checkbox_needed = true;
        }
        if($this->postings[$row]->account->outstanding_balance == 'C' && $debit>0)
        {
          $this->addError('postings', $row_message . 
            Yii::t('delt', 'you cannot do a debit to this kind of account') . ' ' .
            Yii::t('delt', '(unless the journal entry is marked as adjustment)') . '.'
            );
          $this->postings[$row]->debit_errors=true;
          $errors=true;
          $this->adjustment_checkbox_needed = true;
        }
      }
      
      
      if(!$errors)
      {
        $grandtotal_debit += $debit;
        $grandtotal_credit += $credit;
        
        $this->postings[$row]->analysis = $this->postings[$row]->account->getAnalysis($debit - $credit, $this->firm->currency);
        
      }
      
    }

    if($errors)
    {
      $this->_fixAmounts();
      return;
    }
    
    if($grandtotal_debit==0 and $grandtotal_credit==0)
    {
       $this->addError('postings', Yii::t('delt', 'No amounts specified.'));
    }
    
    if(abs($grandtotal_debit - $grandtotal_credit)>0.001) // floating point operations may yeld differences like -5.8207660913467E-11...
    {
       $this->addError('postings',
        Yii::t('delt', 'The total amount of debits ({debits}) does not match the total amounts of credits ({credits}).', array('{debits}'=>DELT::currency_value($grandtotal_debit, $this->currency, false, true), '{credits}'=>DELT::currency_value($grandtotal_credit, $this->currency, false, true)))
        . ' ' .
        Yii::t('delt', 'The imbalance is: {amount}.', array('{amount}'=>DELT::currency_value($grandtotal_debit - $grandtotal_credit, $this->currency, true)))
        );
    }
    
    if($this->hasErrors())
    {
      $this->_fixAmounts();
    }
    
    $this->total_debit = $grandtotal_debit;
    $this->total_credit = $grandtotal_credit;
    
  }
  
  private function _fixAmounts()
  {
    foreach($this->postings as $row => $posting)
    {
      foreach(array('debit', 'credit') as $type)
      {
        $this->postings[$row][$type]=$posting[$type] ? DELT::currency_value($posting[$type], $this->currency) : '';
      }
    }
  }
  
  public function setIdentifier()
  {
    $this->identifier_code = 'jei_'.md5($this->firm_id.'_'.($this->journalentry? $this->journalentry->id: 'new'));
    if(!Yii::app()->getUser()->getState($this->identifier_code))
    {
      $this->identifier = 'dei_' . md5(rand(0,10000000).microtime());
      Yii::app()->getUser()->setState($this->identifier_code, $this->identifier);
    }
  }

}

