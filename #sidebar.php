			<div id="sidebar">

				<div id="sidemenu_1">
					
					<!--link ginterni-->
					<img src="image/menu/title.png" />
					<div class="voicesidemenu"><img src="image/menu/ico_home.png" style="margin-bottom:-2px;" />		<a href="index.php">			&nbsp;HOMEPAGE		</a></div>
					<div class="voicesidemenu"><img src="image/menu/ico_dovesiamo.png" style="margin-bottom:-2px;" />	<a href="dovesiamo.php">			&nbsp;DOVE SIAMO	</a></div>
					<div class="voicesidemenu"><img src="image/menu/ico_contatti.png" style="margin-bottom:-2px;" />	<a href="contatti.php">			&nbsp;CONTATTACI	</a></div>
					<div class="voicesidemenu"><img src="image/menu/ico_prodotti.png" style="margin-bottom:-2px;" />	<a href="prodotti.gazebi.php">	&nbsp;I PRODOTTI	</a></div>
					
					<!--menu shop-->
					<div class="voicesidemenu">
						<img src="image/menu/ico_shop.png" style="margin-bottom:-2px;" /><a href="shop.php">&nbsp;SHOP ONLINE</a>
					</div>
					<!--
                                        <ul class="menushop">
						<li>Coffee</li>
						<li>Tea
							<ul>
							<li>Black tea</li>
							<li>Green tea</li>
							</ul>
						</li>
						<li>Milk</li>
					</ul> -->
                                        <div class="menushop">
													 <?php include 'menu_categorie.php'; ?>
													 <?php include 'mini_login.php'; ?>
                                        <?php include 'mini_cart.php'; ?>
                                        <?php include 'mini_search.php'; ?>
													 <div style="clear:both;"></div>
													 </div>
                                        
					<img src="image/stopmenu.jpg" style="margin-left:12px;" />
					<br /><br />
				
				</div><!--/sidemenu-->

			</div>