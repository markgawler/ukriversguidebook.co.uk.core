<?php 
// No direct access
defined('_JEXEC') or die; ?>
<div class="flex-video widescreen">
	<iframe allowfullscreen src="<?php echo $url ?>" frameborder="0" width="1024" height="576"></iframe>
</div>
<p><?php echo $acknowledgmentPrefix?> <a href="<?php echo $acknowledgmentLink?>"><?php echo $acknowledgmentName?></a></p>
