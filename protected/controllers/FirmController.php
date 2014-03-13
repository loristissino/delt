<?php
/**
 * FirmController class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013 Loris Tissino
 * @since 1.0
 * 
 */
/**
 * The FirmController class.
 * 
 * @package application.controllers
 * 
 */
class FirmController extends Controller
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
//      'postOnly + delete', // we only allow deletion via POST request
      'postOnly + invitation', 
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
      /*
      array('allow',  // allow all users to perform 'index' and 'view' actions
        'actions'=>array('index','view'),
        'users'=>array('*'),
      ),
      */

      array('allow', // allow authenticated user to perform the following actions
        'actions'=>array('create','update','fork','prefork','owners','delete','share','invitation','disown','freeze','unfreeze'),
        'users'=>array('@'),
      ),
      array('allow', // allow admin user to perform 'admin' and 'delete' actions
        'actions'=>array('admin','delete'),
        'users'=>array('admin'),
      ),
      array('allow', // allow authenticated user to perform 'public' actions
        'actions'=>array('public','coa','index','ledger','banner'),
        'users'=>array('*'),
        ),
      array('deny',  // deny all users
        'users'=>array('*'),
      ),
    );
  }

  /**
   * Displays a particular model.
   * @param integer $id the ID of the model to be displayed
   */
  public function actionView($id)
  {
    $this->render('view',array(
      'model'=>$this->loadModel($id),
    ));
  }

  public function actionPublic($slug, $level=0)
  {
    $this->firm=$this->loadFirmBySlug($slug, false);
    if(sizeof($postings = $this->firm->getJournalentriesAsDataProvider(100000)->data))
    {
      $maxlevel = $this->firm->getCOAMaxLevel();
      if($level==0)
      {
         $level = $maxlevel;
      }
      Event::model()->log($this->DEUser, $this->firm->id, Event::FIRM_SEEN);
      $this->render('public', array(
        'model'=>$this->firm,
        'postings'=>$postings,
        'level'=>$level,
        'maxlevel'=>$maxlevel,
      ));
    }
    else
    {
      $this->render('empty', array(
        'model'=>$this->firm
      ));
    }
  }

  public function actionBanner($slug)
  {
    $this->firm=$this->loadFirmBySlug($slug, false);
    $this->serveContent('image/png', $this->firm->banner);
  }

  public function actionLedger($slug, $account)
  {
    $this->firm=$this->loadFirmBySlug($slug, false);
    $account = $this->loadAccount($account);

    if(sizeof($postings = $this->firm->getAccountPostingsAsDataProvider($account->id, 100000)->data))
    {
      $this->render('ledger', array(
        'model'=>$this->firm,
        'postings'=>$postings,
        'account'=>$account,
      ));
    }

  }

  public function actionCoa($slug)
  {
    $this->firm=$this->loadFirmBySlug($slug, false);
    if($this->firm->status != Firm::STATUS_SYSTEM)
    {
      throw new CHttpException(404, 'This page is available only for system firms.');
    }
    
    $this->render('coa', array(
      'model'=>$this->firm,
      'accounts'=>$this->firm->accounts,
    ));
  }

  public function actionOwners($slug)
  {
    throw new CHttpException(501, 'Not yet implemented.');
  }

  /**
   * Creates a new model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   */
  public function actionCreate($step=1)
  {
    
    $this->checkUserStatus();
    $model=new Firm;
    $model->currency = 'EUR';
    $model->setDefaultLanguageFromUserProfile($this->DEUser);

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if(isset($_POST['Firm']))
    {
      if($this->DEUser->canCreateFirms())
      {
        $model->attributes=$_POST['Firm'];
        if($model->validate())
        {
          if($model->saveWithOwner($this->DEUser))
          {
            
            $languages = isset($_POST['Firm']['languages']) ? $_POST['Firm']['languages'] : array();
            $model->saveLanguages($languages);
            
            Yii::app()->getUser()->setFlash('delt_success', Yii::t('delt', 'The firm has been successfully created.'));
            Event::model()->log($this->DEUser, $model->id, Event::FIRM_CREATED, array_diff_key(
                $model->attributes,
                array('id'=>true, 'create_date'=>true, 'frozen_at'=>true)
                )
            );

            $this->redirect(array('/bookkeeping/manage','slug'=>$model->slug));
          }
          else
          {
            Yii::app()->getUser()->setFlash('delt_failure', Yii::t('delt', 'Sorry, something wrong happened and the creation of the firm failed.'));
            $this->redirect(array('/bookkeeping/index'));
          }
        }
        else
        {
          if(!$model->slug)
          {
            $model->slug = md5($model->name . rand(0, 100000));
          }
        }
      }
      else
      {
        Yii::app()->getUser()->setFlash('delt_failure', Yii::t('delt', 'Sorry, you are not allowed to create firms at this time.'));
        $this->redirect(array('/bookkeeping/index'));
      }
    }

    $this->render('create',array(
      'model'=>$model,
      'step'=>$step,
    ));
  }

  public function actionPrefork($slug=null)
  {
    $this->checkUserStatus();
    $firm=Firm::model()->findByAttributes(array('slug'=>$slug));
    if($firm)
    {
      $this->redirect(array('firm/fork', 'slug'=>$firm->slug));
    }
    else
    {
      $this->render('prefork',array(
        'slug'=>$slug,
      ));
    }
  }

  /**
   * Forks an existing, public, firm, or a personal private firm.
   * If creation is successful, the browser will be redirected to the 'update' page.
   */
  public function actionFork($slug=null)
  {
    $this->checkUserStatus();
    $firm = null;
    if($slug)
    {
      $firm = $this->loadFirmBySlug($slug, false);
      if(!$firm->isForkableBy($this->DEUser))
        throw new CHttpException(403, 'You are not allowed to access the requested page.');
    }
    
    $form = new ForkfirmForm;
    $form->change_language=true;
    
    if(isset($_POST['ForkfirmForm']))
    {
      if($this->DEUser->canCreateFirms())
      {
        $form->attributes=$_POST['ForkfirmForm'];
        
        if($form->validate())
        {
          $newfirm = new Firm();
          try
          {
            $newfirm->forkFrom($firm, $this->DEUser, $form->type);
            if($form->change_language)
            {
              $newfirm->setDefaultLanguageFromUserProfile($this->DEUser);
              $newfirm->save();
            }
            $newfirm->fixAccounts();
            $newfirm->fixAccountNames();
            Event::model()->log($this->DEUser, $newfirm->id, Event::FIRM_FORKED, array('parent_firm_id'=>$firm->id, 'type'=>$form->type));
            $this->redirect(array('bookkeeping/manage','slug'=>$newfirm->slug));
          }
          catch(Exception $e)
          {
            Yii::app()->user->setFlash('delt_failure','The information about the firm could not be saved.'); 
            $this->redirect(array('firm/form'));
          }
        }
      }
      else
      {
        Yii::app()->getUser()->setFlash('delt_failure', Yii::t('delt', 'Sorry, you are not allowed to create firms at this time.'));
        $this->redirect(array('/bookkeeping/index'));
      }
    }

    if($firm)
    {
      $this->render('fork_confirm',array(
        'firm'=>$firm, 'forkfirmform'=>$form,
      ));
    }
    else
    {
      $this->render('fork', array(
        'publicfirms'=>Firm::model()->findForkableFirms(),
        'ownfirms'=>$this->DEUser->firms,
      ));
    }
  }

  /**
   * Updates a particular model.
   * If update is successful, the browser will be redirected to the 'view' page.
   * @param integer $id the ID of the model to be updated
   */
  public function actionUpdate($id=null)
  {
    $this->firm=$this->loadFirm($id);
    $this->checkFrostiness($this->firm);
    
    // Uncomment the following line if AJAX validation is needed
     $this->performAjaxValidation($this->firm);
     
     $old_language_id = $this->firm->language_id;

    if(isset($_POST['Firm']))
    {
      $this->firm->attributes=$_POST['Firm'];
      if($this->firm->validate())
      {
        try
        {
          if(!empty($_FILES['Firm']['tmp_name']['banner']))
          {
            $this->firm->acquireBanner(CUploadedFile::getInstance($this->firm,'banner'));
          }
          
          $this->firm->save(false);
          
          Event::model()->log($this->DEUser, $this->firm->id, Event::FIRM_EDITED, array_diff_key(
              $this->firm->attributes,
              array('id'=>true, 'create_date'=>true, 'frozen_at'=>true)
              )
          );
          
          $languages = isset($_POST['Firm']['languages']) ? $_POST['Firm']['languages'] : array();
          $this->firm->saveLanguages($languages);

          if($this->firm->language_id != $old_language_id)
          {
            $this->firm->fixAccountNames();
          }
          Yii::app()->user->setFlash('delt_success','The information about the firm has been correctly saved.'); 
          $this->redirect(array('bookkeeping/manage','slug'=>$this->firm->slug));
        }
        catch(Exception $e)
        {
          Yii::app()->user->setFlash('delt_failure','The information about the firm could not be saved.' . $e->getMessage()); 
        }
      }
    }

    $this->render('update',array(
      'model'=>$this->firm
    ));
  }

  /**
   * Shares a firm, by inviting another user.
   * If update is successful, the browser will be redirected to the 'view' page.
   * @param string $slug the slug of the firm to be shared
   */
  public function actionShare($slug)
  {
    $model=$this->loadFirmBySlug($slug);
    $this->checkFrostiness($model);
    
    if(isset($_POST['username']) && $username = $_POST['username'])
    {
      if($model->invite($username))
      {
          Event::model()->log($this->DEUser, $model->id, Event::FIRM_SHARED, array('username'=>$username));
          Yii::app()->user->setFlash('delt_success',Yii::t('delt', 'An invitation has been sent to «{username}». When accepted, the firm will be considered shared.', array('{username}'=>$username))); 
          $this->redirect(array('bookkeeping/manage','slug'=>$model->slug));
      }
      else
      {
        Yii::app()->user->setFlash('delt_failure',Yii::t('delt', 'The invitation could not be sent to «{username}». Please check the username.', array('{username}'=>$username))); 
      }
    }
    
    $this->render('share',array(
      'model'=>$model,
    ));
  }

  public function actionInvitation($slug)
  {
    $model=$this->loadFirmBySlug($slug, false);
    $this->checkFrostiness($model);
    
    if(isset($_GET['action']) && $_GET['action']=='accept')
    {
      if($this->DEUser->profile->allowed_firms - sizeof($this->DEUser->firms)>0)
      {
        if($fu = FirmUser::model()->findByAttributes(array('firm_id'=>$model->id, 'user_id'=>$this->DEUser->id, 'role'=>'I')))
        {
          $fu->role='O';
          $fu->save();
          Event::model()->log($this->DEUser, $model->id, Event::FIRM_JOINED);
          Yii::app()->user->setFlash('delt_success',Yii::t('delt', 'You are now allowed to manage the firm «{firm}».', array('{firm}'=>$model->name))); 
        }
        else
        {
          Yii::app()->user->setFlash('delt_failure',Yii::t('delt', 'Something went wrong with the firm «{firm}».', array('{firm}'=>$model->name))); 
        }
      }
      else
      {
        Yii::app()->user->setFlash('delt_failure',Yii::t('delt', 'Sorry, you already reached the number of allowed firms.')); 
      }
      
      
    }

    if(isset($_GET['action']) && $_GET['action']=='decline')
    {
      if($fu = FirmUser::model()->findByAttributes(array('firm_id'=>$model->id, 'user_id'=>$this->DEUser->id, 'role'=>'I')))
      {
        $fu->delete();
        Event::model()->log($this->DEUser, $model->id, Event::FIRM_DECLINED);
        Yii::app()->user->setFlash('delt_success',Yii::t('delt', 'You successfully declined the invitation to manage the firm «{firm}».', array('{firm}'=>$model->name)));
      }
      else
      {
        Yii::app()->user->setFlash('delt_failure',Yii::t('delt', 'Something went wrong with the firm «{firm}».', array('{firm}'=>$model->name))); 
      }
    }

    $this->redirect(array('bookkeeping/index'));

    
  }

  public function actionDisown($slug)
  {
    $this->firm=$model=$this->loadFirmBySlug($slug, false);
    $this->checkFrostiness($model);
    
    if(isset($_POST['disown']))
    {
      if($model->getOwners()>=1)
      {
        if($fu = FirmUser::model()->findByAttributes(array('firm_id'=>$model->id, 'user_id'=>$this->DEUser->id, 'role'=>'O')))
        {
          $fu->delete();
          Event::model()->log($this->DEUser, $model->id, Event::FIRM_DISOWNED);
          
          Yii::app()->user->setFlash('delt_success',Yii::t('delt', 'You successfully disowned the firm «{firm}».', array('{firm}'=>$model->name)));
        }
        else
        {
          Yii::app()->user->setFlash('delt_failure',Yii::t('delt', 'Something went wrong with the firm «{firm}».', array('{firm}'=>$model->name))); 
        }
      }
      else
      {
        Yii::app()->user->setFlash('delt_failure',Yii::t('delt', 'You are not allowed to disown the firm «{firm}».', array('{firm}'=>$model->name))); 
      }
      
      $this->redirect(array('bookkeeping/index'));
    }
    
    $this->render('disown',array(
      'model'=>$model
    ));
    
  }

  private function _frostiness($slug)
  {
    $model=$this->loadFirmBySlug($slug);
    
    if(isset($_POST['freeze']))
    {
      if($model->freeze($this->DEUser->id))
      {
        Event::model()->log($this->DEUser, $model->id, Event::FIRM_FROZEN);
        Yii::app()->user->setFlash('delt_success',Yii::t('delt', 'You successfully freezed the firm «{firm}».', array('{firm}'=>$model->name)));
        $this->redirect(array('bookkeeping/manage', 'slug'=>$model->slug));
      }
      else
      {
        Yii::app()->user->setFlash('delt_failure',Yii::t('delt', 'Something went wrong with the freezing of the firm «{firm}».', array('{firm}'=>$model->name))); 
      }
    }

    if(isset($_POST['unfreeze']))
    {
      if($model->unfreeze($this->DEUser->id))
      {
        Event::model()->log($this->DEUser, $model->id, Event::FIRM_UNFROZEN);
        Yii::app()->user->setFlash('delt_success',Yii::t('delt', 'You successfully unfreezed the firm «{firm}».', array('{firm}'=>$model->name)));
        $this->redirect(array('bookkeeping/manage', 'slug'=>$model->slug));
      }
      else
      {
        Yii::app()->user->setFlash('delt_failure',Yii::t('delt', 'Something went wrong with the unfreezing of the firm «{firm}».', array('{firm}'=>$model->name))); 
      }
    }
    
    $this->render('frostiness',array(
      'model'=>$model
    ));

  }

  public function actionFreeze($slug)
  {
    return $this->_frostiness($slug);
  }

  public function actionUnfreeze($slug)
  {
    return $this->_frostiness($slug);
  }
  

  /**
   * Deletes a particular model.
   * If deletion is successful, the browser will be redirected to the 'admin' page.
   * @param integer $id the ID of the model to be deleted
   */
  public function actionDelete($slug)
  {
    $this->firm=$model=$this->loadFirmBySlug($slug, false);
    $this->checkFrostiness($model);
    
    if(isset($_POST['delete']))
    {
      if($this->firm->softDelete())
      {
        Event::model()->log($this->DEUser, $this->firm->id, Event::FIRM_DELETED);
        Yii::app()->getUser()->setFlash('delt_success', Yii::t('delt', 'The firm has been correctly deleted.'));
        $this->redirect(array('/bookkeeping/index'));
      }
      else
      {
        Yii::app()->getUser()->setFlash('delt_failure', Yii::t('delt', 'The firm could not be deleted.') . ' ' . Yii::app()->getUser()->getFlash('delt_failure'));
        $this->redirect(array('/bookkeeping/manage', 'slug'=>$firm->slug));
      }
    }
    
    $this->render('delete',array(
      'model'=>$model
    ));

  }

  /**
   * Lists all models.
   */
  public function actionIndex($token)
  {
    
    if(!isset(Yii::app()->params['list_token']) or $token!=Yii::app()->params['list_token'])
    {
      throw new CHttpException(404,'The requested page does not exist.');
    }
    
    $dataProvider=new CActiveDataProvider('Firm');
    $firm = new Firm('search');
    $firm->unsetAttributes();
    $firm->status = 2;

    $this->layout='html5';
    $this->render('index',array(
      'dataProvider'=>$firm->search(),
      
    ));
  }

  /**
   * Manages all models.
   */
  /*
  public function actionAdmin()
  {
    $model=new Firm('search');
    $model->unsetAttributes();  // clear any default values
    if(isset($_GET['Firm']))
      $model->attributes=$_GET['Firm'];

    $this->render('admin',array(
      'model'=>$model,
    ));
  }
  */

  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer $id the ID of the model to be loaded
   * @return Firm the loaded model
   * @throws CHttpException
   */
  public function loadModel($id)
  {
    return $this->loadFirm($id);
  }

  /**
   * Performs the AJAX validation.
   * @param Firm $model the model to be validated
   */
  protected function performAjaxValidation($model)
  {
    if(isset($_POST['ajax']) && $_POST['ajax']==='firm-form')
    {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
  }
}
