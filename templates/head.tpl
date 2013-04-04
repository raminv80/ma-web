{*	This template holds the page specific region for the head of a page.

	Variables:
		Company Name = {$company_name}
*}
{block name=head}
		<link rel="stylesheet" href="/includes/js/lightbox/css/prettyPhoto.css" type="text/css" media="screen" charset="utf-8" />
		<script src="/includes/js/lightbox/js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript" charset="utf-8">
		  $(document).ready(function(){
		    $("a[rel^=\'lightbox\']").prettyPhoto();
		  });
		</script>
{/block}		
		
		
