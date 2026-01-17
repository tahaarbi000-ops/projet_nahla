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
    uc.cin AS cin_client,
    uc.prenom AS prenom_client,

    up.nom AS nom_prop,
    up.cin AS cin_prop,
    up.prenom AS prenom_prop,

    l.adresse

FROM contrat c
JOIN users uc ON uc.id = c.client_id
JOIN location l ON l.id = c.location_id
JOIN users up ON up.id = l.prop_id
WHERE c.id = $id
";
$sqlTerms = "SELECT description_t FROM  terms WHERE contrat_id = $id";

$res = mysqli_query($conn, $sql);
$resTerms = mysqli_query($conn, $sqlTerms);

$termsHtml = "";

if (mysqli_num_rows($resTerms) > 0) {
    $termsHtml .= "<ol>";
    while ($term = mysqli_fetch_assoc($resTerms)) {
        $termsHtml .= "<li>" . htmlspecialchars($term['description_t']) . "</li>";
    }
    $termsHtml .= "</ol>";
} else {
    $termsHtml = "<p>Aucun terme pour ce contrat.</p>";
}

if (!$res || mysqli_num_rows($res) === 0) {
    die("Contrat introuvable");
}

$data = mysqli_fetch_assoc($res);

$html = "
<style>
body { font-family: DejaVu Sans; }
h2 { text-align: center; }
p { margin: 6px 0; }
.sign { display:flex;justify-content:space-between; }
ol { margin-left:20px; }
li { margin-bottom:6px; }
</style>

<h2>Contrat de Location</h2>

<p><strong>Numéro du contrat :</strong> {$data['contrat_id']}</p>
<p><strong>Date :</strong> {$data['date_cont']}</p>
<p><strong>Durée :</strong> {$data['durée_cont']}</p>

<hr>

<p><strong>Client :</strong> {$data['prenom_client']} {$data['nom_client']} (CIN : {$data['cin_client']})</p>
<p><strong>Propriétaire :</strong> {$data['prenom_prop']} {$data['nom_prop']} (CIN : {$data['cin_prop']})</p>
<p><strong>Adresse :</strong> {$data['adresse']}</p>

<p><strong>Les termes du contrat :</strong></p>
$termsHtml

<br><br>

<table width='100%' style='margin-top:40px;'>
    <tr>
        <td width='50%' align='left'>
            Signature client :<br><br>
            ________________________
        </td>
        <td width='50%' align='right'>
            Signature propriétaire :<br><br>
            ________________________
        </td>
    </tr>
</table>
";


$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$dompdf->stream("contrat_{$id}.pdf", ["Attachment" => true]);
exit;
