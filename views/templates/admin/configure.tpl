/**
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
*
*  @author    Mantas Antanaitis <antanaitis.web@gmail.com>
*  @copyright 2021 Webscript
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

<div class="panel">
	<h3><i class="icon icon-search"></i> {l s='Preview' mod='topbanner'}</h3>
	<p>

	<nav class="banner" style="height: {$height|escape:'htmlall':'UTF-8'}px; color: {$text_color|escape:'htmlall':'UTF-8'}; background: {$bg_color|escape:'htmlall':'UTF-8'}; display: flex;">
  <div class="banner-content" style="display: flex; width: fit-content; margin: 0 auto;height: {$height|escape:'htmlall':'UTF-8'}px; line-height: {$height|escape:'htmlall':'UTF-8'}px; font-size: {$font_size|escape:'htmlall':'UTF-8'}px; font-weight: {$font_weight|escape:'htmlall':'UTF-8'};">
  {if $timer && $timer_date}<span id="timer_date" style="display: none;">{$timer_date}</span>
     {if timer_text}<b style="height: {$height|escape:'htmlall':'UTF-8'}px; line-height: {$height|escape:'htmlall':'UTF-8'}px; font-size: {$font_size|escape:'htmlall':'UTF-8'}px;">{array_shift(explode("%timer%", $timer_text))|escape:'htmlall':'UTF-8'}</b>{/if}
     &nbsp;<b id="timer" style="height: {$height|escape:'htmlall':'UTF-8'}px; line-height: {$height|escape:'htmlall':'UTF-8'}px; font-size: {$font_size|escape:'htmlall':'UTF-8'}px;"></b>&nbsp;
     {if timer_text}<b style="height: {$height|escape:'htmlall':'UTF-8'}px; line-height: {$height|escape:'htmlall':'UTF-8'}px; font-size: {$font_size|escape:'htmlall':'UTF-8'}px;">{if isset(end(explode("%timer%", $timer_text)))}{end(explode("%timer%", $timer_text))|escape:'htmlall':'UTF-8'}{/if}</b>{/if}
     {/if}&nbsp;{$text}</span>
     </div>
</nav>
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
	</p>
</div>
