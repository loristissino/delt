<?php
/**
 * AccountController class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013-2017 Loris Tissino
 * @since 1.0
 * 
 */
/**
 * The AccountController class.
 * 
 * @package application.controllers
 * 
 */
class AccountController extends Controller
{
  /**
   * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
   * using two-column layout. See 'protected/views/layouts/column2.php'.
   */
  public $layout='//layouts/column2';

  /**
   * @return array action filters
   */
  public function filters()
  {
    return array(
      'accessControl', // perform access control for CRUD operations
      'postOnly + delete', // we only allow deletion via POST request
    );
  }

  /**
   * Specifies the access control rules.
   * This method is used by the 'accessControl' filter.
   * @return array access control rules
   */
  public function accessRules()
  {
    return array(
      array('allow', 
        'actions'=>array('create','update','delete','synchronize','import','export','dragdrop'),
        'users'=>array('@'),
      ),
      array('allow', 
        'actions'=>array('admin'),
        'users'=>array('admin'),
      ),
      array('deny',  // deny all users
        'users'=>array('*'),
      ),
    );
  }

  /**
   * Creates a new model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   */
  public function actionCreate($slug, $id=null, $config=false)
  {
    $this->firm = $this->loadFirmBySlug($slug);
    $this->checkFrostiness($this->firm);
    $model=new Account;
    $model->type = $config;
    $model->firm_id = $this->firm->id;
    $model->firm = $this->firm;
    $model->setDefaultForNames();

    if($id)
    {
      $parent = $this->loadAccount($id);
      $model->code = $parent->code . '.';
      $model->position = $parent->position;
      $model->outstanding_balance = $parent->outstanding_balance;
      $model->type = $parent->type;
    }
    else
    {
      $parent = null;
    }

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if(isset($_POST['Account']))
    {
      $model->attributes=$_POST['Account'];
      if($model->validate())
      {
        if($model->save())
        {
          $this->firm->fixAccounts();
          $this->redirect(array($model->isHidden()? 'bookkeeping/configure':'bookkeeping/coa','slug'=>$this->firm->slug));
        }
      }
    }

    $this->render('create',array(
      'account'=>$model,
      'firm'=>$this->firm,
      'parent'=>$parent,
    ));
  }

  /**
   * Updates a particular model.
   * If update is successful, the browser will be redirected to the 'view' page.
   * @param integer $id the ID of the model to be updated
   */
  public function actionUpdate($id, $targetcode='', $sourcecode='')
  {
    $account=$this->loadModel($id);
    $this->checkManageability($this->firm=$this->loadFirm($account->firm_id));
    $this->checkFrostiness($this->firm);
    $account->fixDefaultForNames();
    $oldPosition = $account->position;
    
    $account->subchoices = $account->subchoices!=0;
    // this is needed because otherwise the checkbox is not properly set in the form
    
    if($targetcode)
    {
      $parent = $this->firm->findAccount($targetcode, false, true);
    }
    else
    {
      $parent = $account->getParentAccount();
    }
    if($targetcode)
    {
      $account->code = $targetcode . '.';
      if($this->firm->shortcodes and $sourcecode)
      {
        $account->code .= $this->firm->renderAccountCode($sourcecode);
      }
    }
    
    if(isset($_POST['Account']))
    {
      $account->attributes=$_POST['Account'];
      if($account->validate())
      {
        if($account->save())
        {
          if($account->isHidden())
          {
            $this->firm->updateAccountsPositions($oldPosition, $account->position);
          }
          $this->firm->fixAccounts();
          Event::log($this->DEUser, $this->firm->id, Event::FIRM_COA_UPDATED);
          $this->redirect(array($account->isHidden()? 'bookkeeping/configure':'bookkeeping/coa','slug'=>$this->firm->slug));
        }
      }
    }

    $this->render('update',array(
      'account'=>$account,
      'firm'=>$this->firm,
      'parent'=>$parent,
      'moving'=>$targetcode,
    ));
  }


