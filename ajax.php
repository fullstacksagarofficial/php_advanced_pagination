<html>

<head>
    <title>Advanced Pagination Using PHP and MySQLi </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap.min.css" integrity="sha512-Ez0cGzNzHR1tYAv56860NLspgUGuQw16GiOOp/I2LuTmpSK9xDXlgJz3XN4cnpXWDmkNBKXR/VDMTCnAaEooxA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .pagination {
            font-family: 'Poppins', sans-serif;
            text-align: center;
            padding: 12px 0px;
            display: inline-flex;
            user-select: none;
            position: relative;
        }

        .prevnext {
            font-size: 1rem !important;
        }
        th{
            user-select: none;
            cursor: pointer;
        }
        th img:hover{
           transform: scale(1.3);
           transition: all .4s ease;
        }

        .pagination li a.page-link {
            color: #333;
            background: #eee;
            font-size: 18px;
            line-height: 35px;
            height: 35px;
            margin: 0 5px;
            border: none;
            border-radius: 5px;
            overflow: hidden;
            position: relative;
            z-index: 1;
            transition: all 0.4s ease 0s;
            display: flex;
            align-items: center;
        }

        .pagination li:first-child a.page-link,
        .pagination li:last-child a.page-link {
            color: #59a4f9;
            font-size: 14px;
            font-weight: 400;
        }

        .pagination li a.page-link:hover,
        .pagination li a.page-link:focus,
        .pagination li.active a.page-link:hover,
        .pagination li.active a.page-link {
            color: #59a4f9;
            background: transparent;
        }

        .pagination li a.page-link:before {
            content: '';
            background-color: #59a4f9;
            height: 100%;
            width: 100%;
            border-radius: 5px;
            border: 5px solid #fff;
            box-shadow: 0 0 0 3px #59a4f9;
            opacity: 0;
            transform: scale(2);
            position: absolute;
            left: 0;
            bottom: 0;
            transition: all 0.3s ease 0s;
        }

        .pagecount {
            padding: 5px 15px 5px 5px;
            border-top: dotted 1px #CCC;
        }

        .pagination li a.page-link:hover:before,
        .pagination li a.page-link:focus:before,
        .pagination li.active a.page-link:hover:before,
        .pagination li.active a.page-link:before {
            opacity: 1;
            background-color: transparent;
            transform: scale(0.85);
        }

        @media only screen and (max-width: 480px) {
            .pagination {
                font-size: 0;
                display: inline-block;
            }

            .pagination li {
                display: inline-block;
                vertical-align: top;
                margin: 10px 0;
            }
        }

        .page-item.disabled .page-link {
            color: #59a4f98a !important;
        }

        .searchwidth {
            width: 200px;
            margin-bottom: 10px;

        }

        .form-control,
        select {
            box-shadow: none !important;
            outline: none !important;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row d-flex align-items-center justify-content-between">
            <div class="col-8">Create Advanced Pagination Using PHP and MySQLi</div>
            <div class="col-4 d-flex justify-content-end">
                <div class="form-group searchwidth">
                    <input type="text" id="search" class="form-control " placeholder="Search...">
                </div>
            </div>
        </div>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th style='width:50px;' class="sortable" data-sort="id">ID <img src="sort.png" width="18px" /> </th>
                    <th style='width:150px;' class="sortable" data-sort="name">Name <img src="sort.png" width="18px" /> </th>
                    <th style='width:50px;' class="sortable" data-sort="age">Age <img src="sort.png" width="18px" /> </th>
                    <th style='width:150px;' class="sortable" data-sort="dept">Department <img src="sort.png" width="18px" /> </th>
                </tr>
            </thead>
            <tbody id="pagination-data">
                <!-- Data will be loaded here through AJAX -->
            </tbody>
        </table>

        <div class="pagecount">
            <strong>Page <span id="page-no">1</span> of <span id="total-pages">1</span></strong>
        </div>

        <div class="row d-flex align-items-center">
            <div class="col-6">
                <ul class="pagination" id="pagination-links">
                    <!-- Pagination links will be loaded here through AJAX -->
                </ul>
            </div>
            <div class="col-6 d-flex justify-content-end">
                <div class="mb-3 pagination">
                    <select id="pagesPerPage" class="page-link">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                    </select>
                </div>
            </div>
        </div>




        <br /><br />
        For More Web Snippets or project Visit: <a href="https://www.atechseva.com/"><strong>atechseva.com</strong></a>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            var currentSort = 'id';
        var sortOrder = 'asc'; //

            loadPagination(1);

            $('#pagesPerPage').change(function() {
                loadPagination(1); // Reload the data with the new page count
            });

            $('#search').on('input', function() {
                loadPagination(1); // Reload the data with the new search query
            });

            // Handle click events on sortable table headers
            $('.sortable').on('click', function() {
                var newSort = $(this).data('sort');

                // Toggle sorting order if the same column is clicked
                if (currentSort === newSort) {
                    sortOrder = sortOrder === 'asc' ? 'desc' : 'asc';
                } else {
                    sortOrder = 'asc';
                }

                currentSort = newSort;
                loadPagination(1); // Reload the data with the new sorting parameters
            });

            function loadPagination(page) {
                var pagesPerPage = $('#pagesPerPage').val();
                var searchQuery = $('#search').val();

                $.ajax({
                    url: 'pagination.php', // Replace with the correct URL of your pagination script
                    type: 'GET',
                    data: {
                        page_no: page,
                        pages_per_page: pagesPerPage,
                        search: searchQuery,
                        sort: currentSort,
                        order: sortOrder
                    },
                    dataType: 'json',
                    success: function(response) {
                        var data = response.data;
                        var total_pages = response.total_pages;
                        var page_no = response.page_no;

                        $('#pagination-data').html(data);
                        $('#page-no').text(page_no);
                        $('#total-pages').text(total_pages);

                        $('#pagination-links').html(response.pagination_links);

                        // Enable or disable previous and next buttons
                        if (page_no === 1) {
                            $('#pagination-links li.first').addClass('disabled');
                            $('#pagination-links li.prev').addClass('disabled');
                        } else {
                            $('#pagination-links li.first').removeClass('disabled');
                            $('#pagination-links li.prev').removeClass('disabled');
                        }
                        if (page_no === total_pages) {
                            $('#pagination-links li.next').addClass('disabled');
                            $('#pagination-links li.last').addClass('disabled');
                        } else {
                            $('#pagination-links li.next').removeClass('disabled');
                            $('#pagination-links li.last').removeClass('disabled');
                        }
                    }
                });
            }

            // Handle click events on pagination links
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('=')[1];
                loadPagination(page);
            });
        });
    </script>




</body>

</html>