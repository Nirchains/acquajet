<?php defined('_JEXEC') or die(file_get_contents('index.html'));
?>
<div class="alert alert-<?php 
echo $displayData['type'];
?>
">
	<h3><i class="icon-<?php 
echo $displayData['icon'];
?>
"></i> <?php 
echo $displayData['title'];
?>
</h3>
	<p><?php 
echo $displayData['message'];
?>
</p>
	<?php 
foreach ($displayData['buttons'] as $button)
{
	?>
		<a class="<?php 
	echo $button['class'];
	?>
" href="<?php 
	echo $button['url'];
	?>
"><?php 
	echo $button['label'];
	?>
</a>
	<?php 
}

?>
</div>

