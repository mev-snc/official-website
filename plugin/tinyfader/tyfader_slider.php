    <div id="tinyfader_wrapper">

			<div id="control">
                <div class="sliderbutton"><img src="plugin/tinyfader/images/left.png"  alt="Previous" onclick="slideshow.move(-1)" class="prev_button" /><img src="plugin/tinyfader/images/right.png"  alt="Next" onclick="slideshow.move(1)" class="next_button" /></div>
    
                <div class="wrap_pagination">
                    <ul id="pagination" class="pagination">
                        <li onclick="slideshow.pos(0)">&nbsp;</li>
                        <li onclick="slideshow.pos(1)">&nbsp;</li>
                        <li onclick="slideshow.pos(2)">&nbsp;</li>
                    </ul>
                </div>

            </div>

            <div id="slideshow">
                <ul id="slides">
                    <!--<li id="content"><h1>TinyFader - Simple JavaScript Slideshow</h1></li> esempio -->
                    <li><img src="plugin/tinyfader/photos/test1.jpg" alt="" /></li>
                    <li><img src="plugin/tinyfader/photos/test2.jpg" alt="" /></li>
                    <li><img src="plugin/tinyfader/photos/test3.jpg" alt="" /></li>
                </ul>
            </div>

    </div>
    
    <script type="text/javascript">
		var slideshow=new TINY.fader.fade('slideshow',{
			id:'slides',
			auto:3,
			resume:true,
			navid:'pagination',
			activeclass:'current',
			visible:true,
			position:0
		});
	</script>
