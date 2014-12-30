<div class="comment">
<?php
  $text = '';
  foreach($account->getKeywordsAndValuesFromComment() as $keyword=>$value)
  {
    if($keyword===0)
    {
      $text .= $value;
    }
    else
    {
      $text .= '<span class="keyword">' . $keyword . '</span> ';
      $text .= ($keyword=='@href' ? '<a href="' . $value . '" target="_blank">' . $value . '</a>' : $value) . "\n";
    }
  }
?>
<?php echo nl2br($text) ?>
</div>
