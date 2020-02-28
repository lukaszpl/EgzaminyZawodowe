<?php 
            require '../php_db/db_connect.php'; 

            switch($_GET["who"]){
                case "everyone":
                    $sqlbegin="SELECT * FROM history WHERE 1 ";
                break;
                case "class":
                    $sqlbegin="SELECT history.*,users.class FROM history,users WHERE users.userCode=history.userCode AND class = \"".$_GET["class"]."\" ";
                break;
                case "user":
                    $sqlbegin="SELECT * FROM history WHERE userCode = \"".$_GET["user"]."\" ";
                break;
                case "where":
                    $sqlbegin="SELECT * FROM history WHERE (".$_GET["where"].") ";
                break;
            }
                
            switch($_GET["time"]){
                case "today":
                    $sqltime="AND Date > \"".date("Y-m-d")." 00:00:00\" ";
                break;
                case "period":
                    $before=$_GET["before"]?("AND Date < \"".$_GET["before"]."\" "):"";
                    $after=$_GET["after"]?("AND Date > \"".$_GET["after"]."\" "):"";
                    $sqltime=$before.$after;
                break;
            }
//        $user=$_GET["user"]?("AND history.userCode = \"".$_GET["user"]."\" "):"";
//        $userquery=$_GET["where"]?("AND (".$_GET["where"].") "):"";
//        $before=$_GET["before"]?("AND Date < \"".$_GET["before"]."\" "):"";
//        $after=$_GET["after"]?("AND Date > \"".$_GET["after"]."\" "):"";
//                
//        $sqlWithClass="SELECT history.*,users.class FROM history,users WHERE users.userCode=history.userCode AND class = \"".$_GET["class"]."\" ";
//        $sqlWithoutClass="SELECT * FROM history WHERE 1 ";
                
        $sql=$sqlbegin.$sqltime."ORDER BY Date ASC ";
                //echo $sql;
        //$sql = "SELECT * FROM history WHERE ".$before." AND ".$after." ORDER BY Date DESC"; 
        $result = $connect->query($sql); 
        if($result->num_rows > 0) { 
        while($row = $result->fetch_assoc()) {

        $sql2 = "SELECT * FROM users WHERE userCode=\"".$row['userCode']."\""; 
        $result2 = $connect->query($sql2); 
        $row2 = $result2->fetch_assoc();
        
        $typeIconic=$row['ExamID']==0?"<i class=\"fas fa-microchip fa-fw\"></i> E.12":($row['ExamID']==1?"<i class=\"fas fa-sitemap fa-fw\"></i> E.13":"<i class=\"fas fa-code fa-fw\"></i> E.14");
            
        $plotadder="<script>graphsettings.data.labels.push('".$row['Date']."');graphsettings.data.datasets[0].data.push('".$row['Score']."')</script>";
            
        echo "<tr data-string=\"".$row['Data']."\" data-type=\"".$row['ExamID']."\">";
        echo "<td>".$row['Date']."</td>"; 
        echo "<td>".$typeIconic."</td>"; 
        echo "<td>[".$row['userCode']."] ".$row2['Name']." ".$row2['LastName']."</td>"; 
        echo "<td>".$row2['class']."</td>"; 
        echo "<td>".$row['Score']."/".$row['Total']."</td>"; 
        echo "<td><div class=\"look icon\"><i class=\"fas fa-search fa-fw\"></i></div>".$plotadder."</td>"; 
        echo "</tr>"; 
    } 
}

?>
