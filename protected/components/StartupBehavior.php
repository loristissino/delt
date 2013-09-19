<?php
/**
 * StartupBehavior class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013 Loris Tissino
 * @since 1.0
 */
/**
 * StartupBehavior is a customized behavior class.
 *
 * @package application.behaviors
 * 
 */

class StartupBehavior extends CBehavior
{
  
  // an alternative could be the one described here: http://php-thoughts.cubedwater.com/2010/alternative-to-the-beforeaction-event/
    public function attach($owner)
    {
        $owner->attachEventHandler('onBeginRequest', array($this, 'beginRequest'));
    }

    public function beginRequest(CEvent $event)
    {
        $language=Yii::app()->getUser()->getState('language');
        if(!$language)
        {
          $language = Yii::app()->request->getPreferredLanguage();
        }
        $info=explode('_', $language);
        if(sizeof($info)>1)
        {
          list($lang, $country)=explode('_', $language);
        }
        else
        {
          $lang=$language;
        }
        
        if(in_array($lang, array_keys(Yii::app()->params['available_languages'])))
        {
          Yii::app()->language = $lang;
        }
        
        // http://learnyii.blogspot.it/2011/03/yii-theme-iphone-android-blackberry.html
        Yii::app()->theme = Yii::app()->session['theme'];
        
    }
}
