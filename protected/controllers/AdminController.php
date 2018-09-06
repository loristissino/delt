<?php
/**
 * AdminController class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013-2018 Loris Tissino
 * @since 1.9.55
 */
/**
 * The AdminController class.
 * 
 * @package application.controllers
 * 
 */
class AdminController extends Controller
{
    
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
        'actions'=>array('none'),
        'users'=>array('*'),
      ),
      array('allow', // allow admin user to perform the selected actions
        'actions'=>array('index', 'profiles'),
        'users'=>array('admin'),
      ),
      array('deny',  // deny all users
        'users'=>array('*'),
      ),
    );
  }


  /**
   * This is the default 'index' action that is invoked
   * when an action is not explicitly requested by users.
   */
  public function actionIndex()
  {
    $this->render('index');
  }
  

  /**
   * This is the action that is invoked
   * when the user wants a list of profiles.
   */
  public function actionProfiles()
  {
    $users = DEUser::model()->findAll();
    $this->render('profiles', array('users'=>$users));
  }

  

}
