<?php

    require_once 'vendor/autoload.php';

    $app = new \Slim\Slim();

    //Conectamos la BD ("localizacion","nombre","clave","nombre tabla")
    $db = new mysqli("localhost","root","","pruebas");

    //ruta get para listar el contenido de la tabla producto
    //devuelve un json
    $app->get("/productos",function() use ($db, $app){
        $query = $db->query("SELECT * FROM productos;");
        $productos = array();
        while($fila=$query->fetch_assoc()){
            $productos[]=$fila;
        }
        echo json_encode($productos);
    });

    //ruta post para insertar productos

    $app->post("/productos",function() use ($db, $app){
        $query = "INSERT INTO productos VALUES(NULL,"
        . "'{$app->request->post("name")}',"
        . "'{$app->request->post("description")}',"
        . "'{$app->request->post("price")}'"
        . ")";
        $insert = $db->query($query);

        if($insert){
            $result = array("STATUS" => "true", "message" => "Producto creado correctamente!");
        }else{
            $result = array("STATUS" => "false", "message" => "ERROR: Producto no creado");
        }

        echo json_encode($result);
    });

    //ruta put para actualizar registros
    $app->put("/productos/:id",function($id) use ($db, $app){
        $query="UPDATE productos SET "
        . "name = '{$app->request->post("name")}', "
        . "description = '{$app->request->post("description")}', "
        . "price = '{$app->request->post("price")}'"
        . " WHERE id={$id}";

        $update = $db->query($query);

        if($update){
            $result = array("STATUS" => "true", "message" => "Producto se ha modificado correctamente!");
        }else{
            $result = array("STATUS" => "false", "message" => "ERROR: Producto no modificado");
        }

        echo json_encode($result);
    });

    //ruta delete para eliminar registros de la BD
    $app->delete("/productos/:id",function($id) use ($db, $app){
        $query="DELETE FROM productos WHERE id = {$id}";

        $delete = $db->query($query);

        if($delete){
            $result = array("STATUS" => "true", "message" => "Producto se ha eliminado correctamente!");
        }else{
            $result = array("STATUS" => "false", "message" => "ERROR: Producto no eliminado");
        }

        echo json_encode($result);
    });



    $app->run();