<?php
class My_SQLI{
	public $Servidor,$BaseDeDatos,$Puerto,$Conexion,$Usuario,$Contrasena,$TipoObjeto,$bConectado=false;
	public $bDebug = 0,$LOG;
	public $stmt, $resultado;
	public function setServidor($Servidor){
		$this->Servidor = $Servidor;
	}
	public function getServidor(){
		return $this->Servidor;
	}
	public function setBaseDeDatos($BaseDeDatos){
		$this->BaseDeDatos = $BaseDeDatos;
	}
	public function getBaseDeDatos(){
		return $this->BaseDeDatos;
	}
	public function setPuerto($Puerto){
		$this->Puerto = $Puerto;
	}
	public function getPuerto(){
		return $this->Puerto;
	}
	public function setConexion($Conexion){
		$this->Conexion = $Conexion;
	}
	public function getConexion(){
		return $this->Conexion;
	}
	public function setUsuario($Usuario){
		$this->Usuario = $Usuario;
	}
	public function getUsuario(){
		return $this->Usuario;
	}
	public function setContrasena($Contrasena){
		$this->Contrasena = $Contrasena;
	}
	public function getContrasena(){
		return $this->Contrasena;
	}
	
	public function setTipoObjeto($TipoObjeto){
		$this->TipoObjeto = $TipoObjeto;
	}
	public function getTipoObjeto(){
		return $this->TipoObjeto;
	}
	public function setLOG($LOG){
		$this->LOG = $LOG;
	}
	public function getLOG(){
		return $this->LOG;
	}
	public function setSP($SP){
		$this->SP = $SP;
	}
	public function getSP(){
		return $this->SP;
	}
	public function setParametros($Parametros){
		$this->Parametros = $Parametros;
	}
	public function getParametros(){
		return $this->Parametros;
	}
	public function setBDebug($bDebug){
		$this->bDebug = $bDebug;
	}
	public function getBDebug(){
		return $this->bDebug;
	}
	public function setResultado($resultado){
		$this->resultado = $resultado;
	}
	public function getResultado(){
		return $this->resultado;
	}
	public function __construct($array_config){
		self::setServidor($array_config['Servidor']);
		self::setBaseDeDatos($array_config['BaseDeDatos']);
		self::setPuerto($array_config['Puerto']);
		self::setUsuario($array_config['Usuario']);
		self::setContrasena($array_config['Contrasena']);		
		self::setTipoObjeto($array_config['TipoObjeto']);
		self::setLOG($array_config['oLog']);
		self::_connectme();
	}
    private function _connectme(){
    	try{
			$this->LINK = new mysqli($this->Servidor, $this->Usuario, $this->Contrasena, $this->BaseDeDatos, $this->Puerto);
			mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

			$this->bConectado = true;
		}
		catch(mysqli_sql_exception $e){
			$this->LOG->error('Connect Error (' . $e->getCode() . ') '. $e->getMessage());
			$this->bConectado = false;
		}
	}
	private function _getParametrosString(){
		$arrParams		= self::getParametros();
		$length			= count($arrParams);
		$paramsString	= "";
		for($i=0; $i<$length; $i++){
			$paramsString .= "?,";
		}
		$paramsString = trim($paramsString, ',');
		return $paramsString;
	}
	private function _concatenateParams(){
		$arrParams = self::getParametros();
		if(count($arrParams) >= 1){
			$length = count($arrParams);
			for($i=0; $i < $length; $i++){
				$arrValues[] = $arrParams[$i]['value'];
			}
			$sParams	= implode("','", $arrValues);
		}
		else{
			$sParams = "";
		}
		return "'".$sParams."'";
	}
	private function _debugQuery(){
		$sParams		= self::_concatenateParams();
		$sDebugQuery	= "CALL `".self::getBaseDeDatos()."`.`".self::getSP()."`(".$sParams.");";
		$this->LOG->error($sDebugQuery);
	} 
	private function _bindParams(){
		$arrParams		= self::getParametros();
		$stringTypes	= "";
		$stringValues	= "";
		if(count($arrParams) >= 1){
			$length			= count($arrParams);
			$array_params	= array();
			$param_type		= "";
			for($i = 0; $i < $length; $i++) {
				$param_type .= $arrParams[$i]['type'];
			}
			$array_params[] =& $param_type;
			for($i = 0; $i < $length; $i++) {
				$array_params[] =& $arrParams[$i]['value'];
			}
			call_user_func_array(array($this->stmt, 'bind_param'), $array_params);
		}
	} 
	public function execute(){
		if($this->bDebug == 1){
			self::_debugQuery();
		}
		$paramsString	= self::_getParametrosString();
		$queryString	= "CALL `".self::getBaseDeDatos()."`.`".self::getSP()."`(".$paramsString.");";
		try{
			$this->stmt = $this->LINK->prepare($queryString);
			self::_bindParams();
			$this->stmt->execute();
			$this->result = $this->stmt->get_result();
			return array(
				'Exito'			=> true,
				'Codigo'			=> 0,
				'MsgBox'			=> 'Ok',
				'MsgBoxDetail'	=> 'Ok'
			);
		}
		catch(mysqli_sql_exception $e){
			$this->LOG->error("Error al ejecutar ".$queryString." (".$e->getCode().") : ".$e->getMessage()." L ".$e->getLine()." Archivo ".$e->getFile());
			return array(
				'Exito'			=> false,
				'Codigo'			=> $e->getCode(),
				'MsgBox'			=> 'No fue posible realizar ('.$e->getCode().')',
				'MsgBoxDetail'	=> "Detalle de error ".$queryString." (".$e->getCode().") : ".$e->getMessage()." L ".$e->getLine()." Archivo ".$e->getFile()
			);
		}
	} 
	public function fetchAll(){
		$array = $this->result->fetch_all(MYSQLI_ASSOC);
		return $array;
	} 

	public function fetchObject($className = 'StdClass'){
		$array = array();
		while($obj = $this->result->fetch_object($className)){
			$array[] = $obj;
		}
		return $array;
	}
	public function numRows(){
		return $this->result->num_rows;
	}
	public function closeStmt(){
		$this->stmt->close();
	}
	public function freeResult(){
		$this->result->free_result();
	}
	public function closeConnection(){
		$this->LINK->close();
	}
	public function lastInsertId(){
		$this->stmt->prepare("SELECT LAST_INSERT_ID() AS last_insert_id");
		$this->stmt->execute();
		$this->result = $this->stmt->get_result();
		$array = self::fetchAll();
		return $array[0]['last_insert_id'];
	}
	public function foundRows(){
		$this->stmt = $this->LINK->prepare("SELECT FOUND_ROWS() AS found_rows");
		$this->stmt->execute();
		$this->result = $this->stmt->get_result();
		$array = self::fetchAll();
		return $array[0]['found_rows'];
	}
}
?>