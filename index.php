<?php
    //////// conexão ao banco ////////
	
    include '../../includes/bd/conn.php';
	// Alterar tudo para a classe PDO
	include '../../includes/bd/database.php';
	//require_once 'includes/bd/database.php';
    
    mssql_select_db( "TJM_ADM", $conn );
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CALCULADORA</title>

    <script src="https://kit.fontawesome.com/1df85bf381.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    <script>
        var operador = null;
        var n1;
        var n2;
        var result;
        var insert = 0;
        document.cookie = "insert="+insert;

        console.log(result);
        console.log(n1);
        console.log(n2);
     
        // ok - RECEBE NÚMEROS E ACUMULA NO DISPLAY
        function adiciona(numero){
            if(document.getElementById("visor").value === "0"){
                document.getElementById("visor").value = null;
            }
            document.getElementById("visor").value = document.getElementById("visor").value + numero;

            if(operador === null){
                n1 = parseFloat(document.getElementById("visor").value);
                console.log("digitado " + n1);
            } else {
                n2 = parseFloat(document.getElementById("visor").value);
                console.log("digitado " + n2);
            } 
        }
        
        // ok RECEBE OPERADOR
        function multiplica(){
            operador = "*";
            document.getElementById("visor").value = 0;  
        }
        function divide(){
            operador = "/";
            document.getElementById("visor").value = 0;  
        }
        function subtrai(){
            operador = "-";
            document.getElementById("visor").value = 0;  
        }
        function soma(){
            operador = "soma";
            document.getElementById("visor").value = 0;
        }

        // RESULTADO
        function resultado(){
            if(operador === "soma"){
                var result = parseFloat(n1 + n2);
            } else if (operador === "-"){
                var result = parseFloat(n1 - n2);
            } else if (operador === "*"){
                var result = parseFloat(n1 * n2);
            } else if (operador === "/"){
                var result = parseFloat(n1 / n2);
            }
            /* console.log(result); */
            document.getElementById("visor").value = result;
            
            /* console.log(operador);
            console.log(n1);
            console.log(n2); */

            document.cookie = "res="+result;
            document.cookie = "op="+operador;
            document.cookie = "n1="+n1;
            document.cookie = "n2="+n2;

            insert = 1;
            document.cookie = "insert="+insert;
        }

        // ok - APAGA CADA CARACTERE
        function apagaChar(){
            var apagaChar = document.getElementById("visor").value;
            var apagaChar2 = apagaChar.substring(0, apagaChar.length - 1);
            document.getElementById("visor").value = apagaChar2;
            if(document.getElementById("visor").value == ''){
                document.getElementById("visor").value = '0';
            }
        }

        // ok - APAGA TUDO
        function apagaTudo(){
            location.reload();
            return false;

            result = 0;
            n1 = 0; 
            n2 = 0;
            operador = null;  

            document.getElementById("visor").value = 0; 
        }
        console.log(insert);
        
    </script>
</head>

<body>

<?php

    $resultado = $_COOKIE['res'];
    $operacao = $_COOKIE['op'];
    if($operacao == "soma"){
        $operacao = "+";
    }
    $n1 = $_COOKIE['n1'];
    $n2 = $_COOKIE['n2'];
    $calculo = $n1." ".$operacao." ".$n2;
    $insert = $_COOKIE['insert'];
    /* echo "<br>".$insert." ???"; */
    
    /***********  INSERT ***********/
    if($insert != 0){
        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
        $dataHora = date('Y-m-d H:i:s');

        $sqlInsert = "INSERT INTO testeProjetoCalculo (operacao, resultado, dataHoraCadastro, status, quemCriou)
        VALUES ('".$calculo."','".$resultado."','".$dataHora."', '1', 'Conte')";
        $query_result = mssql_query($sqlInsert, $conn);
    }

    /********* DELETE ***************/ 
    if($_REQUEST['acao'] == 'excluir'){
        $id = $_REQUEST['deletar'];

        $sqlDelete = "DELETE FROM testeProjetoCalculo WHERE id=$id";
        if (mssql_query($sqlDelete, $conn)) {
            ?> <script> alert("Registro Excluído!"); </script> <?php
        } else {
            ?> <script> alert("oooops! Registro não excluído"); </script> <?php
        }

        // provocar reload pra resetar o link da pag
        // https://www.tjmrs.jus.br/testes/conte/calculadora-exercicio/index.php
    }  
    
?>

    <div class="container">

