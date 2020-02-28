<?php
require '../php_db/db_connect.php'; 
if(true){ 
    if($_POST['className'] == null){ 
        $info="Klasa musi mieć nazwę!"; 
    } elseif($_POST['userCode0'] == null||$_POST['Name0'] == null||$_POST['LastName0'] == null){
        $info="Tabela musi zawierać co najmniej jednego ucznia!"; 
    }else{
        $i=0; 
        while(!($_POST['userCode'.$i] == null||$_POST['Name'.$i] == null||$_POST['LastName'.$i] == null)){
            $sql = "INSERT INTO users(userCode, Name, LastName, class, canSolve) VALUES (\"".$_POST['userCode'.$i]."\", \"".$_POST['Name'.$i]."\", \"".$_POST['LastName'.$i]."\", \"".$_POST['className']."\", 0)"; 
            $result = $connect->query($sql); $i++; 
        } 
        $info="Dodano ".$i." uczniów!";
    }
}else{
    $info="Nie wiem co mam zrobić";
}
echo $info;
echo "<script>window.location=\"../adm/index.php?class=".$_POST['className']."&info=".$info."\"</script>";
?>
