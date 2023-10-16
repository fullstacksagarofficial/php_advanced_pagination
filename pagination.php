<?php
include('db.php'); // Include your database connection script

if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
    $page_no = $_GET['page_no'];
} else {
    $page_no = 1;
}

if (isset($_GET['pages_per_page']) && $_GET['pages_per_page'] != "") {
    $pages_per_page = $_GET['pages_per_page'];
} else {
    $pages_per_page = 3; // Default number of pages to display
}
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

$offset = ($page_no - 1) * $pages_per_page;
// Get the sorting parameters
$sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$sortOrder = isset($_GET['order']) ? $_GET['order'] : 'asc';

// Query to retrieve data for the current page
$query = "SELECT * FROM `pagination_table` WHERE `name` LIKE '%$searchQuery%' OR `dept` LIKE '%$searchQuery%' ORDER BY $sortColumn $sortOrder  LIMIT $offset, $pages_per_page";
$result = mysqli_query($con, $query);

$data = '';
while ($row = mysqli_fetch_array($result)) {
    $data .= "<tr>
        <td>" . $row['id'] . "</td>
        <td>" . $row['name'] . "</td>
        <td>" . $row['age'] . "</td>
        <td>" . $row['dept'] . "</td>
    </tr>";
}

if (empty($data)) {
    $data = "<tr>
        <td colspan='4'>No records found</td>
    </tr>";
}

// Query to calculate total records
$total_records_query = "SELECT COUNT(*) As total_records FROM `pagination_table`";
$total_records_result = mysqli_query($con, $total_records_query);
$total_records = mysqli_fetch_array($total_records_result);
$total_records = $total_records['total_records'];

$total_no_of_pages = ceil($total_records / $pages_per_page);

$pagination_links = '';

if ($total_no_of_pages > 1) {
    $pagination_links .= "<li class='page-item " . (($page_no == 1) ? 'disabled' : '') . " first'><a class='page-link' href='?page_no=1'>&laquo;&laquo;</a></li>";

    if ($page_no > 4) {
        $pagination_links .= "<li class='page-item'><a class='page-link' href='?page_no=1'>1</a></li>";
        $pagination_links .= "<li class='page-item'><a class='page-link'>...</a></li>";
    }

    for ($i = max(1, $page_no - 2); $i <= min($page_no + 2, $total_no_of_pages); $i++) {
        $active = ($i == $page_no) ? 'active' : '';
        $pagination_links .= "<li class='page-item $active'><a class='page-link' href='?page_no=$i'>$i</a></li>";
    }

    if ($page_no < $total_no_of_pages - 3) {
        $pagination_links .= "<li class='page-item'><a class='page-link'>...</a></li>";
        $pagination_links .= "<li class='page-item'><a class='page-link' href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
    }

    $pagination_links .= "<li class='page-item " . (($page_no == $total_no_of_pages) ? 'disabled' : '') . " last'><a class='page-link' href='?page_no=$total_no_of_pages'>&raquo;&raquo;</a></li>";

    // Add "Previous" and "Next" buttons
    $prev_page = ($page_no > 1) ? $page_no - 1 : 1;
    $next_page = ($page_no < $total_no_of_pages) ? $page_no + 1 : $total_no_of_pages;

    $pagination_links = "<li class='page-item " . (($page_no == 1) ? 'disabled' : '') . " prev'><a class='page-link' href='?page_no=$prev_page'>&laquo;</a></li>" .
        $pagination_links .
        "<li class='page-item " . (($page_no == $total_no_of_pages) ? 'disabled' : '') . " next'><a class='page-link' href='?page_no=$next_page'>&raquo;</a></li>";
}

$response = [
    'data' => $data,
    'total_pages' => $total_no_of_pages,
    'page_no' => $page_no,
    'pagination_links' => $pagination_links
];

echo json_encode($response);

mysqli_close($con);
