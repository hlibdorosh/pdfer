<?php
return [
    'login_title' => 'Prihlásenie',
    'email' => 'Email',
    'password' => 'Heslo',
    'login_button' => 'Prihlásiť sa',
    'no_account' => 'Nemáte účet? Zaregistrujte sa',
    'invalid_login' => 'Nesprávny email alebo heslo!',
    'register_title' => 'Registrácia',
    'register_button' => 'Zaregistrovať sa',
    'success_register' => 'Registrácia bola úspešná! Prihláste sa',
    'already_have_account' => 'Už máte účet? Prihláste sa',
    'welcome' => 'Vitaj',
    'logout' => 'Odhlásiť sa',

    'pdf_tools' => 'PDF nástroje',
    'merge_pdf_files' => 'Zlúčiť PDF súbory',
    'select_files_to_merge' => 'Vyberte PDF súbory na zlúčenie:',
    'merge' => 'Zlúčiť',
    'split_pdf' => 'Rozdeliť PDF',
    'choose_pdf_file' => 'Vyberte PDF súbor:',
    'pages_to_extract' => 'Strany na extrahovanie (napr. 1-2,4,6):',
    'split' => 'Rozdeliť',
    'compress_pdf' => 'Komprimovať PDF',
    'choose_pdf_to_compress' => 'Vyberte PDF na kompresiu:',
    'compress' => 'Komprimovať',
    'coming_soon' => 'Už čoskoro',
    'merge_pdf' => 'Zlúčiť PDF',

    'error_register' => 'Registrácia zlyhala. Skúste znova.',

    'view_pdf_info' => 'Zobraziť informácie o PDF',
    'pdf_metadata_viewer' => 'Prehliadač PDF metadát',
    'select_pdf_file' => 'Vybrať PDF súbor',
    'view_info' => 'Zobraziť informácie',

    'history' => 'História',
    'manual' => 'Príručka',

    'title' => 'Admin História',
    'back' => 'Späť',
    'action' => 'Akcia',
    'user' => 'Používateľ',
    'ip' => 'IP adresa',
    'timestamp' => 'Čas',
    'delete' => 'Vymazať',
    'no_history' => 'Žiadna história zatiaľ nie je.',
    'confirm_delete' => 'Naozaj chceš vymazať túto položku?',



    //for manual
    'user_manual_html' => <<<HTML
<h2>Používateľská príručka</h2>
<p>Aplikácia umožňuje používateľovi upravovať PDF súbory priamo cez webové rozhranie (frontend) alebo pomocou API požiadaviek.</p>

<h3>Frontend (grafické rozhranie)</h3>
<p>Po prihlásení sa používateľ dostane na hlavnú stránku s prehľadom dostupných nástrojov:</p>
<ul>
  <li><strong>Merge PDF</strong> – zlúči viacero PDF do jedného</li>
  <li><strong>Split PDF</strong> – rozdelí PDF na jednotlivé strany</li>
  <li><strong>Compress PDF</strong> – zmenší veľkosť PDF</li>
  <li><strong>PDF Metadata</strong> – zobrazí technické údeje o súbore (počet strán, autor atĎ.)</li>
</ul>

<p>Každý nástroj sa používa pomocou modálneho okna – kliknutím na tlačidlo sa otvorí dialóg, kde je možné nahrať súbor a zvoliť akciu.</p>

<h3>API rozhranie</h3>
<p>Aplikácia má vytvorené jednoduché API pre jednotlivé funkcie. Požiadavky sa posielajú cez <code>POST</code> na konkrétne koncové body (endpointy), ako napríklad:</p>
<ul>
  <li><code>api/merge.php</code> – zlúčenie PDF</li>
  <li><code>api/split.php</code> – rozdelenie PDF</li>
  <li><code>api/compress.php</code> – komprimácia</li>
  <li><code>api/pdfinfo.php</code> – zobrazenie informácií</li>
</ul>

<p>API vyžaduje zaslanie <code>multipart/form-data</code> formulárov s nahratým súborom <code>file</code> a podľa potreby aj s ďalšími parametrami (napr. <code>pages</code> pre split/remove).</p>

HTML



];
