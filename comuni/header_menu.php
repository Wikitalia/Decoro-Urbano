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
<!--=========Main Navigation=========-->
		<ul id="main_nav">

			<li><a href="segnalazioni_elenco.php" <? if ($id_menu_principale == "20") echo "class='current'" ?>>Segnalazioni</a>
				<ul>
					<li><a href="segnalazioni_elenco.php" <? if ($id_menu_secondario == "21") echo "class='current'" ?>>Elenco segnalazioni</a></li>
					<li><a href="segnalazioni_mappa.php" <? if ($id_menu_secondario == "22") echo "class='current'" ?>>Mappa segnalazioni</a></li>
					<? if ($id_menu_secondario == "23") {?><li><a href="#" class="current">Gestione segnalazione</a></li><?}?>
        </ul>
			</li>

			<li><a href="enti_gestione.php" <? if ($id_menu_principale == "30") echo "class='current'" ?>>Enti/Uffici</a>
				<ul>
					<li><a href="enti_gestione.php" <? if ($id_menu_secondario == "31") echo "class='current'" ?>>Gestione Enti/Uffici</a></li>
				</ul>
			</li>

		</ul>
		<!--End Main Navigation-->

<!--=========Jump Menu=========-->
        <div class="jump_menu" style="display:none;">
            <a href="#" class="jump_menu_btn">Vai a</a>
            <ul class="jump_menu_list">
                <li><a href="#"><img src="images/users2_icon.png" alt="" width="24" height="24" />Utenti</a></li>
                <li><a href="#"><img src="images/tools_icon.png" alt="" width="24" height="24" />Impostazioni</a></li>
                <li><a href="#"><img src="images/messages_icon.png" alt="" width="24" height="24" />Email</a></li>
                <li><a href="#"><img src="images/key_icon.png" alt="" width="24" height="24" />Credenziali</a></li>
                <li><a href="#"><img src="images/documents_icon.png" alt="" width="24" height="24" />Docs</a></li>
            </ul>
        </div>
		<!--End Jump Menu-->