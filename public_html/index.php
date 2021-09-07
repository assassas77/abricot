<?php header('Content-Type: text/html; charset=utf-8');
if ($_POST["test"]){
  $resultat = $_POST["test"];
  $original = $resultat;
} else {
  $resultat = 'ka-cau bp niSa" liah m tieh ; without
spittle fleas cannot be caught.';
  $original = $resultat;
}

// import de la table de conversion
$data = array();

$handle = fopen("pattern.dat", "r");
while ($buffer = fgets($handle)){
  // parsage du fichier qui contient les chaînes à reconnaître et à remplacer
  if (preg_match("/([0-9]+)\t(.+)\t(.+)/u", $buffer, $matches)){
    $data[intval($matches[1])] = array($matches[2], $matches[3]);
  }
}

fclose($handle);

// preprocessing
$resultat = preg_replace("/DICT[a-zA-Z ]*LECT. \r\n[0-9]* \r\n/u", "", $resultat);

// remplacement à partir du fichier externe
foreach ($data as $row){
  $pattern = $row[0];
  $substitute = $row[1];
  $resultat = str_replace($pattern, $substitute, $resultat);
}

// traitement des retours à la ligne
$resultat = str_replace("\r\n", " ", $resultat);
$resultat = str_replace("; ", ";\r\n: ", $resultat);
$resultat = str_replace(". ", ".\r\n; ", $resultat);
$resultat = str_replace(" .", ".", $resultat);
	//traitement des ?
$resultat = preg_replace("/\r\n;(.+)\? (.+)\? /u", "\r\n;$1?\r\n: $2?\r\n; ", $resultat);
$resultat = preg_replace("/\r\n:(.+)\? (.+)\r\n/u", "\r\n:$1?\r\n; $2\r\n", $resultat);

	// traitement des , et des ; qui se suivent non reconnus
	// si deux lignes commencent par le même symbole, il faut soit :
	//	: .+ , .+;
	//	: .+
	// sera remplacé par
	//	: .+.
	//	; .+
	//	: .+
	// et si il y a un point virgule
	//	: .+ ;
	//	: .+
	// sera remplacé par
	//	: .+; .+
for ($i = 1; $i <= 5; $i++){
	$resultat = preg_replace("/\r\n:(.+),(.+);\r\n:(.+)/u", "\r\n:$1.\r\n;$2;\r\n:$3", $resultat);
}

$max_def_per_entry = 10;
for ($i = 1; $i <= $max_def_per_entry; $i++){
	$resultat = preg_replace("/\r\n:(.+);\r\n:(.+)\r\n/u", "\r\n:$1; $2\r\n", $resultat);
}

$resultat = str_replace("  ", " ", $resultat);

	// retrait de la dernière ligne vide
$resultat = str_replace("\r\n;  ", "", $resultat);

	// ajout des section
$resultat = preg_replace("/— ([a-zA-Z']*) —/u", "<section end=\"\" />\r\n<section begin=\"\" />\r\n=== $1 ===\r\n;", $resultat);
$resultat = str_replace("\r\n; <section end=\"\" />", "<section end=\"\" />", $resultat);
	// retrait du header
$resultat = preg_replace("/DICT[a-zA-Z ]*LECT. \r\n[0-9][0-9]*/u", "", $resultat);
$resultat = preg_replace("/[0-9]+ DICT[a-zA-Z ]*LECT./u", "", $resultat);

// fin du traitement
	// retrait des doubles blancs
$resultat = str_replace("  ", " ", $resultat);
?>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style>textarea { width: 40%; height: 20em;} input[type=submit] { background-color: #FD9240; }</style></head>
<body>
<form action="index.php" method="post">
  <textarea name="test"><?php echo $original;?></textarea>
  <textarea name="resultat"><?php echo $resultat;?></textarea>
  <input type="submit" value="ABRICOT!">
</form>
<?php
foreach ($data as $row){
  echo "<input type='text' value='".htmlspecialchars($row[0],ENT_QUOTES)."'>=><input type='text' value='".htmlentities($row[1])."'><br/>";
}
?>
</body>
</html>
