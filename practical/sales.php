<?php

$servername = "localhost";
$username = "root";
$password = "";

try {

    $conn = new PDO("mysql:host=$servername;dbname=test_assigment", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // search
    if(!empty($_GET["search"]["value"])){

        $result = $conn->prepare("SELECT cs.SaleID, cs.VehicleID, cs.InhouseSellerID ,CONCAT(b.BuyerName,' ',b.BuyerSurname) AS Buyer, cs.ModelID, cs.SaleDate, cs.BuyDate 
                                            FROM CarSales AS cs 
                                            LEFT JOIN Buyers AS b ON b.BuyerID = cs.BuyerID
                                            WHERE b.BuyerName LIKE CONCAT('%', :nameQuery, '%') OR b.BuyerSurname LIKE CONCAT('%', :surnameQuery, '%') OR cs.SaleDate LIKE CONCAT('%', :dateQuery, '%')
                                            LIMIT :entries OFFSET :size ");

        $result->bindparam(":entries", intval($_GET["length"]), PDO::PARAM_INT);
        $result->bindValue(":size", intval($_GET["start"]), PDO::PARAM_INT);
        $result->bindValue(":nameQuery", intval($_GET["search"]["value"]), PDO::PARAM_INT);
        $result->bindValue(":surnameQuery", intval($_GET["search"]["value"]), PDO::PARAM_INT);
        $result->bindValue(":dateQuery", intval($_GET["search"]["value"]), PDO::PARAM_INT);

        $result->execute();
    }

    // result paging or number of displayed elements in db changed
    else {
        $result = $conn->prepare("SELECT cs.SaleID, cs.VehicleID, cs.InhouseSellerID ,CONCAT(b.BuyerName,' ',b.BuyerSurname) AS Buyer, cs.ModelID, DATE_FORMAT(cs.SaleDate, '%d.%m.%Y'), DATE_FORMAT(cs.BuyDate, '%d.%m.%Y') 
                                            FROM CarSales AS cs 
                                            LEFT JOIN Buyers AS b ON b.BuyerID = cs.BuyerID 
                                            LIMIT :entries OFFSET :size ");

        $result->bindparam(":entries", intval($_GET["length"]), PDO::PARAM_INT);
        $result->bindValue(":size", intval($_GET["start"]), PDO::PARAM_INT);

        $result->execute();


    }
    $resultData = $result->fetchAll();
    echo json_encode(array("aaData" => $resultData, "iTotalDisplayRecords" => 8415, "iTotalRecords" => 8415, "sEcho" => $_GET));


} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

