<?php
use Respuesta\Respuesta;
	class SP_model extends Respuesta
	{
	    private $Nombre;
	    private $ApellidoPaterno;
	    private $ApellidoMaterno;
	    private $Correo;
	    private $Password;
	    private $Rol;
	    private $IdUser;
	    private $Descripcion;
	    private $Titulo;
	    private $IdPublicacion;	
		public function __construct(){
			parent::__construct();
		}
		public function setNombre($Nombre){
			$this->Nombre=$Nombre;
		}
		public function getNombre(){
			return $this->Nombre;
		}
		public function setApellidoPaterno($ApellidoPaterno){
			$this->ApellidoPaterno=$ApellidoPaterno;
		}
		public function getApellidoPaterno(){
			return $this->ApellidoPaterno;
		}
		public function setApellidoMaterno($ApellidoMaterno){
			$this->ApellidoMaterno=$ApellidoMaterno;
		}
		public function getApellidoMaterno(){
			return $this->ApellidoMaterno;
		}    
		public function setEmail($Correo){
			$this->Correo=$Correo;
		}
		public function getEmail(){
			return $this->Correo;
		}    
		public function setContrasena($Password){
			$this->Password=$Password;
		}
		public function getContrasena(){
			return $this->Password;
		}    
		public function setRol($Rol){
			$this->Rol=$Rol;
		}
		public function getRol(){
			return $this->Rol;
		}
    	public function setIdUser($IdUser){
			$this->IdUser=$IdUser;
		}
		public function getIdUser(){
			return $this->IdUser;
		}		
		public function setDescripcion($Descripcion){
			$this->Descripcion=$Descripcion;
		}
		public function getDescripcion(){
			return $this->Descripcion;
		}
		public function setTitulo($Titulo){
			$this->Titulo=$Titulo;
		}
		public function getTitulo(){
			return $this->Titulo;
		}

		public function setIdPublicacion($IdPublicacion){
			$this->IdPublicacion=$IdPublicacion;
		}
		public function setnIdPublicacion(){
			return $this->IdPublicacion;
		}
		public function registroDeUsuario()
	    {
	        $array_params = array(
	            array(
	                'name'    => 'CkNombre',
	                'value'   => self::getNombre(),
	                'type'    => 's'
	            ),	            
	            array(
	                'name'    => 'CkApellido',
	                'value'   => self::getApellidoPaterno(),
	                'type'    => 's'
	            ),	                        
	            array(
	                'name'    => 'CkCorreo',
	                'value'   => self::getEmail(),
	                'type'    => 's'
	            ),	            
	            array(
	                'name'    => 'CkPassword',
	                'value'   => self::getContrasena(),
	                'type'    => 's'
	            ),	            
	            array(
	                'name'    => 'CkRol',
	                'value'   => self::getRol(),
	                'type'    => 's'
	            )
	        );
	        $this->oRdb->setBaseDeDatos('ingeniatpublicaciones');
	        $this->oRdb->setSP('insert_usuario');
	        $this->oRdb->setParametros($array_params);
	        $oResult = $this->oRdb->execute();
	        $this->setsMessage($oResult['sMensaje']);
	        if (!$oResult['Exito'] || $oResult['Codigo'] != 0) {
	            return $oResult;
	        }
	        $data = $this->oRdb->fetchAll();
	        $this->oRdb->closeStmt();

	        $nRecords = $this->oRdb->foundRows();
	        $this->oRdb->closeStmt();
	        if (COUNT($data) == 1) {
	            $data = $data[0];
	        }
	        $this->setCodigo(0);
	        $this->setRespuesta($data);
	        $this->setRegistros($nRecords);
	    }

		public function login()
	    {
	        $array_params = array(
	            array(
	                'name'    => 'CksCorreo',
	                'value'   => self::getEmail(),
	                'type'    => 's'
	            ),	            
	            array(
	                'name'    => 'CksPassword',
	                'value'   => self::getContrasena(),
	                'type'    => 's'
	            )	            
	        );
	        $this->oRdb->setBaseDeDatos('ingeniatpublicaciones');
	        $this->oRdb->setSP('select_login');
	        $this->oRdb->setParametros($array_params);
	        $oResult = $this->oRdb->execute();
	        $this->setsMessage($oResult['sMensaje']);
	        if (!$oResult['Exito'] || $oResult['Codigo'] != 0) {
	            return $oResult;
	        }
	        $data = $this->oRdb->fetchAll();
	        $this->oRdb->closeStmt();

	        $nRecords = $this->oRdb->foundRows();
	        $this->oRdb->closeStmt();
	        if (COUNT($data) == 1) {
	            $data = $data[0];
	        }
	        $this->setCodigo(0);
	        $this->setRespuesta($data);
	        $this->setRegistros($nRecords);
	    }
		public function creacionDePublicacion()
	    {
	        $array_params = array(
	            array(
	                'name'    => 'CkIdUsuario',
	                'value'   => self::getIdUser(),
	                'type'    => 'i'
	            ),	            
	            array(
	                'name'    => 'CkTitulo',
	                'value'   => self::getTitulo(),
	                'type'    => 's'
	            ),	            
	            array(
	                'name'    => 'CkDescripcion',
	                'value'   => self::getDescripcion(),
	                'type'    => 's'
	            )
	        );
	        $this->oRdb->setBaseDeDatos('ingeniatpublicaciones');
	        $this->oRdb->setSP('insert_publicacion');
	        $this->oRdb->setParametros($array_params);
	        $oResult = $this->oRdb->execute();
	        $this->setsMessage($oResult['sMensaje']);
	        if (!$oResult['Exito'] || $oResult['Codigo'] != 0) {
	            return $oResult;
	        }
	        $data = $this->oRdb->fetchAll();
	        $this->oRdb->closeStmt();

	        $nRecords = $this->oRdb->foundRows();
	        $this->oRdb->closeStmt();
	        if (COUNT($data) == 1) {
	            $data = $data[0];
	        }
	        $this->setCodigo(0);
	        $this->setRespuesta($data);
	        $this->setRegistros($nRecords);
	    }
		public function actualizacionDePublicacion()
	    {
	        $array_params = array(
	            array(
	                'name'    => 'CkIdPublicacion',
	                'value'   => self::setnIdPublicacion(),
	                'type'    => 'i'
	            ),
	            array(
	                'name'    => 'CkIdUsuario',
	                'value'   => self::getIdUser(),
	                'type'    => 's'
	            ),            
	            array(
	                'name'    => 'CkTitulo',
	                'value'   => self::getTitulo(),
	                'type'    => 's'
	            ),	            
	            array(
	                'name'    => 'CkDescripcion',
	                'value'   => self::getDescripcion(),
	                'type'    => 's'
	            )
	        );
	        $this->oRdb->setBaseDeDatos('ingeniatpublicaciones');
	        $this->oRdb->setSP('update_publicacion');
	        $this->oRdb->setParametros($array_params);
	        $oResult = $this->oRdb->execute();
	        $this->setsMessage($oResult['sMensaje']);
	        if (!$oResult['Exito'] || $oResult['Codigo'] != 0) {
	            return $oResult;
	        }
	        $data = $this->oRdb->fetchAll();
	        $this->oRdb->closeStmt();

	        $nRecords = $this->oRdb->foundRows();
	        $this->oRdb->closeStmt();
	        if (COUNT($data) == 1) {
	            $data = $data[0];
	        }
	        $this->setCodigo(0);
	        $this->setRespuesta($data);
	        $this->setRegistros($nRecords);
	    }
	    public function eliminacionDePublicacionLogica()
	    {
	        $array_params = array(
	            array(
	                'name'    => 'CkIdPublicacion',
	                'value'   => self::setnIdPublicacion(),
	                'type'    => 'i'
	            ),
	            array(
	                'name'    => 'CkIdUsuario',
	                'value'   => self::getIdUser(),
	                'type'    => 's'
	            )
	        );
	        $this->oRdb->setBaseDeDatos('ingeniatpublicaciones');
	        $this->oRdb->setSP('delete_publicacion');
	        $this->oRdb->setParametros($array_params);
	        $oResult = $this->oRdb->execute();
	        $this->setsMessage($oResult['sMensaje']);
	        if (!$oResult['Exito'] || $oResult['Codigo'] != 0) {
	            return $oResult;
	        }
	        $data = $this->oRdb->fetchAll();
	        $this->oRdb->closeStmt();

	        $nRecords = $this->oRdb->foundRows();
	        $this->oRdb->closeStmt();
	        if (COUNT($data) == 1) {
	            $data = $data[0];
	        }
	        $this->setCodigo(0);
	        $this->setRespuesta($data);
	        $this->setRegistros($nRecords);
	    } 
	    public function consultaDePublicaciones()
	    {
	        $this->oRdb->setBaseDeDatos('ingeniatpublicaciones');
	        $this->oRdb->setSP('sp_select_publicacion');
	        $oResult = $this->oRdb->execute();
	        $this->setsMessage($oResult['sMensaje']);
	        if (!$oResult['Exito'] || $oResult['Codigo'] != 0) {
	            return $oResult;
	        }
	        $data = $this->oRdb->fetchAll();
	        $this->oRdb->closeStmt();

	        $nRecords = $this->oRdb->foundRows();
	        $this->oRdb->closeStmt();
	        if (COUNT($data) == 1) {
	            $data = $data[0];
	        }
	        $this->setCodigo(0);
	        $this->setRespuesta($data);
	        $this->setRegistros($nRecords);
	    } 
	}
?>
