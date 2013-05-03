<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>elFinder 2.0</title>

		<!-- jQuery and jQuery UI (REQUIRED) -->
		<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
		<script src="http://code.jquery.com/jquery-migrate-1.1.1.min.js"></script>
		<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.min.js"></script>
		<link rel="stylesheet" type="text/css" href="/includes/css/jqui.css" />
		
		<!-- TinyMCE (REQUIRED) -->
		<script src="/admin/includes/js/tiny_mce/jquery.tinymce.js"></script>
		<script src="/admin/includes/js/tiny_mce/tiny_mce_popup.js"></script>

		<!-- elFinder CSS (REQUIRED) -->
		<link rel="stylesheet" type="text/css" href="css/elfinder.min.css">
		<link rel="stylesheet" type="text/css" href="css/theme.css">

		<!-- elFinder JS (REQUIRED) -->
		<script src="/admin/includes/fileManager/js/elfinder.min.js"></script>

   		<!-- elFinder initialization (REQUIRED) -->
        <script type="text/javascript">
            var FileBrowserDialogue = {
                init : function () {
                    // Here goes your code for setting your custom things onLoad.
                },
                mySubmit : function (URL) {
                    
                    var win = tinyMCEPopup.getWindowArg("window");

                    // insert information now
                    win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = URL;

                    // are we an image browser
                    if (typeof(win.ImageDialog) != "undefined") {
                        // we are, so update image dimensions...
                        if (win.ImageDialog.getImageData)
                            win.ImageDialog.getImageData();

                        // ... and preview if necessary
                        if (win.ImageDialog.showPreviewImage)
                            win.ImageDialog.showPreviewImage(URL);
                    }

                    // close popup window
                    tinyMCEPopup.close();
                }
            }

            tinyMCEPopup.onInit.add(FileBrowserDialogue.init, FileBrowserDialogue);
            $().ready(function() {
                var elf = $('#elfinder').elfinder({
                    // lang: 'ru',             // language (OPTIONAL)
                    url : 'php/connector.php',  // connector URL (REQUIRED)
                    //url : 'php/connector.php?type=<?php echo $_GET['type']; ?>',  // connector URL (REQUIRED)
                    getfile : {
                        onlyURL : true,
                        multiple : false,
                        folders : false
                    },
                    getFileCallback : function(url) {
                        path = url;
                        FileBrowserDialogue.mySubmit(path);
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
