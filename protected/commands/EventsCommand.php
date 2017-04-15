<?php
/**
 * EventsCommand class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013-2017 Loris Tissino
 * @since 1.9
 * 
 * @package application.commands
 * 
 */

/*
 *  run this command with something like:
 *  ./yiic events
 * 
 * see http://www.yiiframework.com/doc/guide/1.1/en/topics.console
*/

class EventsCommand extends CConsoleCommand
{
  
  public function actionIndex($begin='2000-01-01', $end='2099-12-31')
  {
    $events=Event::model()->happenedBetween($begin, $end)->findAll();
    
    foreach($events as $event)
    {
      echo implode("\t", array($event->id, $event->user_id, $event->firm_id, $event->action, $event->happened_at)) . "\n";
    }
  }
  
  public function actionDeleteUseless()
  {
    // we keep a backup of events elsewhere, so we can safely delete events
    
    $date = date('Y-m-d', time()-2*24*3600);  // a couple of days ago...
    
    //Event::model()->withoutFirm()->happenedBefore($date)->deleteAll();
    // the previous code does not work:
    // see http://www.yiiframework.com/wiki/728/using-updateall-and-deleteall-with-scopes/
    
    foreach(Event::model()->happenedBefore($date)->findAll() as $event)
    {
      echo $event . "...";
      if ($event->action < Event::USER_SIGNED_UP or ($event->firm && $event->firm->status == Firm::STATUS_CLEARED && !in_array($event->action, array(Event::FIRM_CREATED, Event::FIRM_DELETED))))
      {
        $event->delete();
        echo " deleted";
      }
      else
      {
        echo " kept";
      }
      echo "\n";
    }
  }

}
