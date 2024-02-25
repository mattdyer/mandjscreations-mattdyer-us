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
	<div>
		<textarea id="Content" name="Content">
			<?php
				/*echo $article->get('Content');*/
			?>
		</textarea>
	</div>
	<h3>Changes From Previous Version</h3>
	<ul>
		<li>This is the first version but I will list some of the working and partly working features.</li>
	</ul>
<?php
	$BodyContent = ob_get_contents();
	ob_end_clean();
 ?>

<?php
	include(SiteRoot . "/common/template.php");
?>
