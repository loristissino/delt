<div class="comment">
<?php
  //$keywords = array_keys(Yii::app()->params['keywords_for_comments']);
  $lines = explode("\n", $account->comment);
  $text = '';
  foreach($lines as $line)
  {
    $matches = array();
    if(preg_match('/^@[a-z]*/', $line, $matches))
    {
      $first=$matches[0];
      //if(in_array($first, $keywords))
      //{
        $text .= '<span class="keyword">' . $first . '</span> ';
        $remaining = substr($line, strlen($first)+1);
        $text .= $first=='@href' ? '<a href="' . $remaining . '">' . $remaining . '</a>' : $remaining;
      //}
    }
    else
    {
      $text .= $line;
    }
  }
?>
<?php echo nl2br($text) ?>
</div>
