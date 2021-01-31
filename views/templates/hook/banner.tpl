{if $enabled}
	<div class="banner" style="height: {$height}px; color: {$text_color}; background: {$bg_color}; display: flex;">
	<div class="banner-content" style="height: {$height}px; line-height: {$height}px; font-size: {$font_size}px; font-weight: {$font_weight};">
	   {if $timer && $timer_date}<span id="timer_date" style="display: none;">{$timer_date}</span>
	   {if timer_text}<b style="height: {$height}px; line-height: {$height}px; font-size: {$font_size}px;">{array_shift(explode("%timer%", $timer_text))}</b>{/if}
	   &nbsp;<b id="timer" style="height: {$height}px; line-height: {$height}px; font-size: {$font_size}px;"></b>&nbsp;
	   {if timer_text}<b style="height: {$height}px; line-height: {$height}px; font-size: {$font_size}px;">{if isset(end(explode("%timer%", $timer_text)))}{end(explode("%timer%", $timer_text))}{/if}</b>{/if}
	   {/if}&nbsp;{$text}</span>
	</div>
	<script>
	   var countDownDate = new Date(document.getElementById("timer_date").innerText).getTime();
	   
	   var x = setInterval(function() {
	   
	     // Get today's date and time
	     var now = new Date().getTime();
	   
	     // Find the distance between now and the count down date
	     var distance = countDownDate - now;
	   
	     // Time calculations for days, hours, minutes and seconds
	     var days = Math.floor(distance / (1000 * 60 * 60 * 24));
	     var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
	     var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
	     var seconds = Math.floor((distance % (1000 * 60)) / 1000);
	   
	     // Display the result in the element with id="timer"
	     document.getElementById("timer").innerHTML = days + "d " + hours + "h "
	     + minutes + "m " + seconds + "s ";
	   
	     // If the count down is finished, write some text
	     if (distance < 0) {
	       clearInterval(x);
	       document.getElementById("timer").innerHTML = "EXPIRED";
	     }
	   }, 1000);
	</script>
{/if}