<?php
include("config.php");

$getadmin=$conn->prepare("SELECT * FROM admin");
$getadmin->execute();
$admins = $getadmin->fetchAll();

foreach($admins as $admin){
    echo "<tr>";
    echo "<td>";
    echo $admin['id'];
    echo "</td>";
    echo "<td>";
    echo $admin['username'];
    echo "</td>";
    echo "<td>";
    echo $admin['phone'];
    echo "</td>";
    echo "<td>";
    echo $admin['email'];
    echo "</td>";
    echo "<td>";
    echo $admin['super_Admin'];
    echo "</td>";
    echo "<td>";
    echo "<a href='admin_data_delete.php?id={$admin['id']}' class='delete-btn' onclick='return confirm(\"Delete?\")'>Delete</a>";
    echo "</td>";
    echo "</tr>";
    
    
}
?> 