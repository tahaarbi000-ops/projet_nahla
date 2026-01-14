<?php
require_once("../connect.php");
require_once("../vendor/autoload.php");

use Dompdf\Dompdf;

if (!isset($_GET['id'])) {
    die("Contrat invalide");
}

$id = intval($_GET['id']);

$sql = "
SELECT 
    c.id AS contrat_id,
    c.date_cont,
    c.durée_cont,

    uc.nom AS nom_client,
    uc.prenom AS prenom_client,

    up.nom AS nom_prop,
    up.prenom AS prenom_prop,

    l.adresse

FROM contrat c
JOIN users uc ON uc.id = c.client_id
JOIN location l ON l.id = c.location_id
JOIN users up ON up.id = l.prop_id
WHERE c.id = $id
";

$res = mysqli_query($conn, $sql);
if (!$res || mysqli_num_rows($res) === 0) {
    die("Contrat introuvable");
}

$data = mysqli_fetch_assoc($res);

$html = "
<style>
body { font-family: DejaVu Sans; }
h2 { text-align: center; }
p { margin: 6px 0; }
</style>

<h2>Contrat de Location</h2>

<p>
<strong>Modalités d’application du contrat type (décret du 29 mai 2015)</strong>
Le régime de droit commun en matière de baux d’habitation est défini principalement par la loi n° 89-462 du 6 juillet 1989 tendant à améliorer les rapports locatifs et portant modification de la loi n° 86-1290 du 23 décembre 1986.
L’ensemble de ces dispositions étant d’ordre public, elles s’imposent aux parties, qui ne peuvent, en principe, y renoncer.
</p>

<p><strong>Numéro du contrat :</strong> {$data['contrat_id']}</p>
<p><strong>Date :</strong> {$data['date_cont']}</p>
<p><strong>Durée :</strong> {$data['durée_cont']}</p>

<hr>

<p><strong>Client :</strong> {$data['prenom_client']} {$data['nom_client']}</p>
<p><strong>Propriétaire :</strong> {$data['prenom_prop']} {$data['nom_prop']}</p>
<p><strong>adresse :</strong> {$data['adresse']} {$data['nom_prop']}</p>

<br><br>

<p>Signature client : ________________________</p>
<br>
<p>Signature propriétaire : ________________________</p>
";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$dompdf->stream("contrat_{$id}.pdf", ["Attachment" => true]);
exit;
