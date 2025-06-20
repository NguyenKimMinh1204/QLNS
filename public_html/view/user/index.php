<?php
// Assuming show() is a function that returns a list of users
$listUser = show("users");
print_r($listUser);
?>

<link rel="stylesheet" href="./DataTables/datatables.min.css">

<!-- User Table -->
<table id="myTable" class="display">
    <thead>
        <tr>
            <th>Column 1</th>
            <th>Column 2</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Row 1 Data 1</td>
            <td>Row 1 Data 2</td>
        </tr>
        <tr>
            <td>Row 2 Data 1</td>
            <td>Row 2 Data 2</td>
        </tr>
    </tbody>
</table>

<script src="./DataTables/datatables.js"></script>

<script>
// Initialize DataTable
let table = new DataTable('#myTable');
</script>