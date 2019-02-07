var app=angular.module("siniModule",[]);

app.controller("siniController",

  function ($scope,$http){     

  	$scope.endpoint = "http://localhost/sonia/backend/controllers/"; 
    $scope.cliente = [];
    $scope.asesor = [];
    $scope.redesCliente = [];
    $scope.inmueble = [];
    $scope.ids_tipo_id = [];
  	$scope.ids_tipo_clientes = [];
    $scope.ids_paises = [];
    $scope.ids_departamentos = [];
    $scope.ids_ciudades = [];
    $scope.ids_sectores = [];
    $scope.caracteristicasInmuebles = [];    
    $scope.cant_caract_inm = '0';
    $scope.cant_caract_inm_opc = '0';
    $scope.cant_caract_inm_obl = '0';
    $scope.cant_caract_inm_obl_show = '0';
    $scope.cant_barrios_zona = '0';
    $scope.cant_ases_busq = '1';
    $scope.lista_caracteristicas_inmueble = [];
    $scope.lista_caracteristicas_obligatorias_inmueble = [];
    $scope.lista_caracteristicas_opcionales_inmueble = [];
    $scope.ids_tipo_caracteristicasInm = [];
    $scope.ids_tipo_caracteristicasInm1 = [];
    $scope.ids_tipo_asesores = [];
    $scope.caracteristicas_inmueble_busq = [];   
    $scope.lista_comunas_cali = []; 
    $scope.lista_estratos_cali = []; 
    $scope.lista_estratos_barrios_cali = []; 
    $scope.lista_barrios_zonas = [];
    $scope.barrios_comunas_cali_ini = [];
    $scope.lista_comunas_cali_ini = [];
    $scope.lista_estratos_cali_ini = [];
    $scope.barrios_comunas_cali = [];
    $scope.redesCliente.red_seleccionada = '';
    $scope.redesCliente.id_cliente = '';
    $scope.cant_redes_sociales_cliente = '';
    $scope.lista_redes_sociales_cliente = [];
    $scope.clasificacionBarrios = [];
    $scope.ids_tipo_asesores = [];
    $scope.lista_asesores_busq = [];
    $scope.cambiaComunaInmueble = '1';
    $scope.cambiaEstadoUsuarioAsesor = '0';
    $scope.cambiaEstadoEmpleadoAsesor = '0';
    $scope.valor_usuario_activo = false;
    $scope.cambia_motivo_empleado = '0';
    $scope.asesor.motivo = "";
    $scope.imgAsesorTemp = "";    
    $scope.asesor.foto_asesor = undefined;    
    $scope.lista_asesores_busq.foto = undefined; 
    $scope.asesor_busq =[];   
    $scope.inmobiliaria = [];

    //$scope.seleccionarCliente = [];

    $http.post($scope.endpoint,{'accion':'validateLogin'})
     .then(function(response){      	
       if(response.data == '1'){
       	 $scope.showContent = '../menuPrincipal/formularioMenu1.html';
       }
       else{
       	 $scope.showContent = '../login/formularioLogin.html';
       }
    });

    $scope.seleccionarCliente = function(id_cliente){      
      
      $http.post($scope.endpoint,{'accion':'datosClientePorID','id_cliente':id_cliente})
      .then(function(response){         
        $scope.cliente=response.data.datosCliente;        
        $scope.showClientsCreateUpd($scope.cliente);
      });      
      
    } 

    $scope.getDepartamentosInmobiliaria = function(id_pais){      
      $http.post($scope.endpoint,{'accion':'listaDepartamentos','id_pais':id_pais})
      .then(function(response){ 
        $scope.ids_departamentos=response.data;        
      });
      
    }

    $scope.limpiarAsesoresBusq = function(){
      $scope.asesor_busq = [];
    }

    $scope.seleccionarAsesor = function(id_asesor){      
      
      $http.post($scope.endpoint,{'accion':'datosAsesorPorID','id_asesor':id_asesor})
      .then(function(response){         
        $scope.asesor=response.data.datosAsesor;        
        $scope.showAsesoresCreateUpd($scope.asesor);
      });      
      
    } 

    $scope.showAsesoresCreateUpd = function(asesor){      

      $scope.getTiposIdentificacion();      
      $scope.getPaises();
      $scope.getTiposNotificacion();
      $scope.listarTiposAsesores();
      $scope.showContent = '../asesores/formularioGestionAsesor.html'; 
              
      asesor.numeroIdentificacion = parseInt(asesor.numeroIdentificacion);
      asesor.tipoIdentificacion = {id_tipo_identificacion:asesor.tipoIdentificacion,descripcion:asesor.descIdentificacion};
      asesor.tipoNotificacion = {id_tipo_notificacion:asesor.tipoNotificacion,descripcion:asesor.descNotificacion};
      
      var length = $scope.ids_tipo_asesores.length;
      var tipoAsesor = "";
      for (i = 0; i < length; i++) {
        if($scope.ids_tipo_asesores[i].id_tipo_asesor == asesor.tipoAsesor){
          tipoAsesor = $scope.ids_tipo_asesores[i].descripcion;
          i = length+1;
        }       
      }
      asesor.tipoAsesor = {id_tipo_asesor:asesor.tipoAsesor,descripcion:tipoAsesor};

      if(!angular.isUndefined(asesor.id_pais)){
        var length = $scope.ids_paises.length;
        var pais = "";
        for (i = 0; i < length; i++) {
          if($scope.ids_paises[i].id_pais == cliente.id_pais){
            pais = $scope.ids_paises[i].name;
            i = length+1;
          }       
        }
        cliente.codigoPais = {id_pais:cliente.id_pais,name:pais};
        $scope.getDepartamentosCliente(cliente.codigoPais.id_pais);

        if(!angular.isUndefined(cliente.id_departamento)){
          var length = $scope.ids_departamentos.length;
          var departamento = "";
          for (i = 0; i < length; i++) {
            if($scope.ids_departamentos[i].id_departamento == cliente.id_departamento){
              departamento = $scope.ids_departamentos[i].name;
              i = length+1;
            }       
          }
          cliente.codigoDepartamento = {id_departamento:cliente.id_departamento,name:departamento};
          $scope.getCiudadesCliente(cliente.codigoDepartamento.id_departamento);

          if(!angular.isUndefined(cliente.id_ciudad)){
            var length = $scope.ids_ciudades.length;
            var ciudad = "";
            for (i = 0; i < length; i++) {
              if($scope.ids_ciudades[i].id_ciudad == cliente.id_ciudad){
                ciudad = $scope.ids_ciudades[i].name;
                i = length+1;
              }       
            }
            cliente.codigoCiudad = {id_ciudad:cliente.id_ciudad,name:ciudad};            

          }

        }

      } 

      $scope.cliente=cliente; 

    }

    $scope.setContentDiv = function(){
      $scope.showContent = '../login/formularioLogin.html';           	
    }

    $scope.resetPasswordForm = function(){
      $scope.showContent = '../login/formularioResetPassword.html';           	
    }

    $scope.showClientsIndex = function(){
      $scope.showContent = '../clientes/formularioCliente.html'; 	
    }

    $scope.limpiarInmobiliaria = function(){
      document.formularioInmobiliaria.file.value = '';
      var outputInm = document.getElementById('outputInm');      
      outputInm.style.display = "none";
    }

    $scope.showInformacionInmobiliariaIndex = function(){

      /*$scope.getPaises();
      $scope.inmobiliaria.codigoPais = {id_pais:'1',name:'Colombia'};        
      $scope.getDepartamentosInmobiliaria($scope.cliente.codigoPais.id_pais);*/
      $scope.getPaises();
      $http.post($scope.endpoint,{'accion':'informacionInmobiliaria'})
      .then(function(response){         
        $scope.inmobiliaria=response.data.datosInmobiliaria;       
      });

      $scope.showContent = '../inmobiliaria/formularioInmobiliaria.html';
    }

    //Para guardar o actualizar la información de la inmobiliaria
    $scope.guardarInformacionInmobiliaria = function(){

      var inmobiliaria = {};
      var foto = {};    
      var foto1 = {};
      var base64StringFile = '';
      var fileName = '';
      var fileType = '';

      if(angular.isUndefined($scope.inmobiliaria.nombre_razon_social) || $scope.inmobiliaria.nombre_razon_social==''){
        alert("El nombre de la inmobiliaria no puede estar vacío");
        return false;
      }
      else{
        inmobiliaria.nombre_razon_social = $scope.inmobiliaria.nombre_razon_social;
      }

      if(document.formularioInmobiliaria.file.value!=''&&document.formularioInmobiliaria.file.value!=null){

        var foto_inmobiliaria = document.formularioInmobiliaria.file;
        var extension = foto_inmobiliaria.value.substring(foto_inmobiliaria.value.length-3,foto_inmobiliaria.value.length);

        if(extension == 'jpg' || extension == 'JPG' || extension == 'bmp' || extension == 'BMP' || extension == 'png' || extension == 'PNG'){
                  
          var file = document.querySelector('input[type="file"]').files[0];   

          $scope.getFile(file).then((customJsonFile) => {
             //customJsonFile is your newly constructed file.
             foto.base64StringFile = customJsonFile.base64StringFile;
             foto.fileName = customJsonFile.fileName;
             foto.fileType = customJsonFile.fileType;

            $http.post($scope.endpoint,{'accion':'guardarInformacionInmobiliaria','datos_inmobiliaria':inmobiliaria,'foto':foto})
            .then(function(response){          
              if(response.data.respuesta=='1'){
                alert("Se ha actualizado con éxito la información de la inmobiliaria");
                $scope.inmobiliaria.imagen_logo = response.data.imagen_logo;
                $scope.inmobiliaria.id_inmobiliaria = response.data.id_inmobiliaria;
                document.formularioInmobiliaria.file.value = '';
                var outputInm = document.getElementById('outputInm');      
                outputInm.style.display = "none";
              }     
            })
            .catch(function(data){
                alert(response.data);
            });            
             
          });
          
        }  

      }
      else{
        $http.post($scope.endpoint,{'accion':'guardarInformacionInmobiliaria','datos_inmobiliaria':inmobiliaria,'foto':foto})
        .then(function(response){          
          if(response.data.respuesta=='1'){
            alert("Se ha actualizado con éxito la información de la inmobiliaria");
            //$scope.inmobiliaria.imagen_logo = response.data.imagen_logo;
            $scope.inmobiliaria.id_inmobiliaria = response.data.id_inmobiliaria;
            document.formularioInmobiliaria.file.value = '';
            var outputInm = document.getElementById('outputInm');      
            outputInm.style.display = "none";
          }          
        })
        .catch(function(data){
          alert(response.data);
        });
      }

    }

    //Cargar el formulario o pantalla de gestión de asesores
    $scope.showAsesoresCreate = function(){

      $scope.asesor = [];      
      $scope.ids_paises = [];
      $scope.ids_departamentos = [];
      $scope.ids_ciudades = [];  
      $scope.getTiposIdentificacion();      
      $scope.getPaises();
      $scope.getTiposNotificacion();
      $scope.listarTiposAsesores();
      $scope.showContent = '../asesores/formularioGestionAsesor.html'; 

      $scope.asesor.codigoPais = {id_pais:'1',name:'Colombia'};        
      $scope.getDepartamentosCliente($scope.asesor.codigoPais.id_pais);      
       
    }

    //take a single JavaScript File object
    $scope.getFile = function(file) {
        var reader = new FileReader();
        return new Promise((resolve, reject) => {
            reader.onerror = () => { reader.abort(); reject(new Error("Error parsing file"));}
            reader.onload = function () {

                //This will result in an array that will be recognized by C#.NET WebApi as a byte[]
                let bytes = Array.from(new Uint8Array(this.result));

                //if you want the base64encoded file you would use the below line:
                let base64StringFile = btoa(bytes.map((item) => String.fromCharCode(item)).join(""));

                //Resolve the promise with your custom file structure
                resolve({ 
                    bytes: bytes,
                    base64StringFile: base64StringFile,
                    fileName: file.name, 
                    fileType: file.type
                });
            }
            reader.readAsArrayBuffer(file);
        });
    }

    $scope.showInmueblesIndex = function(){
      $scope.showContent = '../inmuebles/formularioInmueble.html'; 	
    }

    $scope.showCaracteristicasInmueblesIndex = function(){
      $scope.showContent = '../administracion/caracteristicasInmuebles/formularioCaracteristicasInmuebles.html';  
    }

    $scope.showClasificacionBarriosIndex = function(){
      $scope.showContent = '../administracion/clasificacionBarrios/formularioClasificacionBarrios.html';  
    }

    $scope.showAdministracionIndex = function(){
      $scope.showContent = '../administracion/formularioAdministracion.html';  
    }

    $scope.showInmueblesCreate = function(){
      $scope.showContent = '../inmuebles/formularioGestionInmueble.html'	
    }

    //Formulario de asesores de la inmobiliaria
    $scope.showAsesoresInmobiliariaIndex = function(){
      $scope.showContent = '../asesores/formularioAsesor.html'  
    }

    $scope.limpiarRedesClientes = function(){
      $scope.redesCliente.red_social = undefined;
      $scope.redesCliente.nombre_cuenta = undefined;
    }

    $scope.limpiarAsesores = function(){
      $scope.asesor = [];
      /*$scope.ids_paises = [];
      $scope.ids_departamentos = [];
      $scope.ids_ciudades = [];  
      $scope.getTiposIdentificacion();      
      $scope.getPaises();
      $scope.getTiposNotificacion();
      $scope.listarTiposAsesores();
      //$scope.showContent = '../asesores/formularioGestionAsesor.html';*/
      //$scope.asesor.codigoPais = {};
      document.formularioAsesor.file.value = '';
      $scope.asesor.codigoPais = {id_pais:'1',name:'Colombia'};
      //$scope.asesor.codigoPais.name='Colombia';      
      //console.log($scope.asesor.codigoPais);  
      $scope.getDepartamentosCliente('1');
      var output = document.getElementById('output');
      
      output.style.display = "none";
      //$scope.redesCliente.nombre_cuenta = undefined;
    }

    /*$scope.limpiarAsesores = function(){
      $scope.cambiaEstadoUsuarioAsesor = '0';
      $scope.cambiaEstadoEmpleadoAsesor = '0';
      if(!angular.isUndefined($scope.asesor.id_asesor)){
        $scope.cambiaEstadoEmpleadoAsesor = '1';
        if($scope.asesor.tipoAsesor.id_tipo_asesor=='4'){
          $scope.cambiaEstadoUsuarioAsesor = '1';
        }
      }
    }*/

    $scope.listarTiposAsesores = function(){
      $http.post($scope.endpoint,{'accion':'listarTiposAsesores'})
      .then(function(response){               
        $scope.ids_tipo_asesores = response.data.tipos_asesores;
      });
    }

    $scope.cambiarValorEstadoUsuarioAsesor = function(){

      $scope.asesor.motivo = "";

      $scope.valor_usuario_activo = false;

      if($scope.cambia_motivo_empleado == '1'){
        $scope.cambia_motivo_empleado = '0';
      }
      else{
        $scope.cambia_motivo_empleado = '1';
      }      

      if(!angular.isUndefined($scope.asesor.usuario_activo)){
        $scope.valor_usuario_activo = $scope.asesor.usuario_activo;
      }
      
      if($scope.asesor.empleado_activo==false){
        if($scope.asesor.id_tipo_asesor!='4'){
          $scope.asesor.usuario_activo = false;
        }
      }
      else{
        if($scope.asesor.id_tipo_asesor!='4'){
          $scope.asesor.usuario_activo = $scope.valor_usuario_activo;
        }
      }

    }

    $scope.cambiarValorEstadoUsuarioAsesor1 = function(){

      $scope.asesor.motivo = "";
      if($scope.cambia_motivo_empleado == '1'){
        $scope.cambia_motivo_empleado = '0';
      }
      else{
        $scope.cambia_motivo_empleado = '1';  
      }
      

      //$scope.valor_usuario_activo = false;

      /*if(!angular.isUndefined($scope.asesor.usuario_activo)){
        $scope.valor_usuario_activo = $scope.asesor.usuario_activo;
      }
      
      if($scope.asesor.empleado_activo==false){
        if($scope.asesor.id_tipo_asesor!='4'){
          $scope.asesor.usuario_activo = false;
        }
      }
      else{
        if($scope.asesor.id_tipo_asesor!='4'){
          $scope.asesor.usuario_activo = $scope.valor_usuario_activo;
        }
      }*/

    }

    $scope.showRedesSocialesClientes = function(){  
    
      if(!angular.isUndefined($scope.cliente.id_cliente)&&$scope.cliente.id_cliente!=''){
        $scope.redesCliente.numeroIdentificacion = $scope.cliente.numeroIdentificacion;      
        $scope.redesCliente.nombre = $scope.cliente.nombres+' '+$scope.cliente.apellidos;
        $scope.redesCliente.id_cliente = $scope.cliente.id_cliente;      
        $scope.getListaRedesSociales();
        $scope.limpiarRedesClientes();
        $scope.lista_redes_sociales_cliente = [];
        $scope.showContent = '../clientes/formularioRedesCliente.html';  
      }
      else{
        alert('Debe guardar previamente los datos del cliente');
      }
       
    }

    $scope.seleccionarRedSocialCliente = function(id_redes_sociales_cliente){    
    //alert(id_redes_sociales_cliente);  
    //console.log($scope.lista_redes_sociales_cliente);
      angular.forEach($scope.lista_redes_sociales_cliente, function(value, key) {
        if(value.id_red_social_cliente==id_redes_sociales_cliente){
          $scope.redesCliente.red_social={'id_red_social':value.id_red_social,'nombre':value.red_social};          
          $scope.redesCliente.nombre_cuenta=value.cuenta;
          $scope.redesCliente.red_seleccionada='../../form-images/social/'+value.imagen;
          $scope.redesCliente.id_redes_cliente=value.id_red_social_cliente;          
        }  
      });      
    }

    //Guarda o actualiza la información de los asesores
    $scope.saveAsesores = function(){

      var datos_asesor = {};        
      var foto = {};    
      var foto1 = {};
      var base64StringFile = '';
      var fileName = '';
      var fileType = '';

      if(angular.isUndefined($scope.asesor.tipoAsesor)){
        alert("Debe seleccionar el tipo de asesor o empleado de la inmobiliaria");
        return false;
      }
      else{
        datos_asesor.tipoAsesor = $scope.asesor.tipoAsesor.id_tipo_asesor;
      }

      if(angular.isUndefined($scope.asesor.nombres)){
        alert("Debe ingresar los nombres del asesor o empleado de la inmobiliaria");
        return false;
      }
      else{
        datos_asesor.nombres = $scope.asesor.nombres;
      }

      if(angular.isUndefined($scope.asesor.apellidos)){
        alert("Debe ingresar los apellidos del asesor o empleado de la inmobiliaria");
        return false;
      }
      else{
        datos_asesor.apellidos = $scope.asesor.apellidos;
      }

      if(angular.isUndefined($scope.asesor.tipoIdentificacion)){
        alert("Debe seleccionar el tipo de documento de identificación del asesor o empleado de la inmobiliaria");
        return false;
      }
      else{
        datos_asesor.tipoIdentificacion = $scope.asesor.tipoIdentificacion.id_tipo_identificacion;
      }

      if(angular.isUndefined($scope.asesor.numeroIdentificacion)){
        alert("Debe ingresar el número de documento de identificación del asesor o empleado de la inmobiliaria");
        return false;
      }
      else{
        datos_asesor.numeroIdentificacion = $scope.asesor.numeroIdentificacion;
      }

      if(angular.isUndefined($scope.asesor.tipoNotificacion)){
        alert("Debe seleccionar el tipo de notificación que se realizará al asesor o empleado de la inmobiliaria");
        return false;
      }
      else{
        datos_asesor.tipoNotificacion = $scope.asesor.tipoNotificacion.id_tipo_notificacion;
      }

      if(angular.isUndefined($scope.asesor.telefono_movil)){
        alert("Debe ingresar el número de teléfono móvil del asesor o empleado de la inmobiliaria");
        return false;
      }
      else{
        datos_asesor.telefono_movil = $scope.asesor.telefono_movil;
      }

      if(angular.isUndefined($scope.asesor.correo_electronico)){
        alert("Debe ingresar el correo electrónico del asesor o empleado de la inmobiliaria");
        return false;
      }
      else{
        datos_asesor.correo_electronico = $scope.asesor.correo_electronico;
      }

      if(angular.isUndefined($scope.asesor.direccion)){
        alert("Debe ingresar la dirección de residencia del asesor o empleado de la inmobiliaria");
        return false;
      }
      else{
        datos_asesor.direccion = $scope.asesor.direccion;
      }

      if(angular.isUndefined($scope.asesor.codigoPais)){
        alert("Debe seleccionar el país de residencia del asesor o empleado de la inmobiliaria");
        return false;
      }
      else{
        datos_asesor.id_pais = $scope.asesor.codigoPais.id_pais;
      }

      if(angular.isUndefined($scope.asesor.codigoDepartamento)){
        alert("Debe seleccionar el departamento de residencia del asesor o empleado de la inmobiliaria");
        return false;
      }
      else{
        datos_asesor.id_departamento = $scope.asesor.codigoDepartamento.id_departamento;
      }

      if(angular.isUndefined($scope.asesor.codigoCiudad)){
        alert("Debe seleccionar la ciudad de residencia del asesor o empleado de la inmobiliaria");
        return false;
      }
      else{
        datos_asesor.codigoCiudad = $scope.asesor.codigoCiudad.id_ciudad;
      }

      if($scope.asesor.tipoAsesor.id_tipo_asesor!='4'){
        if(angular.isUndefined($scope.asesor.porcentaje_comision)){
          alert("Debe ingresar el porcentaje de comisión que tendrá el asesor o empleado de la inmobiliaria");
          return false;
        }        
        else if($scope.asesor.porcentaje_comision<=0||$scope.asesor.porcentaje_comision>100){
          alert("El porcentaje de comisión debe estar entre 1 y 100");
          return false;
        }
        else{
          datos_asesor.porcentaje_comision = $scope.asesor.porcentaje_comision; 
        }
        if(angular.isUndefined($scope.asesor.usuario_aplicativo)){
          alert("Debe ingresar el usuario que tendrá asignado el asesor o empleado de la inmobiliaria");
          return false;
        }
        else{
          datos_asesor.usuario_aplicativo = $scope.asesor.usuario_aplicativo; 
        }
      }
      if($scope.cambia_motivo_empleado=='1'){
        if(angular.isUndefined($scope.asesor.motivo)){
          alert("Debe ingresar el motivo de cambio de estado del empleado o su usuario del aplicativo dentro de la inmobiliaria");
          return false;
        }
        else{
          datos_asesor.usuario_aplicativo = $scope.asesor.usuario_aplicativo;
        }
      }

      if(!angular.isUndefined($scope.asesor.password)){

        if($scope.asesor.password.length < 8 || $scope.asesor.password.length > 13){
          alert("La contraseña del asesor debe tener entre 8 y 13 caracteres");
          return false;
        }
        else if(angular.isUndefined($scope.asesor.password_confirm)){
          alert("Debe ingresar la confirmación de la contraseña");
          return false;
        }
        else if($scope.asesor.password!=$scope.asesor.password_confirm){
          alert("La contraseña y la confirmación deben coincidir");
          return false;
        }
        else{
          datos_asesor.password = $scope.asesor.password;
        }

        $scope.asesor.password = undefined;
        $scope.asesor.password_confirm = undefined;  

      }          

      if(!angular.isUndefined($scope.asesor.empleado_activo)){
        datos_asesor.empleado_activo = $scope.asesor.empleado_activo; 
      }
      else{
        datos_asesor.empleado_activo = false;  
      }

      if(!angular.isUndefined($scope.asesor.usuario_activo)){
        datos_asesor.usuario_activo = $scope.asesor.usuario_activo; 
      }
      else{
        datos_asesor.usuario_activo = false;  
      }

      if(!angular.isUndefined($scope.asesor.id_asesor)){
        datos_asesor.id_asesor = $scope.asesor.id_asesor;
      }

      if(!angular.isUndefined($scope.asesor.telefono_fijo)){
        datos_asesor.telefono_fijo = $scope.asesor.telefono_fijo;
      }

      //datos_asesor.photo = {};

      if(document.formularioAsesor.file.value!=''&&document.formularioAsesor.file.value!=null){

        var foto_asesor = document.formularioAsesor.file;
        var extension = foto_asesor.value.substring(foto_asesor.value.length-3,foto_asesor.value.length);

        if(extension == 'jpg' || extension == 'JPG' || extension == 'bmp' || extension == 'BMP' || extension == 'png' || extension == 'PNG'){
                  
          var file = document.querySelector('input[type="file"]').files[0];   

          $scope.getFile(file).then((customJsonFile) => {
             //customJsonFile is your newly constructed file.
             foto.base64StringFile = customJsonFile.base64StringFile;
             foto.fileName = customJsonFile.fileName;
             foto.fileType = customJsonFile.fileType;

             $http.post($scope.endpoint,{'accion':'saveAsesores','datos_asesor':datos_asesor,'foto':foto})
            .then(function(response){          
                if(response.data.respuesta=='1'){ 
                  $scope.asesor.id_asesor=response.data.id_asesor;
                  $scope.asesor.empleado_activo=response.data.empleado_activo;
                  $scope.asesor.usuario_activo=response.data.usuario_activo;
                  $scope.asesor.foto_asesor=response.data.fotoAsesor;

                  document.formularioAsesor.file.value = '';
                  var output = document.getElementById('output');      
                  output.style.display = "none";

                  alert('Asesor almacenado exitosamente');                 
              
                }
                else if(response.data.respuesta=='2'){
                  alert('Ya existe un asesor con ese número de documento, por favor verifique');          
                }
                else if(response.data.respuesta=='3'){
                  $scope.asesor.id_asesor=response.data.id_asesor;
                  $scope.asesor.empleado_activo=response.data.empleado_activo;
                  $scope.asesor.usuario_activo=response.data.usuario_activo;
                  $scope.asesor.foto_asesor=response.data.fotoAsesor;

                  document.formularioAsesor.file.value = '';
                  var output = document.getElementById('output');      
                  output.style.display = "none";

                  alert('Asesor actualizado exitosamente');      
          
                }
                else if(response.data.respuesta=='4'){
                  alert('No se ha podido actualizar el asesor, por favor verifique');            
                }  
                else if(response.data.respuesta=='5'){ 
                  $scope.asesor.id_asesor=response.data.id_asesor;
                  $scope.asesor.empleado_activo=response.data.empleado_activo;
                  $scope.asesor.usuario_activo=response.data.usuario_activo;
                  alert('Asesor almacenado exitosamente, pero el usuario para el aplicativo ingresado ya existe para otro empleado, por favor verifique');                 
          
                }        
            })
            .catch(function(data){
                alert(response.data);
            });            
             
          });
          
        }  

      }
      else{

        $http.post($scope.endpoint,{'accion':'saveAsesores','datos_asesor':datos_asesor})
        .then(function(response){          
            if(response.data.respuesta=='1'){ 
              $scope.asesor.id_asesor=response.data.id_asesor;
              $scope.asesor.empleado_activo=response.data.empleado_activo;
              $scope.asesor.usuario_activo=response.data.usuario_activo;
              alert('Asesor almacenado exitosamente');                 
              
            }
            else if(response.data.respuesta=='2'){
              alert('Ya existe un asesor con ese número de documento, por favor verifique');          
            }
            else if(response.data.respuesta=='3'){
              $scope.asesor.id_asesor=response.data.id_asesor;
              $scope.asesor.empleado_activo=response.data.empleado_activo;
              $scope.asesor.usuario_activo=response.data.usuario_activo;
              alert('Asesor actualizado exitosamente');      
          
            }
            else if(response.data.respuesta=='4'){
              alert('No se ha podido actualizar el asesor, por favor verifique');            
            }  
            else if(response.data.respuesta=='5'){ 
              $scope.asesor.id_asesor=response.data.id_asesor;
              $scope.asesor.empleado_activo=response.data.empleado_activo;
              $scope.asesor.usuario_activo=response.data.usuario_activo;
              alert('Asesor almacenado exitosamente, pero el usuario para el aplicativo ingresado ya existe para otro empleado, por favor verifique');                 
          
            }        
        })
        .catch(function(data){
            alert(response.data);
        });

      }        
      
    }

    $scope.borrarRedSocialCliente = function(id_red_social_cliente,red_social,cuenta){      
      if(confirm("Realmente desea borrar la cuenta "+cuenta+" de "+red_social+" de este cliente?")){
        $http.post($scope.endpoint,{'accion':'borrarCuentaRedSocialCliente','id_red_social_cliente':id_red_social_cliente})
        .then(function(response){               
          alert("Se ha borrado la cuenta con éxito");
          $scope.listarRedesSocialesCliente();
          $scope.limpiarRedesClientes();
        });
      }
    }

    //Función para des-asociar un barrio de una zona
    $scope.borrarBarrioZona = function(id_zona_barrio,id_zona,id_ciudad){        
      if(confirm("Realmente desea borrar el barrio de dicha zona?")){
        $http.post($scope.endpoint,{'accion':'borrarBarrioZona','id_zona_barrio':id_zona_barrio})
        .then(function(response){   
          if(response.data = '1'){
            alert("Se ha borrado el barrio de la zona con éxito");
            $scope.listarBarriosZona(id_zona,id_ciudad); 
          }            
                   
        });
      }
    }

    //Función que lista todos los barrios asociados a una zona
    $scope.listarBarriosZona = function(id_sector,id_ciudad){
      $scope.barrios_comunas_cali = $scope.barrios_comunas_cali_ini;
      $scope.clasificacionBarrios.codigoBarrio = {};
      $scope.clasificacionBarrios.codigoZona = {};
      $scope.clasificacionBarrios.codigoEstrato = {};
      $http.post($scope.endpoint,{'accion':'listarBarriosZona','id_sector':id_sector,'id_ciudad':id_ciudad})
      .then(function(response){     
        if(response.data.cant_barrios_zona == '0'){
          $scope.lista_barrios_zonas = [];
          $scope.cant_barrios_zona = '0';
        } 
        else{
          $scope.cant_barrios_zona = response.data.cant_barrios_zona;
          $scope.lista_barrios_zonas = response.data.barrios_zona; 
          //var lista_barrios_restantes = [];           
          angular.forEach($scope.lista_barrios_zonas, function(value1,key1){              
            if(id_ciudad=='132'){                          
              angular.forEach($scope.barrios_comunas_cali, function(value,key){           
                if(value.codigo == value1.id_barrio){                                    
                  $scope.lista_barrios_zonas[key1].nombre_barrio = value.nombre;
                  angular.forEach($scope.lista_comunas_cali, function(value2,key2){           
                    if(value.comuna == value2.codigo){
                      $scope.lista_barrios_zonas[key1].nombre_comuna = value2.nombre;
                    }
                  });
                  angular.forEach($scope.lista_estratos_cali, function(value2,key2){           
                    if(value.estrato == value2.codigo){
                      $scope.lista_barrios_zonas[key1].nombre_estrato = value2.nombre;
                    }
                  });                  
                }
                
              });  
                           
            } 
                    
          }); 
          //console.log($scope.lista_barrios_zonas);
          var lista_barrios_restantes = []; 
          var i = 0;
          angular.forEach($scope.barrios_comunas_cali, function(value,key){  
            var cal = 0;  
            angular.forEach($scope.lista_barrios_zonas, function(value1,key1){           
              if(value.codigo == value1.id_barrio){
                //$scope.lista_barrios_zonas[key1].nombre_estrato = value1.nombre;
                cal = 1;
              }
            });        
            if(cal == 0){
              lista_barrios_restantes[i] = value;
              i++;
            }
          });

          $scope.barrios_comunas_cali = lista_barrios_restantes;
          //console.log(lista_barrios_restantes);                

        }        

      });
    }

    //Función que lista todos los barrios asociados a una zona en la lista de selección de barrios
    $scope.listarBarriosZona1 = function(id_sector,id_ciudad){      

      var codest = $scope.inmueble.codigoEstrato;
      var codzon = $scope.inmueble.codigoZona;

      if(!angular.isUndefined(id_sector)){

        $scope.barrios_comunas_cali = $scope.barrios_comunas_cali_ini;  
        //$scope.cambiaComunaInmueble = '0';
        $scope.inmueble.codigoZona = undefined; 
        $scope.inmueble.codigoEstrato = undefined;   
        $scope.inmueble.codigoBarrio = {}; 
        $http.post($scope.endpoint,{'accion':'listarBarriosZona','id_sector':id_sector,'id_ciudad':id_ciudad})
        .then(function(response){     
          if(response.data.cant_barrios_zona == '0'){
            $scope.lista_barrios_zonas = [];
            $scope.cant_barrios_zona = '0';
            $scope.barrios_comunas_cali = [];
          } 
          else{
            $scope.cant_barrios_zona = response.data.cant_barrios_zona;
            $scope.lista_barrios_zonas = response.data.barrios_zona; 
            //var lista_barrios_restantes = [];           
            angular.forEach($scope.lista_barrios_zonas, function(value1,key1){              
              if(id_ciudad=='132'){                          
                angular.forEach($scope.barrios_comunas_cali, function(value,key){           
                  if(value.codigo == value1.id_barrio){                                    
                    $scope.lista_barrios_zonas[key1].nombre_barrio = value.nombre;
                    angular.forEach($scope.lista_comunas_cali, function(value2,key2){           
                      if(value.comuna == value2.codigo){
                        $scope.lista_barrios_zonas[key1].nombre_comuna = value2.nombre;
                      }
                    });
                    angular.forEach($scope.lista_estratos_cali, function(value2,key2){           
                      if(value.estrato == value2.codigo){
                        $scope.lista_barrios_zonas[key1].nombre_estrato = value2.nombre;
                      }
                    });                  
                  }
                
                });  
                           
              } 
                    
            }); 
          
            var lista_barrios_restantes = []; 
            var i = 0;
            angular.forEach($scope.barrios_comunas_cali, function(value,key){  
              var cal = 0;  
              angular.forEach($scope.lista_barrios_zonas, function(value1,key1){           
                if(value.codigo == value1.id_barrio){
                  $scope.lista_barrios_zonas[key1].nombre_estrato = value1.nombre;
                  cal = 1;
                }
              });        
              if(cal == 1){
                lista_barrios_restantes[i] = value;
                i++;
              }
            });

            $scope.barrios_comunas_cali = lista_barrios_restantes;                     

          }        

        });        

        if(codest == $scope.inmueble.codigoEstrato && codzon == $scope.inmueble.codigoZona){
          $scope.cambiaComunaInmueble = '1';
        }      
        else{
          $scope.cambiaComunaInmueble = '0';
        }        

      }
      else{

        $scope.barrios_comunas_cali = $scope.barrios_comunas_cali_ini;
        $scope.lista_comunas_cali = $scope.lista_comunas_cali_ini;
        $scope.cambiaComunaInmueble = '1';
        $scope.inmueble.codigoZona = undefined; 
        $scope.inmueble.codigoEstrato = undefined;   
        $scope.inmueble.codigoBarrio = {};           

      }  

      if(angular.isUndefined($scope.inmueble.codigoSector)){
        $scope.cambiaComunaInmueble = '1';
      }
      else{
        $scope.cambiaComunaInmueble = '0';
      }    
      
    }

    $scope.showClientsCreate = function(){

      $scope.cliente = [];
      $scope.ids_paises = [];
      $scope.ids_departamentos = [];
      $scope.ids_ciudades = [];  
      $scope.getTiposIdentificacion();
      $scope.getTiposClientes();
      $scope.getPaises();
      $scope.getTiposNotificacion();
      $scope.showContent = '../clientes/formularioGestionCliente.html'; 

      $scope.cliente.codigoPais = {id_pais:'1',name:'Colombia'};      	
      $scope.getDepartamentosCliente($scope.cliente.codigoPais.id_pais);
      
    } 

    $scope.getDepartamentosCliente = function(id_pais){      
      $http.post($scope.endpoint,{'accion':'listaDepartamentos','id_pais':id_pais})
      .then(function(response){ 
        $scope.ids_departamentos=response.data;        
      });
      
    }

    $scope.getListaRedesSociales = function(){
      $http.post($scope.endpoint,{'accion':'listaRedesSociales'})
      .then(function(response){ 
        $scope.ids_redes_sociales=response.data;        
      });
    }

    $scope.getImgRedSocial = function(imagen){      
      $scope.redesCliente.nombre_cuenta = undefined;
      $scope.redesCliente.red_seleccionada = '../../form-images/social/'+imagen;      
    }

    $scope.saveRedesClientes = function(){
      if(angular.isUndefined($scope.redesCliente.red_social)){
        alert('Debe seleccionar una red social');
      }
      else if(angular.isUndefined($scope.redesCliente.nombre_cuenta)){
        alert('Debe ingresar el nombre de la cuenta');
      } 
      else{
        var datosRedesCliente = {};
        datosRedesCliente.id_cliente = $scope.redesCliente.id_cliente;
        datosRedesCliente.id_red_social = $scope.redesCliente.red_social.id_red_social;
        datosRedesCliente.nombre_cuenta = $scope.redesCliente.nombre_cuenta;
        if(!angular.isUndefined($scope.redesCliente.id_redes_cliente)){
          datosRedesCliente.id_redes_cliente = $scope.redesCliente.id_redes_cliente;
        }
        $http.post($scope.endpoint,{'accion':'guardarRedesSocialesCliente','datosRedesCliente':datosRedesCliente})
        .then(function(response){ 
          if(response.data.respuesta == '1'){
            alert('Red social del cliente actualizada exitosamente');
            $scope.redesCliente.id_redes_cliente = response.data.id_redes_sociales_cliente;
            $scope.listarRedesSocialesCliente();            
          }
          else if(response.data.respuesta == '2'){
            alert('La red social que intenta asociar ya se encuentra relacionada a otro cliente');
            //datosRedesCliente.id_redes_cliente = response.data.id_redes_sociales_cliente;
          }
          else if(response.data.respuesta == '3'){
            alert('Red social ingresada existosamente para este cliente');
            $scope.redesCliente.id_redes_cliente = response.data.id_redes_sociales_cliente;
            $scope.listarRedesSocialesCliente();
          }        
          else{
            alert('Ha ocurrido un error inesperado, contacte al administrador');
            //datosRedesCliente.id_redes_cliente = response.data.id_redes_sociales_cliente;
          }
        });
      }   
    }

    $scope.getCiudadesCliente = function(id_departamento){      
      $http.post($scope.endpoint,{'accion':'listaCiudades','id_departamento':id_departamento})
      .then(function(response){         
        $scope.ids_ciudades=response.data;
      });
      
    }

    $scope.showClientsCreateUpd = function(cliente){

      $scope.getTiposIdentificacion();
      $scope.getTiposClientes();
      $scope.getPaises();
      $scope.getTiposNotificacion();
      $scope.showContent = '../clientes/formularioGestionCliente.html';         
      cliente.numeroIdentificacion = parseInt(cliente.numeroIdentificacion);
      cliente.tipoIdentificacion = {id_tipo_identificacion:cliente.tipoIdentificacion,descripcion:cliente.descIdentificacion};
      cliente.tipoNotificacion = {id_tipo_notificacion:cliente.tipoNotificacion,descripcion:cliente.descNotificacion};
      
      var length = $scope.ids_tipo_clientes.length;
      var tipoCliente = "";
      for (i = 0; i < length; i++) {
        if($scope.ids_tipo_clientes[i].id_tipo_cliente == cliente.tipoCliente){
          tipoCliente = $scope.ids_tipo_clientes[i].descripcion;
          i = length+1;
        }       
      }
      cliente.tipoCliente = {id_tipo_cliente:cliente.tipoCliente,descripcion:tipoCliente};

      if(!angular.isUndefined(cliente.id_pais)){
        var length = $scope.ids_paises.length;
        var pais = "";
        for (i = 0; i < length; i++) {
          if($scope.ids_paises[i].id_pais == cliente.id_pais){
            pais = $scope.ids_paises[i].name;
            i = length+1;
          }       
        }
        cliente.codigoPais = {id_pais:cliente.id_pais,name:pais};
        $scope.getDepartamentosCliente(cliente.codigoPais.id_pais);

        if(!angular.isUndefined(cliente.id_departamento)){
          var length = $scope.ids_departamentos.length;
          var departamento = "";
          for (i = 0; i < length; i++) {
            if($scope.ids_departamentos[i].id_departamento == cliente.id_departamento){
              departamento = $scope.ids_departamentos[i].name;
              i = length+1;
            }       
          }
          cliente.codigoDepartamento = {id_departamento:cliente.id_departamento,name:departamento};
          $scope.getCiudadesCliente(cliente.codigoDepartamento.id_departamento);

          if(!angular.isUndefined(cliente.id_ciudad)){
            var length = $scope.ids_ciudades.length;
            var ciudad = "";
            for (i = 0; i < length; i++) {
              if($scope.ids_ciudades[i].id_ciudad == cliente.id_ciudad){
                ciudad = $scope.ids_ciudades[i].name;
                i = length+1;
              }       
            }
            cliente.codigoCiudad = {id_ciudad:cliente.id_ciudad,name:ciudad};            

          }

        }

      } 

      $scope.cliente=cliente; 

    }

    $scope.returnMenuIndex = function(){
      $scope.showContent = '../menuPrincipal/formularioMenu1.html';           	
    } 

    $scope.validateSession = function(user,password){     
      if(angular.isUndefined(user) || angular.isUndefined(password)){
        alert("Todos los campos con (*) son requeridos");        
      } 
      else{      	
        $http.post($scope.endpoint,{'accion':'validateSession','user':user,'password':password})
        .then(function(response){          
          if(response.data.respuesta=='1'){          	
          	$scope.showContent = '../menuPrincipal/formularioMenu1.html';
          }
          else{
          	alert('Datos incorrectos, por favor verifique');
          }
        })
        .catch(function(data){
          alert(response.data);
        });
      }     

    }  	

    $scope.listarRedesSocialesCliente = function(){
      var id_cliente = $scope.redesCliente.id_cliente;
      $http.post($scope.endpoint,{'accion':'listarRedesSocialesCliente','id_cliente':id_cliente})
      .then(function(response){         
        $scope.cant_redes_sociales_cliente=response.data.cantidad_redes_sociales_cliente;        
        $scope.lista_redes_sociales_cliente=response.data.redes_sociales_cliente;
      });
    } 

    $scope.getTiposIdentificacion = function(){            
      $http.post($scope.endpoint,{'accion':'tiposIdentificacion'})
      .then(function(response){       	
        $scope.ids_tipo_id=response.data;
      });

    }     

    $scope.getPaises = function(){            
      $http.post($scope.endpoint,{'accion':'listaPaises'})
      .then(function(response){             	
        $scope.ids_paises=response.data;        
      });

    }

    $scope.getMonedas = function(){            
      $http.post($scope.endpoint,{'accion':'listaMonedas'})
      .then(function(response){       	
        $scope.ids_monedas=response.data;
        $scope.conversion_pesos=null;
      });

    }

    $scope.getConversionPesos = function(moneda){            
      $http.post($scope.endpoint,{'accion':'getConversion','moneda':moneda})
      .then(function(response){       	
        $scope.conversion_pesos=response.data;        
      });

    }

    $scope.getDepartamentos = function(codigoPais){
      $scope.ids_departamentos=null; 
      $scope.ids_ciudades=null;      
      $scope.ids_zonas=null; 
      $scope.ids_sectores=null; 
      $http.post($scope.endpoint,{'accion':'listaDepartamentos','id_pais':codigoPais.id_pais})
      .then(function(response){       	
        $scope.ids_departamentos=response.data;
      });
      
    }


    $scope.getCiudades = function(codigoDepartamento){  
      $scope.ids_ciudades=null; 	
      $scope.ids_zonas=null; 
      $scope.ids_sectores=null;
      if(codigoDepartamento!=null){
        var id_departamento = codigoDepartamento.id_departamento;        
        $http.post($scope.endpoint,{'accion':'listaCiudades','id_departamento':id_departamento})
        .then(function(response){       	
          $scope.ids_ciudades=response.data;
        });
      }	 

    }

    $scope.getZonasSectores = function(codigoCiudad){  
      $scope.ids_zonas=null; 
      $scope.ids_sectores=null;	
      $scope.clasificacionBarrios.codigoBarrio = [];
      $scope.clasificacionBarrios.codigoZona = [];   
      $scope.clasificacionBarrios.codigoEstrato = [];
      $scope.inmueble.codigoSector = undefined;
      $scope.inmueble.codigoBarrio = [];
      $scope.inmueble.codigoZona = undefined;   
      $scope.inmueble.codigoEstrato = undefined;     
      if(codigoCiudad!=null){
        var id_ciudad = codigoCiudad.id_ciudad;   
        $http.post($scope.endpoint,{'accion':'listaSectores','id_ciudad':id_ciudad})
        .then(function(response){       	
          $scope.ids_sectores=response.data;
          //console.log(response.data);
        });     
        $http.post($scope.endpoint,{'accion':'listaZonas','id_ciudad':id_ciudad})
        .then(function(response){       	
          $scope.ids_zonas=response.data;
        }); 
        if(id_ciudad=='132'){
          //Barrios y estratos Cali
          $http.get('https://www.datos.gov.co/resource/i2dd-kbda.json')
          .then(function(response){       	
            var barrios_estratos_cali=response.data;    
            var cant_barr=barrios_estratos_cali.length;            
            var lista_comunas_cali = [];
            var lista_estratos_cali = [];  
            var lista_estratos_barrios_cali = [];         
            var j=0;
            var k=0;
            var l=0;
            angular.forEach(barrios_estratos_cali, function(value, key) {
              if(value.c_digo_nico == '-'){                      
                lista_comunas_cali[j]={};     
                lista_comunas_cali[j].nombre = value.barrio;
                lista_comunas_cali[j].codigo = (value.barrio.substring(7,value.barrio.length)!='') ? value.barrio.substring(7,value.barrio.length) : '0';
                lista_comunas_cali[j].estrato = value.estrato_moda;
                var band = 0;
                angular.forEach(lista_estratos_cali, function(value1, key1){                  
                  if(lista_comunas_cali[j].estrato == value1.nombre){
                    band = 1;                      
                  }
                });
                if(band == 0){
                  lista_estratos_cali[parseInt(lista_comunas_cali[j].estrato)-1]={};
                  lista_estratos_cali[parseInt(lista_comunas_cali[j].estrato)-1].nombre = lista_comunas_cali[j].estrato; 
                  lista_estratos_cali[parseInt(lista_comunas_cali[j].estrato)-1].codigo = lista_comunas_cali[j].estrato;
                  //k++;
                }
                j++;
              }
              else{
                lista_estratos_barrios_cali[l] = {};
                if(!angular.isUndefined(value.estrato_moda)){
                  lista_estratos_barrios_cali[l].estrato = value.estrato_moda;  
                }
                else{
                  lista_estratos_barrios_cali[l].estrato = '0';
                }
                lista_estratos_barrios_cali[l].codigo = value.c_digo_nico; 
                
                l++;
              }              
            });    
               
            $scope.lista_comunas_cali = lista_comunas_cali;
            $scope.lista_comunas_cali_ini = lista_comunas_cali;
            $scope.lista_estratos_cali = lista_estratos_cali; 
            $scope.lista_estratos_cali_ini = lista_estratos_cali;  
            $scope.lista_estratos_barrios_cali = lista_estratos_barrios_cali;                                  
          });
          //Barrios y comunas Cali
          $http.get('https://www.datos.gov.co/resource/hxey-nky4.json')
          .then(function(response){  
            var barrios_comunas_cali=response.data;
            var i=0; 
            var barrios_comunas_cali1=[];
            
            angular.forEach(barrios_comunas_cali, function(value,key){              
              barrios_comunas_cali1[i] = {};
              barrios_comunas_cali1[i].codigo = value.c_digo;
              barrios_comunas_cali1[i].nombre = value.barrio_urbanizaci_n_o_sector;   
              barrios_comunas_cali1[i].comuna = value.comuna;
              
              angular.forEach($scope.lista_estratos_barrios_cali, function(value1,key1){
                if(barrios_comunas_cali1[i].codigo == value1.codigo){
                  barrios_comunas_cali1[i].estrato = value1.estrato;
                }
              });

              i++;
            });
            $scope.barrios_comunas_cali = barrios_comunas_cali1;            
            $scope.barrios_comunas_cali_ini=$scope.barrios_comunas_cali;            
          });
        }
                
      }	 

    }

    $scope.getTiposNotificacion = function(){            
      $http.post($scope.endpoint,{'accion':'tiposNotificacion'})
      .then(function(response){             	
        $scope.ids_tipo_notificacion=response.data;
      });

    }  

    $scope.getTiposInmuebles = function(){            
      $http.post($scope.endpoint,{'accion':'tiposInmuebles'})
      .then(function(response){             	
        $scope.ids_tipo_inmuebles=response.data;
      });

    }  

    $scope.getTiposClientes = function(){            
      $http.post($scope.endpoint,{'accion':'tiposClientes'})
      .then(function(response){             	
        $scope.ids_tipo_clientes=response.data;
      });

    } 

    $scope.getTiposCaracteristicasInm = function(){      
      $http.post($scope.endpoint,{'accion':'tiposCaracteristicasInm'})
      .then(function(response){             	
        $scope.ids_tipo_caracteristicasInm=response.data;            
      });

    }  

    /*$scope.getTiposCaracteristicasInm1 = function(){      
      $http.post($scope.endpoint,{'accion':'tiposCaracteristicasInm'})
      .then(function(response){        
        $scope.ids_tipo_caracteristicasInm1=response.data;       
      });

    }*/

    $scope.getCriteriosDiligenciamiento = function(){            
      $http.post($scope.endpoint,{'accion':'getCriteriosDiligenciamiento'})
      .then(function(response){               
        $scope.ids_criterios_diligenciamiento=response.data;        
      });

    } 

    $scope.guardarCaracteristicaTipoInmueble = function(){      
      if(angular.isUndefined($scope.caracteristicasInmuebles) 
          || angular.isUndefined($scope.caracteristicasInmuebles.tipoInmueble) 
          || angular.isUndefined($scope.caracteristicasInmuebles.tipoCaracteristica) 
          || angular.isUndefined($scope.caracteristicasInmuebles.criterio_diligenciamiento)
          || angular.isUndefined($scope.caracteristicasInmuebles.tipoCaracteristicaInmueble)){
        alert("Todos los campos con (*) son requeridos");
      }
      else{
        var descripcion='';
        if(!angular.isUndefined($scope.caracteristicasInmuebles.descripcion)){
          descripcion=$scope.caracteristicasInmuebles.descripcion;
        }
        $http.post($scope.endpoint,{'accion':'guardarCaracteristicaTipoInmueble', 
          'datosCaracteristicaTipoInmueble' :
          {
            'tipoInmueble': $scope.caracteristicasInmuebles.tipoInmueble.id_tipo_inmueble,
            'tipoCaracteristica' : $scope.caracteristicasInmuebles.tipoCaracteristica.id_tipo_caracteristica,
            'criterio_diligenciamiento' : $scope.caracteristicasInmuebles.criterio_diligenciamiento.id_criterio_diligenciamiento,
            'tipoCaracteristicaInmueble' : $scope.caracteristicasInmuebles.tipoCaracteristicaInmueble.id_tipo_caracteristica_inmueble,
            'descripcion' : descripcion
          }           
        })
        .then(function(response){ 
          if(response.data.respuesta=='1'){
            alert("Característica relacionada exitosamente");
            $scope.limpiarCaracteristicasTipoInmueble();            
          }
          else{
            alert("No se ha podido relacionar la característica");
          }        
        });
      }      
    }

    $scope.getTiposCaracteristicasInmuebles = function(){            
      $http.post($scope.endpoint,{'accion':'tiposCaracteristicasInmuebles'})
      .then(function(response){ 
        $scope.ids_tipo_caracteristicas_inmuebles=response.data;        
      });

    } 

    $scope.getEstratoComunaCali = function(codigoBarrio){ 

      var camb = '0';
      if(angular.isUndefined($scope.inmueble.codigoEstrato)){
        //$scope.cambiaComunaInmueble = '1';
        camb = '1';
      }
      else{
        //$scope.cambiaComunaInmueble = '0';
        camb = '0';
      }
      $scope.lista_comunas_cali = $scope.lista_comunas_cali_ini;
      //console.log($scope.lista_comunas_cali);
      if(!angular.isUndefined(codigoBarrio)&&(codigoBarrio!=null)){
        var comuna = '';
        var exist_comuna = 0;
        angular.forEach($scope.lista_comunas_cali,function(value,key){
          if(value.codigo == codigoBarrio.comuna){
            comuna = value.nombre;
            exist_comuna = 1;
          }
        });
        var estrato = '';
        angular.forEach($scope.lista_estratos_cali,function(value,key){
          if(value.codigo == codigoBarrio.estrato){
            estrato = value.nombre;
          }
        });
        if(exist_comuna == 1){
          $scope.inmueble.codigoZona = {'codigo':codigoBarrio.comuna,'nombre':comuna};
        }
        else{
          $scope.inmueble.codigoZona = {}; 
        }
        var exist_zona = 0;
        
        $scope.inmueble.codigoZona = {'codigo':codigoBarrio.comuna,'nombre':comuna};
        $scope.inmueble.codigoEstrato = {'codigo':codigoBarrio.estrato,'estrato':estrato};
        
        $http.post($scope.endpoint,{'accion':'getZonaBarrioCali','id_barrio':codigoBarrio.codigo})
        .then(function(response){       
          console.log(response);   
          if(!angular.isUndefined(response.data) && response.data!='null'){
            var id_zona = response.data.replace('"','');  
            id_zona = id_zona.replace('"','');          
            var nombre_zona = '';
            var ind = 0;
            angular.forEach($scope.ids_sectores,function(value,key){              
              if(value.id_sector.toString() == id_zona){
                ind = 1;
                nombre_zona = value.name;
              }
            });
            if(ind == 1){
              $scope.inmueble.codigoSector = {'id_sector':id_zona,'name':nombre_zona};   
              //console.log($scope.inmueble.codigoSector);            
            }
          }
          else{
            $scope.inmueble.codigoSector = undefined; 
          }       
        });
        if(camb=='1'){
          $scope.cambiaComunaInmueble = '1';
        }
        else{
          $scope.cambiaComunaInmueble = '0';
        }
        //console.log($scope.inmueble);
      }
      /*else{
        $scope.inmueble.codigoZona = {};
        $scope.inmueble.codigoEstrato = {};
      } */    
      
    }

    //clasificacionBarrios
    $scope.getEstratoComunaCaliClasif = function(codigoBarrio){ 
      if(!angular.isUndefined(codigoBarrio)&&(codigoBarrio!=null)){
        var comuna = '';
        angular.forEach($scope.lista_comunas_cali,function(value,key){
          if(value.codigo == codigoBarrio.comuna){
            comuna = value.nombre;
          }
        });
        var estrato = '';
        angular.forEach($scope.lista_estratos_cali,function(value,key){
          if(value.codigo == codigoBarrio.estrato){
            estrato = value.nombre;
          }
        });
        $scope.clasificacionBarrios.codigoZona = {'codigo':codigoBarrio.comuna,'nombre':comuna};
        $scope.clasificacionBarrios.codigoEstrato = {'codigo':codigoBarrio.estrato,'estrato':estrato};
        //console.log($scope.inmueble);
      }     
      
    }

    $scope.getLocEstCali = function(codigoEstrato){

        
      $scope.lista_comunas_cali = $scope.lista_comunas_cali_ini;
      $scope.barrios_comunas_cali = $scope.barrios_comunas_cali_ini;  
      $scope.inmueble.codigoZona = undefined;

      if(!angular.isUndefined(codigoEstrato) && (codigoEstrato!=null)){
        $scope.cambiaComunaInmueble = '0';
        var estrato = codigoEstrato.codigo;
        var lista_comunas_cali1 = [];
        var lista_comunas_barrios_cali1 = [];
        var i = 0;
        var j = 0;

        angular.forEach($scope.lista_comunas_cali, function(value,key){
          if(value.estrato==estrato){
           lista_comunas_cali1[i] = value;
            i++; 
          }
        });

        angular.forEach($scope.barrios_comunas_cali, function(value,key){
          if(value.estrato==estrato){
            lista_comunas_barrios_cali1[j] = value;
            j++; 
          }
        });

        if(i!=0){
          $scope.lista_comunas_cali = lista_comunas_cali1;
        }

        if(j!=0){
          $scope.barrios_comunas_cali = lista_comunas_barrios_cali1;
        } 

      }      
      else{
        $scope.cambiaComunaInmueble = '1';
        $scope.inmueble.codigoZona = undefined;
        $scope.inmueble.codigoBarrio = {};
      }     
      
    }



    $scope.getBarriosLocCali = function(codigoLocalidad){    

      if(codigoLocalidad != null){
        $scope.barrios_comunas_cali = $scope.barrios_comunas_cali_ini;        
        var localidad = codigoLocalidad.codigo;      
        var barrios_comunas_cali1 = [];
        var i = 0;
      
        angular.forEach($scope.barrios_comunas_cali, function(value,key){
          if(value.comuna==localidad){
            barrios_comunas_cali1[i] = value;            
            i++; 
          }
        });      
        if(i!=0){
          $scope.barrios_comunas_cali  = barrios_comunas_cali1;
        }
      }            

    }

    $scope.consultarInmueblesCaracteristicas = function(){
      
      var caracter = [];
      var caracter_opc = [];
      var i=0;

      for (var key in $scope.caracteristicas_inmueble_busq.caracteristica) {            
        caracter[i]=$scope.caracteristicas_inmueble_busq.caracteristica[key];        
        i++;
      }
      i=0; 
      for (var key in $scope.caracteristicas_inmueble_busq.caracteristica_opc) {  
        caracter_opc[i]=$scope.caracteristicas_inmueble_busq.caracteristica_opc[key];
        i++;
      }

      var inmueble = $scope.inmueble;  

      if(angular.isUndefined(inmueble.codigoPais)){
      	alert('Debe seleccionar el país en donde se busca el inmueble');
      	return false;
      }
      else if(angular.isUndefined(inmueble.codigoDepartamento)){
      	alert('Debe seleccionar el departamento, estado o región en donde se busca el inmueble');
        return false;
      }
      else if(angular.isUndefined(inmueble.codigoCiudad)){
      	alert('Debe seleccionar la ciudad o municipio en donde se busca el inmueble');
        return false;
      }
      else if(angular.isUndefined(inmueble.tipoInmueble)){
      	alert('Debe seleccionar el tipo de inmueble a consultar');
        return false;
      }
      else{      	
        var inmuebleCons = {};
        inmuebleCons.codigoPais = inmueble.codigoPais;   
        inmuebleCons.codigoDepartamento = inmueble.codigoDepartamento;
        inmuebleCons.codigoCiudad = inmueble.codigoCiudad;
        inmuebleCons.tipoInmueble = inmueble.tipoInmueble;

        if(angular.isUndefined(inmueble.tipoInmueble.codigoSector)){
          inmuebleCons.codigoSector = inmueble.codigoSector;
        }  

        if(angular.isUndefined(inmueble.tipoInmueble.codigoZona)){
          inmuebleCons.codigoZona = inmueble.codigoZona;
        } 

        if(angular.isUndefined(inmueble.tipoInmueble.precio)){
          inmuebleCons.precio = inmueble.precio;
        }

        if(angular.isUndefined(inmueble.tipoInmueble.moneda)){
          inmuebleCons.moneda = inmueble.moneda;
        } 

        console.log(inmuebleCons);
        $http.post($scope.endpoint,{'accion':'consultarInmuebles','datosInmueble':inmuebleCons,'caracteristicas':caracter,'caracteristicas_opcionales':caracter_opc})
        .then(function(response){ 
          $scope.ids_tipo_caracteristicas_inmuebles=response.data;        
        });	
      }
      

    }

    $scope.mostrarCaracteristicasAdicionales = function(caract_adic){   

      if(caract_adic){
        $scope.cant_caract_inm_obl_show='1';

      }
      else{
        $scope.cant_caract_inm_obl_show='0';
        $scope.caracteristicas_inmueble_busq.caracteristica_opc=[];        
      }
    }

    $scope.getCaracteristicasTiposInmuebles = function(id_tipo_inmueble,tipo_inmueble){ 
      $http.post($scope.endpoint,{'accion':'caracteristicasTiposInmuebles','id_tipo_inmueble':id_tipo_inmueble})
      .then(function(response){                 
        $scope.cant_caract_inm=response.data.caracteristicasInmueble.cantidad; 
        if($scope.cant_caract_inm=='0'){ 
          $scope.cant_caract_inm_opc='0';
          $scope.cant_caract_inm_obl='0';
          $scope.cant_caract_inm_obl_show='0';          
          $scope.inmueble.caracteristicas_adicionales=false;
          $scope.caracteristicas_inmueble_busq = [];
          $scope.lista_caracteristicas_inmueble=[];  
          $scope.lista_caracteristicas_obligatorias_inmueble=[];  
          $scope.lista_caracteristicas_opcionales_inmueble=[];     
          $scope.getTiposCaracteristicasInm();
        }
        else{
          //console.log(response.data);
          $scope.cant_caract_inm_opc=response.data.caracteristicasInmueble.cantidad_opc;
          $scope.cant_caract_inm_obl=response.data.caracteristicasInmueble.cantidad_obl; 
          $scope.cant_caract_inm_obl_show='0';  
          $scope.inmueble.caracteristicas_adicionales=false;   
          $scope.caracteristicas_inmueble_busq = [];     
          $scope.lista_caracteristicas_inmueble=response.data.caracteristicasInmueble.lista_caracteristicas_inmueble; 
          $scope.lista_caracteristicas_opcionales_inmueble=response.data.caracteristicasOpcionalesInmueble.lista_caracteristicas_inmueble; 
          $scope.lista_caracteristicas_obligatorias_inmueble=response.data.caracteristicasObligatoriasInmueble.lista_caracteristicas_inmueble; 
          $scope.ids_tipo_caracteristicasInm=response.data.caracteristicasRestantes;          

        } 

        //console.log($scope.lista_caracteristicas_inmueble);                  
        
      });

    } 

    $scope.borrarCaracteristicaTipoInmueble = function(id_caracteristicas_tipo_inmueble){
      if(confirm("Desea borrar la característica para este tipo de inmueble?")){
        $http.post($scope.endpoint,{'accion':'borrarCaracteristicasInmuebles','id_caracteristicas_tipo_inmueble':id_caracteristicas_tipo_inmueble})
        .then(function(response){               
          alert("Se ha borrado la característica con éxito");
          $scope.limpiarCaracteristicasTipoInmueble();
        });
      }      
    }

    $scope.verSolicitudesCliente = function(id_cliente){
      alert(id_cliente);
    }

    $scope.limpiarCaracteristicasTipoInmueble = function(){

      $scope.caracteristicasInmuebles=[];
      $scope.lista_caracteristicas_inmueble=[];
      $scope.lista_caracteristicas_obligatorias_inmueble=[];
      $scope.lista_caracteristicas_opcionales_inmueble=[];
      $scope.inmueble = [];
      $scope.cant_caract_inm = '0';
      $scope.cambiaComunaInmueble = '1';
      $scope.cant_caract_inm_opc = '0';
      $scope.cant_caract_inm_obl = '0';     
      $scope.inmueble.codigoPais = {id_pais:'1',name:'Colombia'}; 
      $scope.inmueble.moneda = {id_moneda:'1',denominacion:'COP - Pesos Colombianos'}; 
      $scope.getDepartamentosCliente('1');
      $scope.inmueble.codigoDepartamento = {id_departamento:'32',name:'Valle del Cauca'}; 
      $scope.getCiudadesCliente('32');
      $scope.getTiposCaracteristicasInm();
      //$scope.getTiposCaracteristicasInm1();
    }    

    //Limpiar el formulario de clasificación del barrio. Deja por defecto valores de Colombia
    $scope.limpiarClasificacionBarrios = function(){

      $scope.clasificacionBarrios=[];      
      $scope.clasificacionBarrios.codigoPais = {id_pais:'1',name:'Colombia'};      
      $scope.getDepartamentosCliente('1');
      $scope.clasificacionBarrios.codigoDepartamento = {id_departamento:'32',name:'Valle del Cauca'}; 
      $scope.getCiudadesCliente('32');
      $scope.cant_barrios_zona = '0';
      $scope.lista_barrios_zonas = [];
      
    } 

    /*Valida el formulario de clasificación del barrio indicando si hay algún valor vacío, de lo contrario 
      se realiza el guardado de la misma.
    */
    $scope.guardarClasificacionBarrio = function(){
      if(angular.isUndefined($scope.clasificacionBarrios.codigoPais)){
        alert("Debe seleccionar el país");
      }
      else if(angular.isUndefined($scope.clasificacionBarrios.codigoDepartamento)){
        alert("Debe seleccionar el departamento");
      }
      else if(angular.isUndefined($scope.clasificacionBarrios.codigoCiudad)){
        alert("Debe seleccionar la ciudad");
      }
      else if(angular.isUndefined($scope.clasificacionBarrios.codigoSector)){
        alert("Debe seleccionar la zona o sector");
      }
      else if((angular.isUndefined($scope.clasificacionBarrios.codigoBarrio))||$scope.clasificacionBarrios.codigoBarrio==''){
        alert("Debe seleccionar el barrio");
      }      
      else{        
        $http.post($scope.endpoint,{'accion':'guardarClasificacionBarrio', 'clasificacion':
                                    {
                                      'id_barrio':$scope.clasificacionBarrios.codigoBarrio.codigo,
                                      'id_ciudad':$scope.clasificacionBarrios.codigoCiudad.id_ciudad,
                                      'id_sector':$scope.clasificacionBarrios.codigoSector.id_sector
                                    }                                      
                                  })
        .then(function(response){ 
          if(response.data.respuesta=='1'){
            alert("Se ha relacionado el barrio a la zona correctamente.");
            /*$scope.listarBarriosZona();
            $scope.clasificacionBarrios.codigoZona = {}; 
            $scope.clasificacionBarrios.codigoEstrato= {};*/           
            $scope.listarBarriosZona($scope.clasificacionBarrios.codigoSector.id_sector,$scope.clasificacionBarrios.codigoCiudad.id_ciudad);
          }
          else{
            alert("No se ha podido relacionar la zona, por favor verifique.");
          }                
        });  
      }
    }

    $scope.consultarAsesores = function(){ 

      var numero_identificacion=''; 
      var nombres=''; 
      var apellidos='';         
      //console.log($scope.asesor_busq);
      if(!angular.isUndefined($scope.asesor_busq)){
        if((!angular.isUndefined($scope.asesor_busq.numero_identificacion)) && $scope.asesor_busq.numero_identificacion!=null)
          numero_identificacion=$scope.asesor_busq.numero_identificacion;
        if((!angular.isUndefined($scope.asesor_busq.nombres)) && $scope.asesor_busq.nombres!='')
          nombres=$scope.asesor_busq.nombres;
        if((!angular.isUndefined($scope.asesor_busq.apellidos)) && $scope.asesor_busq.apellidos!='')
          apellidos=$scope.asesor_busq.apellidos;        
      } 

      $http.post($scope.endpoint,{'accion':'consultarAsesores','asesorBusq':{'numero_identificacion':numero_identificacion,'nombres':nombres,'apellidos':apellidos}})
      .then(function(response){ 
          
        if(response.data.cant=='0'){          
            alert("No se encontraron asesores con esos datos"); 
        }
        else{
          $scope.lista_asesores_busq=response.data.lista_asesores;
          $scope.cant_ases_busq = '0';
        }     
        
      });
      
    }

    $scope.volverBusqAsesores = function(){
      $scope.lista_asesores_busq = [];
      $scope.cant_ases_busq = '1';
    }

    $scope.seleccionarCaracteristicaInmueble = function(id_caracteristicas_tipo_inmueble){      
      $http.post($scope.endpoint,{'accion':'seleccionarCaracteristicaInmueble','id_caracteristicas_tipo_inmueble':id_caracteristicas_tipo_inmueble})
      .then(function(response){         
        $scope.caracteristicasInmuebles=response.data;   
        console.log($scope.caracteristicasInmuebles);        
      });
    }

    $scope.closeSession = function(){            
      if(confirm("Desea cerrar la sesion?")){
      	$http.post($scope.endpoint,{'accion':'closeSession'})
        .then(function(response){             	
          $scope.showContent = '../login/formularioLogin.html';
        });
      }
    }

    $scope.resetPassword = function(password_act,password_new,password_new1){ 

      if(angular.isUndefined(password_act) || angular.isUndefined(password_new) || angular.isUndefined(password_new1)){
        alert('Debe ingresar la contraseña actual, la contraseña nueva y la confirmación de la contraseña');
      }
      else if(password_new != password_new1){
        alert('La contraseña nueva y la confirmación deben coincidir');
      }
      else if(password_new.length < 8 || password_new.length > 13){
        alert('La contraseña debe tener entre ocho (8) y trece (13) caracteres');
      }
      else{
      	$http.post($scope.endpoint,{'accion':'resetPassword','password_act':password_act,'password_new':password_new})
        .then(function(response){             	
          if(response.data.respuesta=='1'){  
            alert('Se ha reestablecido la contraseña con éxito');        	
          	$scope.showContent = '../login/formularioLogin.html';
          }
          else{
          	alert('Datos incorrectos, por favor verifique que la contraseña actual sea la correspondiente');
          }
        });
      }

      $scope.resetPasswordForm.password_act=null;
      $scope.resetPasswordForm.password_new=null;
      $scope.resetPasswordForm.password_new1=null;
    
    }     
    
  }  

);

