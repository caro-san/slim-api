<?php

    require_once 'vendor/autoload.php';

    $app = new \Slim\Slim();

    $app -> get("/hola/:nombre",function($nombre){
        echo "Hola ".$nombre;
    });

    function pruebaMiddle1(){
        echo "Soy el primer middleware";
    }

    function pruebaMiddle2(){
        echo "Soy el segundo middleware";
    }

    //Todos los parametros declarados en la ruta, son obligatorios, de lo contario tira error
    $app -> get("/prueba1/:uno/:dos",function($uno,$dos){
        echo "Hola ".$uno."<br>";
        echo "Hola ".$dos."<br>";
    });

    //Para hacer un parametro opcional, se encierra en parentesis y en la funcion se espeficica el NULL
    $app -> get("/prueba2(/:uno(/:dos))",function($uno=NULL,$dos=NULL){
        echo "Hola ".$uno."<br>";
        echo "Hola ".$dos."<br>";
    });

    //Para incluir condiciones las cuales son obligatorias, se usan expresiones regulares, se usan las que sean necesarias
    //El * al final de la expesion regular, es para poder incluir tantos valores o numeros somo uno desee
    $app -> get("/prueba3(/:uno(/:dos))",function($uno=NULL,$dos=NULL){
        echo "Hola ".$uno."<br>";
        echo "Hola ".$dos."<br>";
    })->conditions(array(
        "uno"=>"[a-zA-Z]*",
        "dos"=>"[0-9]*"
    ));

    //Ruta con Middleware incluida que se ejecuta antes que la funcion.
    $app -> get("/prueba4(/:uno(/:dos))", 'pruebaMiddle1', 'pruebaMiddle2' ,function($uno=NULL,$dos=NULL){
        echo "Hola ".$uno."<br>";
        echo "Hola ".$dos."<br>";
    })->conditions(array(
        "uno"=>"[a-zA-Z]*",
        "dos"=>"[0-9]*"
    ));

    //Variable creada para el redireccionamiento,
    //se especifica en /api,/ejemplo y en /mandame-a-hola, si no, tira error
    //Este problema se soluciona con URL For
    $urr="/slim/index.php/api/ejemplo/";
    //Un Grupo de ruta es una ruta que anida otra ruta y en la URL se van enlazando
    $app->group("/api", function() use ($app,$urr){
        $app->group("/ejemplo", function() use ($app,$urr){
            $app->get("/hola/:nombre", function($nombre){
                echo "Hola ".$nombre;
            });

            $app->get("/dime-tu-apellido/:apellido", function($apellido){
                echo "Apellido: ".$apellido;
            });

            //Redirecciones, hay que especificar que se usara $app o tira error
            $app->get("/mandame-a-hola", function() use ($app,$urr){
                $app -> redirect($urr."hola/Vengo desde mandame a hola");
            });
        });
    });

    //URL For
    $app->group("/api", function() use ($app,$urr){
        $app->group("/ejemplo", function() use ($app,$urr){
            $app->get("/hola/:nombre", function($nombre){
                echo "Hola ".$nombre;
            })->name("hola");

            $app->get("/dime-tu-apellido/:apellido", function($apellido){
                echo "Apellido: ".$apellido;
            });

            //Redirecciones, hay que especificar que se usara $app o tira error
            $app->get("/redireccion-a-hola", function() use ($app,$urr){
                $app -> redirect($app->urlFor("hola", array(
                    "nombre" => "Vengo desde redireccion a hola"
                )));
            });
        });
    });

    //Pasando datos por get

    $app -> get("/saludo1/:nombre",function($nombre) use ($app){
        echo "Hola ".$nombre;
        var_dump($app->request->params());
    });

    //especificando dato en el parametro = params()
    $app -> get("/saludo2/:nombre",function($nombre) use ($app){
        echo "Hola " . $nombre . "<br>";
        var_dump($app->request->params("hola"));
    });

    



    $app -> run();

?>