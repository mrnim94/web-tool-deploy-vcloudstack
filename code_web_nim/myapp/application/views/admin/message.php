<?php
	if (isset($message) && $message)
	{
	?>
		<div class="alert alert-rose alert-with-icon" data-notify="container">
	        <i class="material-icons" data-notify="icon">notifications</i>
	        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	            <i class="material-icons">close</i>
	        </button>
	        <span data-notify="message"><?php echo $message; ?></span>
	    </div>
	<?php
	}
 ?>