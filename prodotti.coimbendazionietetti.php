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
				<h1>FOTO PRODOTTI | COIMBENDAZIONI E TETTI</h1>
				<div class="menugallery">
					<a href="prodotti.gazebi.php">&raquo; Gazebi</a><a href="prodotti.coimbendazionietetti.php">&raquo; Coibentazioni e tetti</a><a href="prodotti.grondaie.php">&raquo; Grondaie</a><a href="prodotti.tettoie.php">&raquo; Tettoie</a><a href="prodotti.altrelavorazioni.php">&raquo; Altre lavorazioni</a>
				</div>
				<p>COIMBENDAZIONI E TETTI<br /><img width="845" src="image/gallery/tetti/Coimbendati.jpg" /><br />Spazio dedicato ad una descrizione generica</p>
				<div id="img_conteiner">
					<a rel="lightbox[roadtrip]" href="image/gallery/tetti/1.jpg"><img src="image/gallery/tetti/mini/1.jpg" /></a>
					<a rel="lightbox[roadtrip]" href="image/gallery/tetti/2.jpg"><img src="image/gallery/tetti/mini/2.jpg" /></a>
					<a rel="lightbox[roadtrip]" href="image/gallery/tetti/3.jpg"><img src="image/gallery/tetti/mini/3.jpg" /></a>
					<a rel="lightbox[roadtrip]" href="image/gallery/tetti/4.jpg"><img src="image/gallery/tetti/mini/4.jpg" /></a>

					<a rel="lightbox[roadtrip]" href="image/gallery/tetti/5.jpg"><img src="image/gallery/tetti/mini/5.jpg" /></a>
					<a rel="lightbox[roadtrip]" href="image/gallery/tetti/6.jpg"><img src="image/gallery/tetti/mini/6.jpg" /></a>
					<a rel="lightbox[roadtrip]" href="image/gallery/tetti/7.jpg"><img src="image/gallery/tetti/mini/7.jpg" /></a>
					<a rel="lightbox[roadtrip]" href="image/gallery/tetti/8.jpg"><img src="image/gallery/tetti/mini/8.jpg" /></a>

					<a rel="lightbox[roadtrip]" href="image/gallery/tetti/9.jpg"><img src="image/gallery/tetti/mini/9.jpg" /></a>
					<a rel="lightbox[roadtrip]" href="image/gallery/tetti/10.jpg"><img src="image/gallery/tetti/mini/10.jpg" /></a>
					<a rel="lightbox[roadtrip]" href="image/gallery/tetti/11.jpg"><img src="image/gallery/tetti/mini/11.jpg" /></a>
					<a rel="lightbox[roadtrip]" href="image/gallery/tetti/12.jpg"><img src="image/gallery/tetti/mini/12.jpg" /></a>
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
