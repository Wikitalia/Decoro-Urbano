{config_load file="testi_email.conf" section="segnalazioneModerazioneOK"}

{include file="_header.tpl"}

<h1 class="fRed fBig">Ciao <span class="fItalic">{$nome_utente}</span>,<br /><br /></h1>
Grazie per aver utilizzato <strong>Decoro Urbano</strong>, la tua segnalazione è stata approvata ed è ora consultabile a questo indirizzo:<br /><br />
<a href="{$link_segnalazione}">{$link_segnalazione}</a><br /><br />

Ti informiamo inoltre che la segnalazione è stata inoltrata per competenza a {$nome_competenza}.

<div class="divider"></div>

<div class="fSSmall">{$via}</div>
<div>{$messaggio}</div>
<div class="fSSmall">{$categoria}</div>
<div class="fSSmall">{$data}</div>
<img src="{$imgSegnalazione}" alt="Abilita la visualizzazione delle immagini per vedere questa segnalazione" />
<img src="{$imgMappa}" alt="Abilita la visualizzazione delle immagini per vedere questa segnalazione" />

<div class="divider"></div>
Utilizza gli strumenti di share per diffondere la tua segnalazione, la cittadinanza attiva inizia da te!<br /><br />

<div style="text-align:center;margin:10px 0;"><img src="{$settings.sito.url}/email/images/cellSplash.jpg" /></div>

<div>Utilizza Decoro Urbano al top! Scopri le <a href="{$settings.sito.applicazioni}">applicazioni smartphone</a> gratuite per inviare segnalazioni direttamente dal cellulare!</div>


<div class="divider"></div>
{include file="_footer.tpl"}
