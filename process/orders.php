<?php

include_once("conn.php");

$method = $_SERVER['REQUEST_METHOD'];

//Resgate de dados .
if($method === "GET"){

    $pedidosSQL = "SELECT * FROM pedidos;";

    //Não utilizando variáveis externas, utilizar o ->query() diretamente invés do ->prepare()
    $pedidosQuery = $conn->query($pedidosSQL);

    $pedidos = $pedidosQuery->fetchAll();

    $pizzas = [];

    //Montando a pizza.

    foreach ($pedidos as $pedido) {

        $pizza = [];

        //Array para cada pizza:
        $pizza["id"] = $pedido["pizza_id"];

        //Resgatando a pizza:
        $pizzaSQL = "SELECT * FROM pizzas WHERE id = :pizza_id";

        //Utilizando variáveis externas, não utilizar o ->query() diretamente, usar o ->prepare()
        $pizzaQuery = $conn->prepare($pizzaSQL);

        $pizzaQuery->bindParam(":pizza_id", $pizza["id"]);

        $pizzaQuery->execute();

        $pizzaData = $pizzaQuery->fetch(PDO::FETCH_ASSOC);



        //Resgatando as demais informações (borda, massa e sabores):



        //Resgatando a pizza:
        $bordaSQL = "SELECT * FROM bordas WHERE id = :borda_id";

        //Utilizando variáveis externas, não utilizar o ->query() diretamente, usar o ->prepare()
        $bordaQuery = $conn->prepare($bordaSQL);

        $bordaQuery->bindParam(":borda_id", $pizzaData["borda_id"]);

        $bordaQuery->execute();

        $bordaData = $bordaQuery->fetch(PDO::FETCH_ASSOC);

        $pizza["borda"] = $bordaData["tipo"];




        $massaSQL = "SELECT * FROM massas WHERE id = :massa_id";

        //Utilizando variáveis externas, não utilizar o ->query() diretamente, usar o ->prepare()
        $massaQuery = $conn->prepare($massaSQL);

        $massaQuery->bindParam(":massa_id", $pizzaData["massa_id"]);

        $massaQuery->execute();

        $massaData = $massaQuery->fetch(PDO::FETCH_ASSOC);

        $pizza["massa"] = $massaData["tipo"];




        $saboresSQL = "SELECT * FROM pizza_sabor WHERE pizza_id = :pizza_id";

        //Utilizando variáveis externas, não utilizar o ->query() diretamente, usar o ->prepare()
        $saboresQuery = $conn->prepare($saboresSQL);

        $saboresQuery->bindParam(":pizza_id", $pizzaData["id"]);

        $saboresQuery->execute();

        $saboresData = $saboresQuery->fetchAll(PDO::FETCH_ASSOC);

        // $pizza["sabores"] = $sabores["tipo"];
        

        //Resgatando / relacionando os sabores da pizza:

        $saboresDaPizza = [];

        $saborSQL = "SELECT * FROM sabores WHERE id = :sabor_id;";

        $saborQuery = $conn->prepare($saborSQL);

        foreach ($saboresData as $sabor) {

            $saborQuery->bindParam(":sabor_id", $sabor["sabor_id"]);

            $saborQuery->execute();

            $saborPizza = $saborQuery->fetch(PDO::FETCH_ASSOC);

            array_push($saboresDaPizza,$saborPizza["nome"]);
        }

        $pizza["sabores"] = $saboresDaPizza;


        //Adicionar o status do pedido:
        $pizza["status"] = $pedido["status_id"];

        //Adicionar todos os valores contidos no array $pizza ao outro array $pizzas:

        array_push($pizzas,$pizza);

    }

    //Resgtando os status:

    $statusSQL = "SELECT * FROM status;";

    $statusQuery = $conn->query($statusSQL);

    $statusData = $statusQuery->fetchAll();

    // print_r($pizzas);
    
}else if($method === "POST"){

    //Verificar se o form POST é de update (atualizar) ou delete (deletar):

        if($_POST['type'] === "delete"){

            $pizzaId = $_POST['id'];

            $deleteSQL = "DELETE FROM pedidos WHERE pizza_id = :pizza_id;";

            $deleteQuery = $conn->prepare($deleteSQL);

            $deleteQuery->bindParam(":pizza_id", $pizzaId, PDO::PARAM_INT);

            $deleteQuery->execute();

            $_SESSION['txt_msg'] = "Pedido removido com sucesso!";
            $_SESSION['tipo_msg'] = "success";


        }else if($_POST['type'] === "update"){

            $pizzaId = $_POST['id'];
            $statusId = $_POST['status'];

            $updateSQL = "UPDATE pedidos SET status_id = :status_id WHERE pizza_id = :pizza_id;";

            $updateQuery = $conn->prepare($updateSQL);

            $updateQuery->bindParam(":status_id", $statusId, PDO::PARAM_INT);
            $updateQuery->bindParam(":pizza_id", $pizzaId, PDO::PARAM_INT);

            $updateQuery->execute();

            $_SESSION['txt_msg'] = "Pedido atualizado com sucesso!";
            $_SESSION['tipo_msg'] = "success";
        }

        //Retornar para dashboard:

        header("Location: ../php/dashboard.php");
}

?>