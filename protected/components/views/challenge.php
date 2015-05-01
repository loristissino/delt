<div id="challenge">
<ul>

<?php 
$challenges = $this->getChallenges();
?>
<?php foreach($challenges as $challenge): ?>
<li><?php echo $challenge ?></li>
<?php endforeach ?>
</ul>

</div>
