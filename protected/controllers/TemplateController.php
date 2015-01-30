<?php
/**
 * TemplateController class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013-2015 Loris Tissino
 * @since 1.7
 * 
 */
/**
 * The TemplateController class.
 * 
 * @package application.controllers
 * 
 */

class TemplateController extends Controller
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
      array('allow', // allow authenticated user to perform 'create' and 'update' actions
        'actions'=>array('admin', 'test'),
        'users'=>array('@'),
      ),
      array('deny',  // deny all users
        'users'=>array('*'),
      ),
    );
  }

  /**
   * Manages all models.
   */
  public function actionAdmin($slug)
  {
    $this->firm = $this->loadFirmBySlug($slug);
    $this->checkFrostiness($this->firm);
    $model=new Template('search');
    $model->unsetAttributes();  // clear any default values
    $model->firm_id = $this->firm->id;
    $this->render('admin',array(
      'model'=>$model,
    ));
  }
  
  public function renderAutomatic(Template $template, $row)
  {
    return $this->renderPartial('_automatic',array('template'=>$template),true);
  }

  public function renderDescription(Template $template, $row)
  {
    return CHtml::link($template->description, $this->createUrl('bookkeeping/journalentryfromtemplate', array('id'=>$template->id))); 
  }

}
