</div><!-- main content -->
</div><!-- wrapper -->
<?php
if($_SESSION['alert'] != ''){
	
	echo'
	<script>
	$(document).ready(function(){
		AlertD("'.$_SESSION['alert'].'");
	});
	</script> 
	';
	$_SESSION['alert'] = '';
}

?>
</div><!-- container -->

