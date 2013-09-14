<script type="text/javascript">
	$(document).ready(function(){	
		$("#slider").easySlider({
			auto: true,
			continuous: true,
			controlsShow: false,
			speed: 800
		});
	});	
</script>	
<div class="last_default">
    <div class="module_title">{$title}</div>
    <div>
       <div id="slider">
       	<ul class="animation_ul">
       		{foreach from=$content item=i}
    			<li>{$i.title}</li>
			{/foreach}
       	</ul>
       </div>
    </div>
</div>