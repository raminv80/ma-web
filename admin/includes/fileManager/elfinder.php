<?php
session_start();
if((!isset($_SESSION['user']['admin']) || empty($_SESSION['user']['admin']))){
  die('Authentication failed!');
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>elFinder 2.0</title>

		<!-- jQuery and jQuery UI (REQUIRED) -->
		<script src="/admin/includes/js/jquery-2.1.4.min.js"></script>
		<script src="/admin/includes/js/jquery-ui.min.js"></script>
		<link rel="stylesheet" type="text/css" href="/admin/includes/css/jquery-ui.css" />

		<!-- TinyMCE (REQUIRED) -->
		<script src="/admin/includes/js/tiny_mce/jquery.tinymce.min.js"></script>

		<!-- elFinder CSS (REQUIRED) -->
		<link rel="stylesheet" type="text/css" href="css/elfinder.min.css">
		<link rel="stylesheet" type="text/css" href="css/theme.css">

		<!-- elFinder JS (REQUIRED) -->
		<script src="/admin/includes/fileManager/js/elfinder.min.js"></script>

   		<!-- elFinder initialization (REQUIRED) -->
        <script type="text/javascript">
        var FileBrowserDialogue = {
        	    init: function() {
        	      // Here goes your code for setting your custom things onLoad.
        	    },
        	    mySubmit: function (file, elf) {
        	      // pass selected file data to TinyMCE
        	      parent.tinymce.activeEditor.windowManager.getParams().oninsert(file, elf);
        	      // close popup window
        	      parent.tinymce.activeEditor.windowManager.close();
        	    }
        	  }

        	  $().ready(function() {
        	    var elf = $('#elfinder').elfinder({
        	      // set your elFinder options here
        	      url: 'php/connector.php',  // connector URL
        	      getFileCallback: function(file) { // editor callback
        	        // Require `commandsOptions.getfile.onlyURL = false` (default)
        	        FileBrowserDialogue.mySubmit(file, elf); // pass selected file path to TinyMCE 
        	      }
        	    }).elfinder('instance');  
        	  });
        </script>
	</head>
	<body>

		<!-- Element where elFinder will be created (REQUIRED) -->
		<div id="elfinder"></div>

	</body>
</html>
