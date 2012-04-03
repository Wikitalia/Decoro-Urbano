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
<!--=========Header Area including search field and logo=========-->
		<div class="header_main clearfix">
                  
<!--===Search field===-->
		  <div class="header_search" style="display:none;">
			<a href="#"><img src="images/search_icon.png" alt="Search" width="21" height="20" class="search_icon" /></a>
			<input name="textfield" type="text"  id="textfield" class="search_field" />
		  </div>
                  
<!--===Logo===-->
			<a id="logo" href="segnalazioni_elenco.php"><?= $settings['admin_comuni']['nome_sito'] ?></a>
			
			<!--<a id="logo_citta" href="segnalazioni_elenco.php" style="float:right;"></a>-->
			<a href="segnalazioni_elenco.php" style="float:right;font-size:24px;margin-top:24px;">Comune di <?=$comune[0]['nome']?></a>
			
		</div>
		<!--End Search field and logo Header Area-->