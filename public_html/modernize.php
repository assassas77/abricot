<a href="index.php">Remove common OCR errors</a>

<?php header('Content-Type: text/html; charset=utf-8');
if ($_POST["test"]){
  $resultat = $_POST["test"];
  $original = $resultat;
} else {
  $resultat = '; pùa bí;
: to winnow rice.
; thai pùa; to sift and winnow.
; pùa-ki;
: a winnowing basket.
; pùa tīo cho-khng;
: winnow away the chaff.
; khṳt huang éng pùa tîeh;
: splashed by wind and waves.';
  $original = $resultat;
}

// import de la table de conversion
$data = array();

$handle = fopen("modernize.dat", "r");
while ($buffer = fgets($handle)){
  // parsage du fichier qui contient les chaînes à reconnaître et à remplacer
  if (preg_match("/([0-9]+)\t(.+)\t(.+)/u", $buffer, $matches)){
    $data[intval($matches[1])] = array($matches[2], $matches[3]);
  }
}

fclose($handle);

// remplacement à partir du fichier externe
foreach ($data as $row){
  $pattern = $row[0];
  $substitute = $row[1];
  $pattern = '(;.*[ -])(p)([^£])';
  $substitute = "$1b£$3";
  $resultat = preg_replace("/$pattern/m", $substitute, $resultat);
}

// fin du traitement
	// retrait des doubles blancs
$resultat = str_replace("  ", " ", $resultat);
//$resultat = str_replace("£", "", $resultat);
?>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style>textarea { width: 40%; height: 20em;} input[type=submit] { background-color: cyan; }</style></head>
<body>
<form action="modernize.php" method="post">
  <textarea name="test"><?php echo $original;?></textarea>
  <textarea name="resultat"><?php echo $resultat;?></textarea>
  <input type="submit" value="MODERNIZE!">
</form>
<?php
foreach ($data as $row){
  echo "<input type='text' value='".htmlspecialchars($row[0],ENT_QUOTES)."'>=><input type='text' value='".htmlentities($row[1])."'><br/>";
}
?>
</body>
</html>
