<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

  <head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>MEV snc </title>
	<link rel="stylesheet" type="text/css" href="style.css" media="screen" />

	<!--=PLUGIN=-->
	<!---jquery--->
	<script type="text/javascript" src="plugin/jquery/jquery-1.7.2.min.js"></script>
	<!---lightbox--->
	<link rel="stylesheet" type="text/css" href="plugin/lightbox/css/lightbox.css" media="screen" />
	<script type="text/javascript" src="plugin/lightbox/js/lightbox.js"></script>
	<script type="text/javascript">
		$(function() {
			//$('a[@rel*=lightbox]').lightBox({fixedNavigation:true}); 		// Select all links that contains lightbox in the attribute rel
			$('#lightbox').lightBox({fixedNavigation:true});		// Select all links in object with gallery ID
			//$('a.lightbox').lightBox({fixedNavigation:true}); 			// Select all links with lightbox class
			//$('a').lightBox({fixedNavigation:true}); 					// Select all links in the page
		});
	</script>

  </head>
  <body>

	<!---header--->
	<?php include("#header.php");?>

	<div id="topcontent" style="margin-top:-70px;"></div>
	<div id="content"><br />
		<div id="wrap">

			<div id="page">

				<img src="image/headpage_prodotti.png" />
				<h1>MEV snc</h1>
				<h1>FOTO PRODOTTI | ALTRE LAVORAZIONI</h1>
				<div class="menugallery">
					<a href="prodotti.gazebi.php">&raquo; Gazebi</a><a href="prodotti.coimbendazionietetti.php">&raquo; Coibentazioni e tetti</a><a href="prodotti.grondaie.php">&raquo; Grondaie</a><a href="prodotti.tettoie.php">&raquo; Tettoie</a><a href="prodotti.altrelavorazioni.php">&raquo; Altre lavorazioni</a>
				</div>
				<p>ALTRE LAVORAZIONI...<br />Spazio dedicato ad una descrizione generica</p>
				<div id="img_conteiner">
					<a rel="lightbox[roadtrip]" href="image/gallery/altrelavorazioni/1.jpg"><img src="image/gallery/altrelavorazioni/mini/1.jpg" /></a>
					<a rel="lightbox[roadtrip]" href="image/gallery/altrelavorazioni/2.jpg"><img src="image/gallery/altrelavorazioni/mini/2.jpg" /></a>
					<a rel="lightbox[roadtrip]" href="image/gallery/altrelavorazioni/3.jpg"><img src="image/gallery/altrelavorazioni/mini/3.jpg" /></a>
					<a rel="lightbox[roadtrip]" href="image/gallery/altrelavorazioni/4.jpg"><img src="image/gallery/altrelavorazioni/mini/4.jpg" /></a>

					<a rel="lightbox[roadtrip]" href="image/gallery/altrelavorazioni/5.jpg"><img src="image/gallery/altrelavorazioni/mini/5.jpg" /></a>
					<a rel="lightbox[roadtrip]" href="image/gallery/altrelavorazioni/6.jpg"><img src="image/gallery/altrelavorazioni/mini/6.jpg" /></a>
					<a rel="lightbox[roadtrip]" href="image/gallery/altrelavorazioni/7.jpg"><img src="image/gallery/altrelavorazioni/mini/7.jpg" /></a>
					<a rel="lightbox[roadtrip]" href="image/gallery/altrelavorazioni/8.jpg"><img src="image/gallery/altrelavorazioni/mini/8.jpg" /></a>

					<a rel="lightbox[roadtrip]" href="image/gallery/altrelavorazioni/9.jpg"><img src="image/gallery/altrelavorazioni/mini/9.jpg" /></a>
					<a rel="lightbox[roadtrip]" href="image/gallery/altrelavorazioni/10.jpg"><img src="image/gallery/altrelavorazioni/mini/10.jpg" /></a>
					<a rel="lightbox[roadtrip]" href="image/gallery/altrelavorazioni/11.jpg"><img src="image/gallery/altrelavorazioni/mini/11.jpg" /></a>
					<a rel="lightbox[roadtrip]" href="image/gallery/altrelavorazioni/12.jpg"><img src="image/gallery/altrelavorazioni/mini/12.jpg" /></a>

					<a rel="lightbox[roadtrip]" href="image/gallery/altrelavorazioni/13.jpg"><img src="image/gallery/altrelavorazioni/mini/13.jpg" /></a>
					<a rel="lightbox[roadtrip]" href="image/gallery/altrelavorazioni/14.jpg"><img src="image/gallery/altrelavorazioni/mini/14.jpg" /></a>
					<a rel="lightbox[roadtrip]" href="image/gallery/altrelavorazioni/15.jpg"><img src="image/gallery/altrelavorazioni/mini/15.jpg" /></a>
					<a rel="lightbox[roadtrip]" href="image/gallery/altrelavorazioni/16.jpg"><img src="image/gallery/altrelavorazioni/mini/16.jpg" /></a>
				</div>
				<br /><br />

			</div>

			<!---sidebar--->
			<?php include("#sidebar.php");?>

			<div style="clear:both;"></div>

		</div>
	</div>	

	<!---footer--->
	<?php include("#footer.php");?>

  </body>

</html>
