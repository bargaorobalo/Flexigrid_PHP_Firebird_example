<?php
$page 		= isset($_POST['page']) 		? $_POST['page'] 		: 1;
$rp 		= isset($_POST['rp']) 			? $_POST['rp'] 			: 10;
$sortname	= isset($_POST['sortname']) 	? $_POST['sortname']	: "NOME";
$sortorder 	= isset($_POST['sortorder'])	? $_POST['sortorder'] 	: "ASC";
$query 		= isset($_POST['query']) 		? $_POST['query'] 		: false;
$qtype 		= isset($_POST['qtype']) 		? $_POST['qtype'] 		: false;
$params		= isset($_POST['IDEMPRESA']) 	? $_POST['IDEMPRESA']   : NULL;
$params2	= isset($_POST['IDPERFIL']) 	? $_POST['IDPERFIL']   : NULL;

$usingSQL = true;

class Json{
	public $valorArray;
	// ++
	function objectToArray($object){
            	if (count($object) >= 1){
			$arr = array();
			for ($i = 0; $i < count($object); $i++){
				$arr[] = get_object_vars($object[$i]);
			}
		return $arr;
		} else{
		
			return get_object_vars($object);
		}
		
		for ($i = 0; $i < count($object); $i++) {
			$arrObj[] = objectToArray($object[$i]);
		}
		return $arrObj;
	}

	// ++
	function arrayToJson($valorArray){
		return json_encode($valorArray);
	}
	
	
}//end class


function runSQL($rsql) {
	/**
	*/
	require_once 'sua_classe_de_bancodedados.php';
	database::setDb('ALIAS_DO_SEU_BD');
	$resultado = database::execSelect($rsql);
	return $resultado;
}

function countRec($fname,$tname) {
	$sql = "SELECT count($fname) FROM $tname ";
	$result = runSQL($sql);
	return $result;
}
if (!$sortname) $sortname = "NOME";
if (!$sortorder) $sortorder = "ASC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page)
    $page = 1;
if (!$rp)
    $rp = 10;

if ($page == 1) {
    $start = $page;
    $end = $rp;
} else {
    $start = (($page-1)*$rp)+1;
    $end = $page*$rp;
}

$limit = "ROWS $start to $end ";
$where = "";
if ($query) $where = " WHERE $qtype CONTAINING '".$query."' ";

$sql = "SELECT * FROM SEL_USUARIOS_EMPRESA_GRID(".$params.",".$params2.") $where $sort $limit";
//echo $sql."</br>";
$result = runSQL($sql);

	$total = countRec('IDUSUARIO','SEL_USUARIOS_EMPRESA_GRID('.$params.','.$params2.')');

	$objJson = new Json();
	$valorArray = $objJson->objectToArray($result);
	$total =  $objJson->objectToArray($total);
	$total = $total[0]['COUNT'];

//header("Content-type: application/json");
$jsonData = array('page'=>$page,'total'=>$total,'rows'=>array());

foreach($valorArray AS $row){
	if($row['ATIVO']==1){$row['ATIVO'] = 'Sim';}else{$row['ATIVO']= 'Não';}
	$entry = array('counter'=>$row['IDUSUARIO'],
		'cell'=>array(
			'IDUSUARIO'=>$row['IDUSUARIO'],
			'NOME'=>strtoupper($row['NOME']),
			'CODIGO'=>$row['CODIGO'],
			'IDIDIOMA'=>$row['IDIDIOMA'],
			'IDIOMA'=>$row['IDIOMA'],
			'LOGIN'=>$row['LOGIN'],
			'ATIVO'=>$row['ATIVO'],
			'TELEFONE'=>$row['TELEFONE'],
			'IDPERFIL'=>$row['IDPERFIL'],
			'PERFIL'=>$row['PERFIL'],
			'OBSERVACAO'=>$row['OBSERVACAO']
		),
	);
	$jsonData['rows'][] = $entry;
}
echo json_encode($jsonData);
//print_r($jsonData);
?> 