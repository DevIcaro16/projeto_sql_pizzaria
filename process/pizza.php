<?php

include_once("conn.php");

$method = $_SERVER['REQUEST_METHOD'];

//Resgate de dados / montagem do pedido.
if($method === "GET"){

    $bordasSQL = "SELECT * FROM bordas;";

    $bordasQuery = $conn->query($bordasSQL);

    $bordas = $bordasQuery->fetchAll();

    $massasSQL = "SELECT * FROM massas;";

    $massasQuery = $conn->query($massasSQL);

    $massas = $massasQuery->fetchAll();

    $saboresSQL = "SELECT * FROM sabores;";

    $saboresQuery = $conn->query($saboresSQL);

    $sabores = $saboresQuery->fetchAll();

    // print_r($sabores);

//Montagem / Criação do pedido.
}else if($method === "POST"){

    $borda = $_POST['borda'];
    $massa = $_POST['massa'];
    $sabores = $_POST['sabores'];

    

    //Validar quantidade máxima de sabores:
    

    if(!isset($sabores) || !is_array($sabores) || empty($sabores)){

        $_SESSION['txt_msg'] = "Selecione pelo menos 1 sabor!";
        $_SESSION['tipo_msg'] = "warning";

        //Para continuar na tela inical (index.php):

        header("Location: ../php/index.php");
        exit;

    }else if($borda === ""){

        $_SESSION['txt_msg'] = "Selecione o tipo da borda!";
        $_SESSION['tipo_msg'] = "warning";

        //Para continuar na tela inical (index.php):

        header("Location: ../php/index.php");
        exit;

    }else if($massa === ""){

        $_SESSION['txt_msg'] = "Selecione o tipo da massa!";
        $_SESSION['tipo_msg'] = "warning";

        //Para continuar na tela inical (index.php):

        header("Location: ../php/index.php");
        exit;

    }else if (count($sabores) > 3) {

        $_SESSION['txt_msg'] = "Selecione no máximo 3 sabores!";
        $_SESSION['tipo_msg'] = "warning";

        //Para continuar na tela inical (index.php):

        header("Location: ../php/index.php");
        exit;

    }else {
    //    echo "Faz o pedido direito!";
    //    exit;

    //Salvando os dados do pedido:
    $sqlPizzas = "INSERT INTO pizzas(borda_id,massa_id) VALUES (:borda, :massa);";
    $queryInserirPizzas = $conn->prepare($sqlPizzas);

    //Filtração dos inputs (apenas valores inteiros): 

    $queryInserirPizzas->bindParam(":borda", $borda, PDO::PARAM_INT);
    $queryInserirPizzas->bindParam(":massa", $massa, PDO::PARAM_INT);

    //Executar a query.
    $queryInserirPizzas->execute(); 

    //Resgatando último id da última pizza:

    $pizzaId = $conn->lastInsertId();

    $sqlPizzaId = "INSERT INTO pizza_sabor(pizza_id, sabor_id) VALUES (:pizza, :sabor);";

    $queryPizzaId = $conn->prepare($sqlPizzaId);

    foreach ($sabores as $sabor) {
        $queryPizzaId->bindParam(":pizza", $pizzaId, PDO::PARAM_INT);
        $queryPizzaId->bindParam(":sabor", $sabor, PDO::PARAM_INT);

        $queryPizzaId->execute();
    }

    //Criar / Salvar  o pedido da pizza:

    $sqlPedido = "INSERT INTO pedidos(pizza_id, status_id) VALUES (:pizza, :status);";
    
    $queryInserirPedido = $conn->prepare($sqlPedido);

    //OBS: O status do pedido sempre inicia com o valor 1 (Em produção).

    $statusId = 1;

    //Filtração dos inputs.

    $queryInserirPedido->bindParam(":pizza", $pizzaId);
    $queryInserirPedido->bindParam(":status", $statusId);

    $queryInserirPedido->execute();

    //Pedido feito, exibir msg de sucesso:

    $_SESSION['txt_msg'] = "Pedido criado com sucesso!";
    $_SESSION['tipo_msg'] = "success";


    }

    //Retornar o user para a página inicial(index.php):

    // header("Location: ../php/index.php");

    //Retornar o user para a página de gerenciar pedidos (dashboard.php):

    header("Location: ../php/dashboard.php");
}

?>