<?php
if (isset($_GET['result'])) {
    //recibo la variable result que viene por el meto get (por la url)
    $cantidad = $_GET['result'];
    //incluyo la conexion a la base de datos y en la linea 6 llamo la funcion Conectar y le paso la conexion
    include_once ("../conexion/conex.php");
    $conectar = Conectar();
    //declaro un array el cual va a ser el principal lo declaro como atributo entre corchetes results
    $array_principal['results']=array();
    //preparo la consulta para que me traiga la cantidad de usuarios solicitados por url
    $consultas = $conectar->prepare("SELECT * FROM empleados LIMIT $cantidad");
    //ejecuto la consulta
    $consultas->execute();
    //tengo un variable llamada datos con un fechtall
    $datos = $consultas->FetchAll();
    //creo un for para recorrer las informaciones que tiene el array datos
    foreach ($datos as $filas) {
        //declaro otro array el cual le voy a poner solamente el codigo, y le paso el id de la base de datos
        $array_Id = array('Codigo'=>$filas['codigo']);
        //luego creo otro array para englobar los nombres
        $array_Nombres['Nombres'] = array('Nombres' => $filas['nombre'],
                            'Apellidos' => $filas['apellido']
                        );
        //procedo a crear otro array donde voy a introduir los datos de usuario
        $array_User['Usuario']= array("User" => $filas['usuario'],
            "Password" => $filas['password']
        );
        //procedo a crear otro array con el atributo departamento el cual aparecera como categoria
        $array_Departamento['Departamento']= array('Departamento'=>$filas['departamento']);
        //dentro del foreach procedo a buscar en otra tabla la se llama direccion, la cual esta relacionada con la tabla empleados
        //realizo una consulta para obetener los datos 
        $datos_Direccion = $conectar->query("SELECT * FROM direccion WHERE empleado_id = {$filas['codigo']}");
        //aqui realizo otro foreach para recorrer los datos de la tabla direccion
        foreach ($datos_Direccion as $direccion) {
            //creo otro array para introducir los datos de la direccion 
            $array_Direccion['Direccion'] = array('Direccion'=>$direccion['calle'],
                                                    'Zona'=>$direccion['provincia']);
        }
        //creo una variable donde voy a concatenar todos los array anteriores para tenerlo en un solo array
        $arraydefinitivo = $array_Id + $array_Nombres + $array_User + $array_Departamento + $array_Direccion ;
        //procedo a introducir todos los arraydefinitivo que contenien todos los array en el array principal que es result
        array_push($array_principal['results'],$arraydefinitivo);
    }
    //imprimo por pantalla con echo y codigo el array principal en json
    echo json_encode ($array_principal);
}else{
    echo "Error";
}