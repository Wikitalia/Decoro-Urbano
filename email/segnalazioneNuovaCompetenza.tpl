{config_load file="testi_email.conf" section="segnalazioneModerazioneOK"}

{include file="_header.tpl"}

E' stata approvata una nuova segnalazione inserita da {$nome_utente} di competenza {$nome_competenza}.<br /><br />
E' possibile consultare la segnalazione al seguente indirizzo:<br />
<a href="{$link_segnalazione}">{$link_segnalazione}</a>

<div class="divider"></div>

<div class="fSSmall">{$via}</div>
<div>{$messaggio}</div>
<div class="fSSmall">{$categoria}</div>
<div class="fSSmall">{$data}</div>
<img src="{$imgSegnalazione}" alt="Abilita la visualizzazione delle immagini per vedere questa segnalazione" />
<img src="{$imgMappa}" alt="Abilita la visualizzazione delle immagini per vedere questa segnalazione" />


<div class="divider"></div>
{include file="_footer.tpl"}