  public function actionDragdrop($source='', $target='')
  {
    $sourceAccount=$this->loadModel($source);
    $targetAccount=$this->loadModel($target);
    $this->redirect(array('account/update', 'id'=>$sourceAccount->id, 'targetcode'=>$targetAccount->code, 'sourcecode'=>$sourceAccount->code));
  }

  /**
   * Deletes a particular model.
   * If deletion is successful, the browser will be redirected to the 'admin' page.
   * @param integer $id the ID of the model to be deleted
   */
  public function actionDelete($id)
  {
    $account=$this->loadAccount($id);
    $this->checkManageability($firm=$this->loadFirm($account->firm_id));
    $this->checkFrostiness($firm);
    
    if(!Yii::app()->getRequest()->isPostRequest)
    {
      throw new CHttpException(404,'The requested page does not exist.'); 
    }
    
    try
    {
      $account->delete();
      $firm->fixAccounts();
    }
    catch (Exception $e)
    {
      return false;
    }

    // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
    if(!isset($_GET['ajax']))
      $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array($account->isHidden()? 'bookkeeping/configure':'bookkeeping/coa', 'slug'=>$firm->slug));
    
  }

  /**
   * Synchronizes the whole chart of accounts from parent firm or other ancestor.
   */
  public function actionSynchronize($slug, $ancestor='')
  {
    $this->firm = $this->loadFirmBySlug($slug);
    $this->checkFrostiness($this->firm);
    
    if($ancestor and $ancestor=$this->loadFirmBySlug($ancestor, false) and $this->firm->isDescendantOf($ancestor))
    {
      if(Yii::app()->getRequest()->isPostRequest)
      {
        if($this->firm->synchronizeAccounts($_POST))
        {
          $this->firm->fixAccounts();
          $this->firm->fixAccountNames();
          Yii::app()->getUser()->setFlash('delt_success', 'Accounts correctly synchronized.');
        }
        else
        {
          Yii::app()->getUser()->setFlash('delt_failure', 'The accounts could not be synchronized.');
        }
        $this->redirect(array('bookkeeping/coa','slug'=>$this->firm->slug));
      }
      
      $this->render('synchronize',array(
        'firm'=>$this->firm,
        'ancestor'=>$ancestor,
        'diff'=>$this->firm->findDifferentAccounts($ancestor),
      ));
    }
    else
    {
      $this->render('synchronize',array(
        'firm'=>$this->firm,
        'ancestors'=>$this->firm->ancestors,
      ));
    }
  }

  public function actionImport($slug)
  {
    $this->firm = $this->loadFirmBySlug($slug);
    $this->checkFrostiness($this->firm);
    
    $accounts_form = new IEAccountsForm;
    
    if(Yii::app()->getRequest()->isPostRequest)
    {
      $accounts_form->attributes=$_POST['IEAccountsForm'];
      if($accounts_form->validate())
      {
        $count = $this->firm->importAccountsFrom($accounts_form);
        Yii::app()->getUser()->setFlash('delt_success', 'Accounts correctly imported: ' . $count);
        $this->firm->fixAccounts();
        $this->redirect(array('bookkeeping/coa','slug'=>$this->firm->slug));
      }
    }
      
    $this->render('import',array(
      'firm'=>$this->firm,
      'model'=>$accounts_form,
    ));
  }

  public function actionExport($slug)
  {
    $this->firm = $this->loadFirmBySlug($slug);

    $accounts_form = new IEAccountsForm;
    $accounts_form->loadAccounts($this->firm);
    
    $this->render('export',array(
      'firm'=>$this->firm,
      'model'=>$accounts_form,
    ));
  }


  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer $id the ID of the model to be loaded
   * @return Account the loaded model
   * @throws CHttpException
   */
  public function loadModel($id)
  {
    return $this->loadAccount($id);
    // we define this in the parent class, because it is of common use...
  }

  /**
   * Performs the AJAX validation.
   * @param Account $model the model to be validated
   */
  protected function performAjaxValidation($model)
  {
    if(isset($_POST['ajax']) && $_POST['ajax']==='account-form')
    {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
  }
}
