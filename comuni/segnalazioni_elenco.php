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

/*
 * Elenco delle segnalazioni
 */

ini_set("session.cookie_domain", ".".$settings['sito']['dominio']);
session_start();

require_once('../include/config.php');

if ($settings['sito']['debug']) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

require_once('../include/db_open.php');
require_once('../include/db_open_funzioni.php');
require_once('../include/funzioni.php');
require_once('../include/controlli.php');
require_once('../include/decorourbano.php');

require_once('auth.php');


$id_menu_principale = "20";
$id_menu_secondario = "21";

if (isset($_GET["p"])) {
    $p = $_GET["p"];
} else {
    $p = 1;
}

$comune = data_get('tab_comuni', array('id_comune' => $user['id_comune']));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="robots" content="noindex,nofollow" />
        <title><?= $settings['admin_comuni']['nome_sito'] ?></title>          

<? require_once($settings['admin_comuni']['percorso'] . 'head_tag.php') ?>			 

    </head>
    <body>

        <!--==================== Inizio Header =======================-->
        <div id="header_bg">

            <div class="wrapper">

                <? require_once($settings['admin_comuni']['percorso'] . 'header_bar.php') ?>	
                <? require_once($settings['admin_comuni']['percorso'] . 'header_area.php') ?>
                <? require_once($settings['admin_comuni']['percorso'] . 'header_menu.php') ?>      

            </div>

        </div>
        <!--==================== Fine Header =======================-->



        <!--============================ Template Content Background ============================-->
        <div id="content_bg" class="clearfix">
            <!--============================ Main Content Area ============================-->
            <div class="content wrapper clearfix">
                <!--============================Sidebar============================-->
                <div class="sidebar">
                    <!-- inizio barra laterale -->

                    <!--=========History Box=========-->
                    <div class="small_box">
                        <div class="header">
                            Guida
                        </div>

                        <div class="body">
                            <ul class="bulleted_list">
                                <li>Per <b>visualizzare e modificare</b> una segnalazione cliccare sull'icona &nbsp;<img style="border:0;margin-right:4px;margin-bottom:-4px;" src="images/icona_mini_modifica.png" /> dalla lista a sinistra</li>
                            </ul>
                        </div>


                    </div>
                    <!--End History Box-->		  


                    <!-- fine barra laterale -->
                </div>
                <!--End sidebar-->
                <!--============================Main Column============================-->
                <div class="main_column">
                    <!-- inizio contenuto principale -->






                    <!--=========Tables Box=========-->
                    <div class="box">
                        <div class="header" >

                            <img src="images/icona_pagina_grande.png" style="vertical-align:text-bottom;" />Mappa segnalazioni
                        </div>
                        <div class="body">
                            <div class="panes">
                                <!-- Any div under the class of "panes" will associate itself in the same order as the tabs defined above under "sub_nav"-->
                                <!-- Pane 1 -->
                                <div class="clearfix">
                                    <table cellpadding="0" cellspacing="0" border="0" class="display" id="data_table">
                                        <thead>
                                            <tr style="background-color:#F2F2F2;">
                                                <th>Messaggio</th>
                                                <th>Posizione</th>
                                                <th>Data</th>
                                                <th>Stato</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tfoot style="display:none;">
                                            <tr>
                                                <th>Messaggio</th>
                                                <th>Posizione</th>
                                                <th>Data</th>
                                                <th>Stato</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                        <tbody>


<?

// recupera la lista delle segnalazioni
$parametri['id_comune'] = $user['id_comune'];
$parametri['id_competenza'] = 0;
$parametri['genere'] = 'degrado';

$parametri = cleanArray($parametri);

$segnalazioni = segnalazioni_get($parametri);

if (count($segnalazioni))
    foreach ($segnalazioni as $row) {
        $id_segnalazione = $row["id_segnalazione"];

        $messaggio = $row["messaggio"];
        $data = converti_timestamp_human($row["data"]);

        $indirizzo = $row["citta"] . " " . $row["indirizzo"] . " " . $row["civico"];

        if ($row["stato"] < $settings['segnalazioni']['in_carico']) {
            $stato = 'In attesa';
        } elseif ($row["stato"] >= $settings['segnalazioni']['in_carico'] && $row["stato"] < $settings['segnalazioni']['risolta']) {
            $stato = 'In carico';
        } elseif ($row["stato"] >= $settings['segnalazioni']['risolta']) {
            $stato = 'Risolta';
        }
        ?>
                                                    <tr class="gradeA"><td><?= left($messaggio, 30) ?></td><td><?= $indirizzo ?></td><td><?= $data ?></td><td><?= $stato ?></td><td valign="bottom" style="vertical-align:bottom;" nowrap><a href="segnalazione_gestione.php?id=<?= $id_segnalazione ?>"><img src="images/icona_mini_modifica.png" style="border:0;margin-right:4px;margin-bottom:-4px;" /></a></td></tr>
                                                    <?
                                                }
                                            ?>

                                        </tbody>
                                    </table>
                                </div>


                            </div>
                        </div>
                    </div>
                    <!--End Tables Box-->

                    <!-- fine contenuto principale -->
                </div>
                <!--End Main Column-->
            </div>
            <!--End main content area-->
        </div>
        <!--End Template Content bacground-->




<? require_once($settings['admin_comuni']['percorso'] . 'footer.php') ?>



    </body>
    <!--End Body-->
</html>
