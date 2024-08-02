<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

  <head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>MEV snc </title>
	<link rel="stylesheet" type="text/css" href="style.css" media="screen" />

	<!--=PLUGIN=-->
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
	<!---slidefade--->
	<link rel="stylesheet" type="text/css" href="plugin/tinyfader/style_tyfader_slider.css" />
	<script type="text/javascript" src="plugin/tinyfader/tinyfader.js"></script>

  </head>
  <body>

	<!---header--->
	<?php include("#header.php");?>

	<!---slidefade--->
	<?php include("plugin/tinyfader/tyfader_slider.php");?>

	<!---content--->
	<div id="topcontent"></div>
	<div id="content"><br />
		<div id="wrap">

			<div id="page">

				<img src="image/headpage_home.png" />
				<img src="image/mevimage.jpg" style="float:left; margin:23px 15px 10px 20px;" />
				<h1>BENVENUTI ALLA MEV snc</h1>
				<p>Benvenuti alla Mev snc. Siamo l'azienda che realizza prodotti in legno per la tua casa come i gazebi o le tettoie, le fioriere e le panche ed altro come le grondaie in rame, alluminio, preverniciato e altro ancora...<br /><br />Ci trovi a <i>Battipaglia</i> (SA), in <i>Via Como al numero 10B</i>. Vieni a trovarci e scopri di persona tutto quello che qui sul nostro sito puoi trovare, e anche di più!</p>
				<h1>QUALCOSA DI NOI</h1>
				<p>
					Ci rivolgiamo a tutti gli appassionati di bricolage e Do-it-Yourself e alle aziende di costruzione.
					Da oltre XXX anni siamo molto convincenti con i nostri prodotti per le ricostruzioni e ristrutturazioni.
					Qualità, funzionalità e design hanno fatto del nostro marchio un concetto di prodotti di altissima qualità e adeguati ai fabbisogni più svariati per gli appassionati di bricolage. Costanti controlli della merce e dell'attività garantiscono durevolmente la qualità dei cicli di lavorazione e dei prodotti.<br />
					Catturiamo l'attenzione e le tendenze, ampliamo e ottimizziamo costantemente le nostre offerte con i nostri nuovi e innovativi prodotti.<br />
					<i>"IL NOSTRO OBBIETTIVO? CLIENTI SODDISFATTI!"</i><br />
					La soddisfazione dei clienti è al centro della nostra filosofia aziendale. Per questo motivo, la nostra gamma di prodotti soddisfa pienamente le richieste degli appassionati, offrendo le soluzioni più svariate per nuovi progetti di ricostruzione e ristrutturazione. I nostri prodotti sono facili e veloci da montare. Con le nostre fantastiche offerte di servizi garantiamo divertimento e successo ai fai da te.
				</p>
				<br />
				<h2>&nbsp;Alcuni nostri prodotti...</h2>
				<div id="img_conteiner">
					<a rel="lightbox[roadtrip]" href="image/gallery/gazebo/4.jpg"><img src="image/gallery/gazebo/mini/4.jpg" /></a>
					<a rel="lightbox[roadtrip]" href="image/gallery/gazebo/12.jpg"><img src="image/gallery/gazebo/mini/12.jpg" /></a>
					<a rel="lightbox[roadtrip]" href="image/gallery/tetti/5.jpg"><img src="image/gallery/tetti/mini/5.jpg" /></a>
					<a rel="lightbox[roadtrip]" href="image/gallery/tettoie/6.jpg"><img src="image/gallery/tettoie/mini/6.jpg" /></a>
				</div>
				<br />

			</div>

			<!---sidebar--->
			<?php include("#sidebar.php");?>

			<div style="clear:both;"></div>

		</div>
	</div>

	<div style="clear:both;"></div>
	<!---footer--->
	<?php include("#footer.php");?>

  </body>

</html>
