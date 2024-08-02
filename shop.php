<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

  <head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>MEV snc </title>
	<link rel="stylesheet" type="text/css" href="style.css" media="screen" />

	<!--=PLUGIN=-->
	<!---jquery--->
	<script type="text/javascript" src="plugin/jquery/jquery.js"></script>
	<!---slidefade--->
	<link rel="stylesheet" type="text/css" href="plugin/tinyfader/style_tyfader_slider.css" />
	<script type="text/javascript" src="plugin/tinyfader/tinyfader.js"></script>

  </head>
  <body>

	<!---header--->
	<?php include("#header.php");?>

   <div id="topcontent" style="margin-top:-70px;"></div>
	<div id="content"><br />
		<div id="wrap">

			<div id="page">

                                <img src="image/headpage_shop.png" />
										  <div style="padding:20px 20px 20px 20px;">
											<?php 
                                    $pagina = 'home';
                                    if(!empty($_GET['l'])){
                                        $pagina = $_GET['l'];
                                    }

                                    if (!isset ($pagina) || !file_exists($pagina . '.php')) {
                                        $pagina = 'home';
                                    }
                                    else {
                                        include $pagina . '.php';
                                    }
                                ?>
										  </div>

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
