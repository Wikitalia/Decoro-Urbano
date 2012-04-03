<?php

/*
 * ----------------------------------------------------------------------------
 * Decoro Urbano version 0.4.0
 * ----------------------------------------------------------------------------
 * Copyright Maiora Labs Srl (c) 2012
 * ----------------------------------------------------------------------------   
 * 
 * This file is part of Decoro Urbano.
 * 
 * Decoro Urbano is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * Decoro Urbano is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with Decoro Urbano.  If not, see <http://www.gnu.org/licenses/>.
 */

?>
<!--============================Footer============================-->
<div id="footer">
	<div class="wrapper">
	<?= date('Y') ?> - <?= $settings['admin_comuni']['proprietario'] ?> <!-- - powered by <a href="<?= $settings['dev']['url'] ?>"><?= $settings['dev']['nome'] ?></a> -->

<br><img src="images/logo_maiora_panel.png">

  </div>
</div>
<!--End Footer-->

<!--============================Modal============================-->
<div id="facebox"> 
	<div> 
		<h2>Fiestra Debug</h2> 
		<p><? if(isset($debug)) echo $debug ?></p> 
		<hr />
		<p class="tar"><button class="close button"> Chiudi </button></p> <!-- Close class on any element inside the modal box will close the modal. -->
	</div> 
</div>
<!--End Modal-->