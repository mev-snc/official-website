<script type="text/javascript" src="common/jquery-1.6.2.min.js"></script>

<h2 style="margin-left:-20px;">Cerca prodotti</h2> 
<div style="border-top:#000 1px dotted;border-bottom:#000 2px dotted; padding-bottom:5px;">
<form id="frm_search" method="get" action="shop.php">
  Cerca:
  <input type="hidden" name="l" value="cerca_prodotti"/>
  <input style="background:#999; width:100%;" name="cerca" type="text" id="cerca" size="10" maxlength="100" />
  <input style="background:#999; width:100%; margin-top:4px;" type="submit" name="btn_cerca" id="btn_cerca" value="Cerca" onclick="javascript: if($('#cerca').val()=='') return false;"/>
</form>
</div>