<?php
	include("/var/www/html/modules/AppInit.php");
	
	ob_start();
?>
	<script type="text/ecmascript">
		$(function(){
			DisplayValue();
			$('#SaveButton').click(function(){
				var SavedValue = $('#SomethingToSave').val();
				localStorage.setItem("MySavedText",SavedValue);
				$('#SomethingToSave').val('');
				DisplayValue();
			});
		});
		function DisplayValue(){
			//alert(localStorage.getItem("MySavedText"));
			$('#SavedText').html(localStorage.getItem("MySavedText"));
		}
	</script>
	<p>Simple HTML 5 localStorage Example. Type something in the box and click Save. This string will be saved and redisplayed for you next time you visit the site.</p>
	<div id="SavedText"></div>
	<div><input type="text" name="SomethingToSave" id="SomethingToSave" /> <input type="button" id="SaveButton" value="Save" /></div>
<?php
	$BodyContent = ob_get_contents();
	ob_end_clean();
 ?>

<?php
	include(SiteRoot . "/common/template.php");
?>