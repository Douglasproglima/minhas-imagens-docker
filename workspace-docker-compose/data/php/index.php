<html>
<head>
  <title>Exemplo PHP</title>
</head>
  <?php
    ini_set("display_errors", 1);
    header('Content-Type: text/html; charset=iso-8859-1');

    echo 
      '<strong>Versao Atual do PHP:</strong> ' 
      . phpversion() . 
      '<br><hr><br>';

    $servername = "db";
    $username = "root";
    $password = "Senha123";
    $database = "testedb";

    // Criar conexão
    $link = new mysqli($servername, $username, $password, $database);

    /* check connection */
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }

    $query = "SELECT * FROM emails";

    if ($result = mysqli_query($link, $query)) {
        while ($row = mysqli_fetch_assoc($result)) {
            printf (
              "<strong>ID</strong>: %s <br> 
              <strong>Assunto: </strong> %s <br> 
              <strong>Mensagem: </strong> %s <br>", 
              $row["id"], $row["content"], $row["body"]
            );
        }

        mysqli_free_result($result);
    }

    mysqli_close($link);
  ?>
</html>