app.controller("siniController1",

  function ($scope,$http){   	

  	$scope.pages = [];
  	$scope.currentPage = 0;
    $scope.pageSize = 10;
    $scope.lista_clientes_busq = [];       
    $scope.cant_clie_busq = '1';      

    $scope.consultarClientes = function(){ 

      var numero_identificacion=''; 
      var nombres=''; 
      var apellidos='';
      var telefono='';    

      if(!angular.isUndefined($scope.cliente_busq)){
        if((!angular.isUndefined($scope.cliente_busq.numero_identificacion)) && $scope.cliente_busq.numero_identificacion!=null)
        	numero_identificacion=$scope.cliente_busq.numero_identificacion;
        if((!angular.isUndefined($scope.cliente_busq.nombres)) && $scope.cliente_busq.nombres!='')
        	nombres=$scope.cliente_busq.nombres;
        if((!angular.isUndefined($scope.cliente_busq.apellidos)) && $scope.cliente_busq.apellidos!='')
        	apellidos=$scope.cliente_busq.apellidos;
        if((!angular.isUndefined($scope.cliente_busq.telefono)) && $scope.cliente_busq.telefono!='')
          telefono=$scope.cliente_busq.telefono;
      } 

      $http.post($scope.endpoint,{'accion':'consultarClientes','clienteBusq':{'numero_identificacion':numero_identificacion,'nombres':nombres,'apellidos':apellidos,'telefono':telefono}})
      .then(function(response){ 
          
        if(response.data.cant=='0'){        	
            alert("No se encontraron clientes con esos datos"); 
        }
        else{
        	$scope.lista_clientes_busq=response.data.lista_clientes;
        	$scope.cant_clie_busq = '0';
        }   	
        
      });
      
    }

    $scope.volverBusqClientes = function(){
    	$scope.lista_clientes_busq = [];
    	$scope.cant_clie_busq = '1';
    }    

    $scope.limpiarClientesBusq = function(){
      $scope.cliente_busq = [];
    }

    $scope.limpiarClientes = function(){      
      $scope.cliente.id_cliente=undefined;
      $scope.cliente.nombres=undefined;
      $scope.cliente.apellidos=undefined;
      $scope.cliente.tipoIdentificacion=undefined;
      $scope.cliente.numeroIdentificacion=undefined;
      $scope.cliente.tipoCliente=undefined;
      $scope.cliente.tipoNotificacion=undefined;
      $scope.cliente.telefono_movil=undefined;
      $scope.cliente.telefono_fijo=undefined;
      $scope.cliente.direccion=undefined;
      $scope.cliente.correo_electronico=undefined;
      $scope.cliente.codigoCiudad=undefined;
      $scope.cliente.codigoDepartamento=undefined;
      $scope.cliente.codigoPais.id_pais='1';      
      $scope.ids_ciudades = [];  
      $scope.getDepartamentosCliente('1');
    }   


    $scope.configPages = function() {
        $scope.pages.length = 0;
        var ini = $scope.currentPage - 4;
        var fin = $scope.currentPage + 5;
        if (ini < 1) {
          ini = 1;
          if (Math.ceil($scope.lista_clientes_busq.length / $scope.pageSize) > 10)
            fin = 10;
          else
            fin = Math.ceil($scope.lista_clientes_busq.length / $scope.pageSize);
        } else {
          if (ini >= Math.ceil($scope.lista_clientes_busq.length / $scope.pageSize) - 10) {
            ini = Math.ceil($scope.lista_clientes_busq.length / $scope.pageSize) - 10;
            fin = Math.ceil($scope.lista_clientes_busq.length / $scope.pageSize);
          }
        }
        if (ini < 1) ini = 1;
        for (var i = ini; i <= fin; i++) {
          $scope.pages.push({
            no: i
          });
        }

        if ($scope.currentPage >= $scope.pages.length)
          $scope.currentPage = $scope.pages.length - 1;
    };

    $scope.setPage = function(index) {
        $scope.currentPage = index - 1;
    };


    $scope.saveClientes = function(action){   
       
      if(angular.isUndefined($scope.cliente.tipoCliente)||
      	 angular.isUndefined($scope.cliente.nombres)||
      	 angular.isUndefined($scope.cliente.apellidos)||
         angular.isUndefined($scope.cliente.tipoIdentificacion)||
         angular.isUndefined($scope.cliente.numeroIdentificacion)||
         angular.isUndefined($scope.cliente.tipoNotificacion)
      	){
        alert("Todos los campos con (*) son requeridos");
      }     
      else if(angular.isUndefined($scope.cliente.telefono_movil)&&
              angular.isUndefined($scope.cliente.telefono_fijo)){
        alert("Debe ingresar un número de teléfono, ya sea fijo o móvil");
      }  
      else if($scope.cliente.tipoNotificacion.id_tipo_notificacion=='1' && angular.isUndefined($scope.cliente.correo_electronico)){
        alert("Debe ingresar un correo electrónico para este tipo de notificación");
      }
      else if($scope.cliente.tipoNotificacion.id_tipo_notificacion=='2' && angular.isUndefined($scope.cliente.telefono_movil)){
        alert("Debe ingresar un número de teléfono móvil para este tipo de notificación");
      }
      else if($scope.cliente.tipoNotificacion.id_tipo_notificacion=='3' && 
      	      (angular.isUndefined($scope.cliente.telefono_movil)||angular.isUndefined($scope.cliente.correo_electronico))){
        alert("Debe ingresar un teléfono móvil y un correo para este tipo de notificación");
      }      
      else{

        if(angular.isUndefined($scope.cliente.codigoCiudad))
        	var id_ciudad=null;
        else
        	var id_ciudad=$scope.cliente.codigoCiudad.id_ciudad;

        if(angular.isUndefined($scope.cliente.codigoDepartamento))
        	var id_departamento=null;
        else
        	var id_departamento=$scope.cliente.codigoDepartamento.id_departamento;

        if(angular.isUndefined($scope.cliente.codigoPais))
        	var id_pais=null;
        else
        	var id_pais=$scope.cliente.codigoPais.id_pais;

        $http.post($scope.endpoint,
        	{'accion':'guardarClientes',
        	 'datosCliente':
        	   {
        	   	 'id_cliente':$scope.cliente.id_cliente,
        	   	 'tipoCliente':$scope.cliente.tipoCliente.id_tipo_cliente,
        	   	 'nombres':$scope.cliente.nombres,
        	   	 'apellidos':$scope.cliente.apellidos,
        	   	 'tipoIdentificacion':$scope.cliente.tipoIdentificacion.id_tipo_identificacion,
        	   	 'numeroIdentificacion':$scope.cliente.numeroIdentificacion,
        	   	 'tipoNotificacion':$scope.cliente.tipoNotificacion.id_tipo_notificacion,
        	   	 'telefono_movil':$scope.cliente.telefono_movil,
        	   	 'telefono_fijo':$scope.cliente.telefono_fijo,
                 'correo_electronico':$scope.cliente.correo_electronico,
                 'direccion':$scope.cliente.direccion,
                 'id_ciudad':id_ciudad,
                 'id_departamento':id_departamento,
                 'id_pais':id_pais
        	   }
            }
        )
        .then(function(response){          
          if(response.data.respuesta=='1' && action=='1'){ 
          	$scope.cliente.id_cliente=response.data.id_cliente;
            alert('Cliente almacenado exitosamente');                	
          	
          }
          else if(response.data.respuesta=='2' && action=='1'){
          	alert('Ya existe un cliente con ese número de documento, por favor verifique');
            
          }
          else if(response.data.respuesta=='3' && action=='1'){
          	$scope.cliente.id_cliente=response.data.id_cliente;
          	alert('Cliente actualizado exitosamente');      
            
          }
          else if(response.data.respuesta=='4' && action=='1'){
            alert('No se ha podido actualizar el cliente, por favor verifique');            
          }
          if(action=='2'){
          	if(response.data.respuesta=='1'||response.data.respuesta=='3'){
          	  $scope.cliente.id_cliente=response.data.id_cliente;	
              alert('Se procede a registrar la solicitud');
          	}            
          }
          else if(action=='3'){
          	if(response.data.respuesta=='1'||response.data.respuesta=='3'){
          	  $scope.cliente.id_cliente=response.data.id_cliente;	
              alert('Se procede a registrar el inmueble');
          	}            
          }
        })
        .catch(function(data){
          alert(response.data);

        });
      }     

    }

  }

).filter('startFromGrid', function() {
  return function(input, start) {
    start = +start;
    return input.slice(start);
  }
});