$(document).ready(function () {
    $('#car-sales-data').DataTable(
        {
            "paging": true,
            "retrieve": true,
            "processing": true,
            "serverSide": true,
            "ajax": 'sales.php'
        }
    );
});

