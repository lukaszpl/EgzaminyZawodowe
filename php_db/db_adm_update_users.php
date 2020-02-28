<?php
require '../php_db/db_connect.php'; 
switch($_POST['action'] ){ 
    case "chmod":
       $sql = "UPDATE users SET canSolve=".$_POST['newchmod']." WHERE ".$_POST['where'];
        $result = $connect->query($sql);
        $info="Zrobione!";

        break;
    case "reclass":
       $sql = "UPDATE users SET class=\"".$_POST['newreclass']."\" WHERE ".$_POST['where'];
        $result = $connect->query($sql);
        $info="Zrobione!";

        break;
    case "inclass":
       $sql = "UPDATE users SET class=\"".$_POST['newinclass']."\" WHERE ".$_POST['where'];
        $result = $connect->query($sql);
        $info="Zrobione!";

        break;
    case "remove":
        if($_POST['newremove']){
            $sql = "DELETE FROM history WHERE ".$_POST['where'];
            $result = $connect->query($sql);
            
            $sql = "DELETE FROM users WHERE ".$_POST['where'];
            $result = $connect->query($sql);
            
            $info="Zrobione!";
        }else{
            $info="Nie usuwam bo nie jesteś pewien";
        }

        break;
    default:
        
        $info="Nie wiem co mam zrobić";
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        

}
echo $info;
echo "<script>window.location=\"../adm/index.php?class=".$_POST['className']."&info=".$info."\"</script>";
