<?php
$manualText = <<<HTML
<h2>Používateľská príručka</h2>

<p>Aplikácia umožňuje používateľovi upravovať PDF súbory priamo cez webové rozhranie (frontend) alebo pomocou API požiadaviek.</p>

<h3>Frontend (grafické rozhranie)</h3>
<p>Po prihlásení sa používateľ dostane na hlavnú stránku s prehľadom dostupných nástrojov:</p>
<ul>
  <li><strong>Merge PDF</strong> – zlúči viacero PDF do jedného</li>
  <li><strong>Split PDF</strong> – rozdelí PDF na jednotlivé strany</li>
  <li><strong>Compress PDF</strong> – zmenší veľkosť PDF</li>
  <li><strong>PDF Metadata</strong> – zobrazí technické údaje o súbore (počet strán, autor atď.)</li>
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


<h3>Príklad použitia API (cURL)</h3>

```bash
curl -X POST -F "file=@subor.pdf" -F "pages=2-3" https://.../project/pdfer/api/split.php


HTML;
?>

<div class="container mt-5">
    <?= $manualText ?>
    <?php if (!isset($isPdf) || !$isPdf): ?>
        <a href="api/manual_pdf.php" class="btn btn-sm btn-primary noprint">📥 Stiahnuť PDF</a>

    <?php endif; ?>


</div>

<style>
    body {
        font-family: "DejaVu Sans", sans-serif;
        line-height: 1.6;
        font-size: 16px;
        color: #222;
        padding: 20px;
    }

    h2, h3 {
        color: #003366;
        margin-top: 1em;
    }

    ul {
        margin-left: 20px;
    }

    .btn-download {
        display: inline-block; /* 🔑 це головне */
        width: auto;            /* не займати 100% */
        padding: 6px 12px;
        font-size: 14px;
        background-color: #007bff;
        color: white;
        border-radius: 4px;
        text-decoration: none;
        border: none;
    }

    .btn-download:hover {
        background-color: #0056b3;
    }

    .noprint {
        display: block;
    }

    /* Сховати елементи в PDF */
    @media print {
        .noprint {
            display: none !important;
        }
    }

    /* Dompdf не підтримує @media print, тому сховаємо вручну */
    .pdf .noprint {
        display: none !important;
    }
</style>

