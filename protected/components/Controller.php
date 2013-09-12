<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
  /**
   * @var string the default layout for the controller view. Defaults to '//layouts/column1',
   * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
   */
  public $layout='//layouts/column1';
  
  /**
   * @var array context menu items. This property will be assigned to {@link CMenu::items}.
   */
  public $menu=array();
  public $menutitle='Operations';

  /**
   * @var Firm The current selected firm, identified by slug or Pk.
   */
  public $firm=null;

  /**
   * @var Post The current selected post, identified by Pk.
   */
  public $post=null;
  
  /**
   * @var array general firm menu items. This property will be assigned to {@link CMenu::items}.
   */
  public $firmmenu=array();

    
  /**
   * @var array the breadcrumbs of the current page. The value of this property will
   * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
   * for more details on how to specify this property.
   */
  public $breadcrumbs=array();
  
  public $DEUser; //= DEUser::model()->findByPK(Yii::app()->user->id);
  
  protected function beforeAction($action)
  {
    $this->DEUser = DEUser::model()->findByPK(Yii::app()->user->id);
    return parent::beforeAction($action);
  }

  /**
   * Checks whether a firm is manageable by the logged-in user.
   * If not, an HTTP exception will be raised.
   * @param integer $id the ID of the model to be loaded
   * @return Account the loaded model
   * @throws CHttpException
   */
  public function checkManageability(Firm $firm)
  {
    if(!$firm->isManageableBy($this->DEUser))
      throw new CHttpException(403, 'You are not allowed to access the requested page.');
  }

  /**
   * Returns the Firm object based on the id value given.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer $id the id of the model to be loaded
   * @return Firm the loaded model
   * @throws CHttpException
   */
  public function loadFirm($id, $check_manageability=true)
  {
    $firm=Firm::model()->findByPk($id);
    return $this->_loadFirm($firm, $check_manageability);
  }

  /**
   * Returns the data model based on the slug value given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param string $slug the slug of the model to be loaded
   * @return Firm the loaded model
   * @throws CHttpException
   */
  public function loadFirmBySlug($slug, $check_manageability=true)
  {
    $firm=Firm::model()->findByAttributes(array('slug'=>$slug));
    return $this->_loadFirm($firm, $check_manageability);
  }
  
  private function _loadFirm($firm, $check_manageability)
  {
    if($firm===null)
      throw new CHttpException(404,'The requested page does not exist.');
    if($firm->status==Firm::STATUS_SUSPENDED)
      throw new CHttpException(403, 'The access to this firm\'s data is currently suspended.');
    if($firm->status==Firm::STATUS_DELETED)
      throw new CHttpException(410, 'This firm has been deleted.');
    if($firm->slug=='teapot')
      throw new CHttpException(418, 'I am a teapot.');
    if($check_manageability && !$this->DEUser)
      throw new CHttpException(401,'You must be authenticated to access this page.');
    if($check_manageability)
    {
      $this->checkManageability($firm);
    }
    return $firm;
  }
  
  
  /**
   * Returns the account model based on the primary key.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer $id the ID of the model to be loaded
   * @return Account the loaded model
   * @throws CHttpException
   */
  public function loadAccount($id)
  {
    $model=Account::model()->findByPk($id);
    if($model===null)
      throw new CHttpException(404,'The requested page does not exist.');
    return $model;
  }
  
  /**
   * Returns the post model based on the primary key.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer $id the ID of the model to be loaded
   * @return Post the loaded model
   * @throws CHttpException
   */
  public function loadPost($id)
  {
    $model=Post::model()->findByPk($id);
    if($model===null)
      throw new CHttpException(404,'The requested page does not exist.');
    return $model;
  }
  
  /**
   * Sends a Content-Disposition HTTP header.
   * @param string $filename the filename being sent
   */  
  public function sendDispositionHeader($filename)
  {
    header(sprintf('Content-Disposition: attachment; filename="%s"', $filename));
  }

  /**
   * Serves an object as a json-encoded string via HTTP.
   * @param string $object the object to send
   */  
  public function serveJson($object)
  {
    $this->serveContent('application/json', CJSON::encode($object), false);
  }

  /**
   * Serves a content via HTTP.
   * @param string $type the Internet Media Type (MIME) of the content
   * @param string $content the content to send
   */  
  public function serveContent($type, $content)
  {
    $this->_serve($type, $content, false);
  }

  /**
   * Serves a file via HTTP.
   * @param string $type the Internet Media Type (MIME) of the file
   * @param string $file the file to send
   */  
  public function serveFile($type, $file)
  {
    $this->_serve($type, $file, true);
  }

  /**
   * Serves something via HTTP.
   * @param string $type the Internet Media Type (MIME) of the content
   * @param string $content the content to send
   * @param boolean $is_file whether the content is a file
   */  
  private function _serve($type, $content, $is_file=false)
  {
    header("Content-Type: " . $type);
    if ($is_file)
    {
      readfile($content);
    }
    else
    {
      echo $content;
    }
    Yii::app()->end();
  }
  
  
  public function createIcon($name, $alt='', $htmlOptions=array(), $extension='.png')
  {
    return CHtml::image(Yii::app()->request->baseUrl.'/images/' . $name . $extension, $alt, $htmlOptions);
  }
  
}
