<?php
require '../vendor/autoload.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();
$dompdf->loadHtml(<<<EOT
<ul style="font-family: Monospace;">
    <li>Uno</li>
    <li>Dos</li>
    <li>Tres</li>
</ul>
EOT);
$dompdf->setPaper('A4');
$dompdf->render();
$dompdf->stream('salida.pdf', ['Attachment' => false]);
