<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/modules/AppInit.php");
	
	$ArticleID = 44;
	$article = LoadClass(SiteRoot . '/modules/classes/articles/Article');
	
	$article->load($ArticleID);
	
	
	ob_start();
?>
	<script type="text/javascript" src="/common/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="/common/jquery-ui-1.8.4.custom.min.js"></script>
	<link rel="stylesheet" type="text/css" href="jquery-ui-1.8.4.custom.css"/>
	<script type="text/javascript" src="common/htmledit.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			myeditor = new htmledit({
				'Editor':'#Content'
			});
		});
	</script>
	<style type="text/css">
		#Content{
			height:300px;
			width:600px;
		}
	</style>
	<h1>HTMLEdit</h1>
	<h2>Javascript HTML Editor</h2>
	<h3>Example</h3>
	<p>Version Alpha 2: This is the newest working version. In this version I will work out the details of preparing the html for saving.</p>
	<div>
		<textarea id="Content" name="Content">
			<div style="height:150px; width:150px;">text</div>
		</textarea>
	</div>
	<h3>Changes From Previous Version</h3>
	<ul>
		<li>The combine button now works for most simple cases.</li>
	</ul>
	<h3>Previous Versions</h3>
	<div>
		<a href="/projects/htmledit/alpha1/index.php">Alpha 1</a>
	</div>
<?php
	$BodyContent = ob_get_contents();
	ob_end_clean();
 ?>

<?php
	include(SiteRoot . "/common/template.php");
?>
