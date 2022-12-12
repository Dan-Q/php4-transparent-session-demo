<?
session_register('counter');
if( ! $counter ) $counter = 0;
?>
<p>
  <b>Pages loaded: <?= $counter++ ?></b>
  👈 
  <i>watch me grow!</i>
</p>
