{config_load file="testi_email.conf" section="segnalazioneIncarico"}
{include file="_header.tpl"}

<h1 class="fRed fBig">Ciao <span class="fItalic">{$nome_utente}</span>!<br /><br /></h1>
Ti informiamo che una tua segnalazione effettuata in data {$data} in {$via} è stata inoltrata per competenza a {$nome_competenza}.
Puoi consultarne i dettagli al seguente indirizzo:<br />
<a href="{$link_segnalazione}">{$link_segnalazione}</a>

{include file="_footer.tpl"}
