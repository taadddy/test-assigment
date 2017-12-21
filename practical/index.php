<!DOCTYPE HTML>
<html>

<head>
    <title>Test assigment </title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css"/>

</head>

<body>
<div id="wrapper" style="margin: 0 auto; width:70%;">
    <?php

    function parseAndPrepareDataForDb()
    {

        $siteLink = "https://admin.b2b-carmarket.com//test/project";

        // reading site content and making array by spiting it on <br> html tag
        $carSalesRawData = explode("<br>", file_get_contents($siteLink));

        // removing column labels
        unset($carSalesRawData[0]);

        // creating associative array
        $carSalesAssociativeArray = array();

        foreach ($carSalesRawData as $carSale) {

            // parsing line by comma
            $rawCarSaleData = explode(",", $carSale);


            // creating associative array entry
            $singleSale = array(
                "VehicleID" => $rawCarSaleData[0],
                "InhouseSellerID" => $rawCarSaleData[1],
                "BuyerID" => $rawCarSaleData[2],
                "ModelID" => $rawCarSaleData[3],
                "SaleDate" => $rawCarSaleData[4],
                "BuyDate" => $rawCarSaleData[5],
            );

            // saving entry into assoc array;
            array_push($carSalesAssociativeArray, $singleSale);
        }

        return $carSalesAssociativeArray;
    }

    function insertCarSalesData($carSales)
    {
        $servername = "localhost";
        $username = "root";
        $password = "";

        try {
            // creating db connection
            $conn = new PDO("mysql:host=$servername;dbname=test_assigment", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // prepared car sale insert statement
            $insert = $conn->prepare("INSERT INTO CarSales(VehicleID, InhouseSellerID, BuyerID, ModelID, SaleDate, BuyDate) VALUES(:VehicleID, :InhouseSellerID, :BuyerID, :ModelID, :SaleDate, :BuyDate );");

            foreach ($carSales as $carSale) {

                // inserting car sales data

                $insert->bindParam(":VehicleID", $carSale["VehicleID"]);
                $insert->bindParam(":InhouseSellerID", $carSale["InhouseSellerID"]);
                $insert->bindParam(":BuyerID", $carSale["BuyerID"]);
                $insert->bindParam(":ModelID", $carSale["ModelID"]);
                $insert->bindParam(":SaleDate", $carSale["SaleDate"]);
                $insert->bindParam(":BuyDate", $carSale["BuyDate"]);

                $insert->execute();

            }
            $conn = null;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    function insertBuyers($carSales)
    {
        $randomNames = ["Jana", "Corrine", "Isela", "Marlon", "Ferne", "Kai", "Marilu", "Yuonne", "Nakita", "Isobel", "Joel", "Carlos", "Terrence", "Lashunda", "Fernanda", "Daphine", "Cassie", "Nida", "Lily", "Shara", "Becky", "Antwan", "Evia", "Susann", "Johanne", "Earlie", "Emelda", "Deloris", "Mozelle", "Kanisha", "Sidney", "Shon", "Fred", "Blake", "Vanessa", "Emilee", "Hee", "Migdalia", "Blanca", "Iva", "Misty", "Huey", "Sona", "Fatima", "Lanora", "Estella", "Rhiannon", "Rigoberto", "Margarete", "Maximo"];
        $randomSurnames = ["Fulltail", "Dragonhair", "Stormsnow", "Waterleaf", "Slaterun", "Embershout", "Bouldergazer", "Skytoe", "Solidstar", "Tallroot", "Hydradust", "Lunasnarl", "Sharpbow", "Hazescream", "Fallentrap", "Smartbrew", "Fogshaper", "Solidweaver", "Warcut", "Marshgust", "Dragonoak", "Marshsun", "Longripper", "Frostwolf", "Mildkeeper", "Flintbrew", "Sternshade", "Blazesteam", "Downsplitter", "Simplelight"];

        $servername = "localhost";
        $username = "root";
        $password = "";

        try {
            // creating db connection
            $conn = new PDO("mysql:host=$servername;dbname=test_assigment", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $insertedBuyers = array();
            $buyer = $conn->prepare("INSERT INTO Buyers(BuyerID, BuyerName, BuyerSurname) VALUES(:BuyerID, :BuyerName, :BuyerSurname)");
            foreach ($carSales as $carSale) {

                // inserting buyer data
                if (!in_array($carSale["BuyerID"], $insertedBuyers)) {
                    $randomNameIndex = rand(0, sizeof($randomNames) - 1);
                    $randomSurnameIndex = rand(0, sizeof($randomSurnames) - 1);

                    $buyer->bindParam(":BuyerID", $carSale["BuyerID"]);
                    $buyer->bindParam(":BuyerName", $randomNames[$randomNameIndex]);
                    $buyer->bindParam(":BuyerSurname", $randomSurnames[$randomSurnameIndex]);

                    $buyer->execute();

                    array_push($insertedBuyers, $carSale["BuyerID"]);
                }


            }

            $conn = null;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    $carSales = parseAndPrepareDataForDb();
    insertCarSalesData($carSales);
    insertBuyers($carSales);


    ?>


    <table id="car-sales-data" class="display" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>SaleID</th>
            <th>Vehicle</th>
            <th>InhouseSellerID</th>
            <th>Buyer</th>
            <th>ModelID</th>
            <th>SaleDate</th>
            <th>BuyDate</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>SaleID</th>
            <th>Vehicle</th>
            <th>InhouseSellerID</th>
            <th>Buyer</th>
            <th>ModelID</th>
            <th>SaleDate</th>
            <th>BuyDate</th>
        </tr>
        </tfoot>
        <tbody>
        </tbody>
    </table>


</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-1.12.4.js"
        integrity="sha256-Qw82+bXyGq6MydymqBxNPYTaUXXq7c8v3CwiYwLLNXU="
        crossorigin="anonymous">
</script>

<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

<script src="main.js"></script>


</body>

</html>
