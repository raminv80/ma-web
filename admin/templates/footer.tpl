{block name=footer}
<div class="footer">
	<div class="row">
		<div class="col-sm-offset-8 col-sm-4">
			<p>
				&copy; Copyright {'Y'|date} Made by <a href="http://www.them.com.au" target="_blank" title="Adelaide, South Australia Website Design and Development by Them Advertising">Them Advertising</a>
			</p>
		</div>
	</div>
</div>

<script src="/admin/includes/js/tiny_mce/tinymce.min.js"></script>
<script src="/admin/includes/js/timepicker/jquery.ui.timepicker.js"></script>
<script src="/admin/includes/fileManager/js/elfinder.full.js"></script>

{literal}
 <script type="text/javascript">
    $(document).ready(function(){
      tinyMCEinit('tinymce');
    });

    function tinyMCEinit(CLASSID){
      tinymce.init({
        selector: "textarea."+CLASSID,
        width: 500,
        plugins: [
         "advlist autolink link image lists charmap print preview hr anchor pagebreak linebreak spellchecker",
         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
         "autosave save table contextmenu directionality template paste textcolor"
        ],
        content_css: "/includes/css/styles.css,/admin/includes/css/tinymce.css",
        toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print media | forecolor backcolor",
        file_picker_callback  : elFinderBrowser,
        convert_urls : false,
        indentation  : '5px',
        init_instance_callback : function(editor) {
          $('#'+editor.id).removeClass('tinymce');
        },
        setup: function(editor) {
            editor.on('focus', function(e) {
                tinymce.triggerSave();
            });

            editor.on('blur', function(e) {
                tinymce.triggerSave();
            });
        },
        save_enablewhendirty: true
        });
    }

    function elFinderBrowser (callback, value, meta)  {
            var cmsURL = '/admin/includes/fileManager/elfinder.php';    // script URL - use an absolute path!

            tinyMCE.activeEditor.windowManager.open({
                file : cmsURL,
                title : 'elFinder 2.0',
                width : 900,
                height : 450,
                resizable : "yes",
                inline : "yes",  // This parameter only has an effect if you use the inlinepopups plugin!
                popup_css : false, // Disable TinyMCE's default popup CSS
                close_previous : "no"
            }, {
            oninsert: function (file, elf) {
              var url, reg, info;

            // URL normalization
            url = file.url;
            reg = /\/[^/]+?\/\.\.\//;
            while(url.match(reg)) {
              url = url.replace(reg, '/');
            }

            // Make file info
            info = file.name + ' (' + elf.formatSize(file.size) + ')';

            // Provide file and text for the link dialog
            if (meta.filetype == 'file') {
              callback(url, {text: info, title: info});
            }

            // Provide image and alt text for the image dialog
            if (meta.filetype == 'image') {
              callback(url, {alt: info});
            }

            // Provide alternative source and posted for the media dialog
            if (meta.filetype == 'media') {
              callback(url);
            }
          }
        });
            return false;
        }
  </script>
{/literal}
{/block}
