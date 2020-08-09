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

// remplacement
foreach ($data as $row){
  $pattern = $row[0];
  $substitute = $row[1];
  $resultat = str_replace($pattern, $substitute, $resultat);
}
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
</body>
</html>
<?php
foreach ($data as $row){
  echo "<input type='text' value='".htmlspecialchars($row[0],ENT_QUOTES)."'>=><input type='text' value='".htmlentities($row[1])."'><br/>";
}
?>
