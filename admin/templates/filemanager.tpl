{block name=body}
<!-- elFinder CSS (REQUIRED) -->
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/fileManager/css/elfinder.min.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/fileManager/css/theme.css">

<!-- elFinder JS (REQUIRED) -->
<script type="text/javascript" src="/admin/includes/fileManager/js/elfinder.full.js"></script>

<!-- elFinder translation (OPTIONAL) -->
<script type="text/javascript" src="/admin/includes/fileManager/js/i18n/elfinder.ru.js"></script>

<!-- elFinder initialization (REQUIRED) -->
<script type="text/javascript" charset="utf-8">
	$().ready(function() {
		var elf = $('#elfinder').elfinder({
			url : '/admin/includes/fileManager/php/connector.php'  // connector URL (REQUIRED)
			// lang: 'ru',             // language (OPTIONAL)
		}).elfinder('instance');
	});
</script>

<!-- Element where elFinder will be created (REQUIRED) -->
<div id="elfinder"></div>
{/block}
