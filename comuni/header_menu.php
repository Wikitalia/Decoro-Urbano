<?php

/*
 * ----------------------------------------------------------------------------
 * Decoro Urbano version 0.2.1
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

			<!--li><a href="menu_gestione.php" <? if ($id_menu_principale == "30") echo "class='current'" ?>>Menu</a>
				<ul>
					<li><a href="menu_gestione.php" <? if ($id_menu_secondario == "31") echo "class='current'" ?>>Gerarchie & contenuti</a></li>
				</ul>
			</li>

			<li><a href="localita_gestione.php" <? if ($id_menu_principale == "40") echo "class='current'" ?>>Località</a>
				<ul>
				  <li><a href="localita_gestione.php" <? if ($id_menu_secondario == "41") echo "class='current'" ?>>Elenco Località</a></li>
					<li><a href="localita_gestione_modifica.php" <? if ($id_menu_secondario == "42") echo "class='current'" ?>>Nuova Località</a></li>
        </ul>
			</li>

			<li><a href="realizzazioni_gestione.php" <? if ($id_menu_principale == "50") echo "class='current'" ?>>Realizzazioni</a>
				<ul>
				  <li><a href="realizzazioni_gestione.php" <? if ($id_menu_secondario == "51") echo "class='current'" ?>>Elenco Realizzazioni</a></li>
					<li><a href="realizzazioni_gestione_modifica.php" <? if ($id_menu_secondario == "52") echo "class='current'" ?>>Nuova Realizzazione</a></li>
        </ul>
			</li>

			<li><a href="ftp_utenti_gestione.php" <? if ($id_menu_principale == "60") echo "class='current'" ?>>Utenti FTP</a>
				<ul>
				  <li><a href="ftp_utenti_gestione.php" <? if ($id_menu_secondario == "61") echo "class='current'" ?>>Elenco Utenti</a></li>
					<li><a href="ftp_utenti_gestione_modifica.php" <? if ($id_menu_secondario == "62") echo "class='current'" ?>>Nuovo utente</a></li>
        </ul>
			</li-->

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