{config_load file="testi_email.conf" section="segnalazioneRisolta"}
{include file="_header.tpl"}

<h1 class="fRed fBig">Ciao <span class="fItalic">{$nome_utente}</span>!<br /><br /></h1>
Ti informiamo che {$nome_competenza} ha comunicato di aver risolto una tua segnalazione effettuata in data {$data} in {$indirizzo}.
Puoi consultarne i dettagli al seguente indirizzo:<br />
<a href="{$link_segnalazione}">{$link_segnalazione}</a>

{include file="_footer.tpl"}