<!-- DISPLAY OK -->
        <form method="post" action="index.php">
            <div class="row justify-content-md-center">
                <div class="d-flex flex-row-reverse bd-highlight" 
                style="width:230px; height:50px; background-color:green; 
                border-radius:5px; margin:5px; margin-top:50px; 
                flex-direcition:row-reverse;">
                    <div class="p-3 bd-highlight">
                        <input type="text" name="visor" id="visor" value="0" style="background-color: transparent; border:none">
                    </div>
                </div>
            </div>

    <!-- c <- / -->
            <div class="row justify-content-md-center"> 
               
                <button type="button" class="btn btn-secondary btn-lg" 
                style="margin:5px; width:110px" name="apagatudo" onclick="apagaTudo()">C</button>
            
                <button type="button" class="btn btn-secondary btn-lg" 
                style="margin:5px; width:50px" onclick="apagaChar()">
                <i class="fas fa-arrow-left"></i></button>
                
                <button type="button" class="btn btn-secondary btn-lg" 
                style="margin:5px; width:50px" onclick="divide()">/</button>
                
              
            </div>

    <!-- 7 8 9 * -->
            <div class="row justify-content-md-center">
             
                <button type="button" class="btn btn-secondary btn-lg" 
                style="margin:5px; width:50px"  onclick="adiciona('7')">7</button>
    
                <button type="button" class="btn btn-secondary btn-lg" 
                style="margin:5px; width:50px" onclick="adiciona('8')">8</button>
    
                <button type="button" class="btn btn-secondary btn-lg" 
                style="margin:5px; width:50px" onclick="adiciona('9')">9</button>

                <button type="button" class="btn btn-secondary btn-lg" 
                style="margin:5px; width:50px" onclick="multiplica()">*</button>
               
            </div>

    <!--  4 5 6 - -->
            <div class="row justify-content-md-center">
           
                <button type="button" class="btn btn-secondary btn-lg" 
                style="margin:5px; width:50px" onclick="adiciona('4')">4</button>
        
                <button type="button" class="btn btn-secondary btn-lg" 
                style="margin:5px; width:50px" onclick="adiciona('5')">5</button>
        
                <button type="button" class="btn btn-secondary btn-lg" 
                style="margin:5px; width:50px" onclick="adiciona('6')">6</button>

                <button type="button" class="btn btn-secondary btn-lg" 
                style="margin:5px; width:50px" onclick="subtrai()">-</button>
                
            </div>

    <!--  1 2 3 + -->
            <div class="row justify-content-md-center">
               
                <button type="button" class="btn btn-secondary btn-lg" 
                style="margin:5px; width:50px" onclick="adiciona('1')">1</button>
            
                <button type="button" class="btn btn-secondary btn-lg" 
                style="margin:5px; width:50px" onclick="adiciona('2')">2</button>
            
                <button type="button" class="btn btn-secondary btn-lg" 
                style="margin:5px; width:50px" onclick="adiciona('3')">3</button>
            
                <button type="button" class="btn btn-secondary btn-lg" 
                style="margin:5px; width:50px" onclick="soma()">+</button>
                
            </div>

    <!--  0 , = -->
            <div class="row justify-content-md-center">
                
                <button type="button" class="btn btn-secondary btn-lg" 
                style="margin:5px; width:110px" onclick="adiciona('0')">0</button>
            
                <button type="button" class="btn btn-secondary btn-lg" 
                style="margin:5px; width:50px" onclick="adiciona('.')">,</button>
            
                <button type="button" name="result" class="btn btn-secondary btn-lg" 
                style="margin:5px; width:50px" onclick="resultado()">=</button>
            </div>
        </form>
    </div>
</div>

<?php
        $sql = "SELECT TOP 10 dataHoraCadastro, id, operacao, resultado 
                FROM testeProjetoCalculo 
                WHERE quemCriou = 'Conte'
                AND status=1
                ORDER BY dataHoraCadastro DESC";

        $query_result = mssql_query($sql, $conn);
        
    // EXIBE SELECT
    echo '<table class="container table">'; 
        echo '<thead>';
            echo '<tr>';
                        
                echo '<th>'.utf8_decode("OPERAÇÃO").'</th>';
                echo '<th>RESULTADO</th>';
                echo '<th>DATA/HORA</th>';
                echo '<th></th>';

            echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        while($linha = mssql_fetch_assoc($query_result)){
            $id = $linha['id'];
            $resul = $linha['resultado'];
            $dHC = $linha['dataHoraCadastro'];
            $opCalc = $linha['operacao'];
?>
            
                <tr>
                    <td scope='col'><?php echo $opCalc ?></td>
                    <th style="text-transform: uppercase" scope="col"><?php echo $resul ?></th>
                    <td scope='col'><?php echo $dHC ?></td>
                    
                    <!----- DELETE BUTTON ------------> 
                    <td>
                        <a class="btn btn-danger" href="index.php?deletar=<?php echo $id ?>&acao=excluir">
                        Excluir</a>  
                    </td>
                </tr>
        <?php } ?>
        </tbody>    
    </table>        
    

</body>
</html>

<!-- resetar o link da pag após exclusão de registro -->
<!-- elaborar possibilidade de usar mouse -->