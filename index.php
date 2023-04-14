<html>

<head>
	<title>Advanced Pagination Using PHP and MySQLi </title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap.min.css" integrity="sha512-Ez0cGzNzHR1tYAv56860NLspgUGuQw16GiOOp/I2LuTmpSK9xDXlgJz3XN4cnpXWDmkNBKXR/VDMTCnAaEooxA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<style>
		.pagination {
			font-family: 'Poppins', sans-serif;
			text-align: center;
			padding: 12px 0px;
			display: inline-flex;
			position: relative;
		}

		.prevnext {
			font-size: 1rem !important;
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
			color: #f95959;
			font-size: 30px;
			font-weight: 400;
		}

		.pagination li a.page-link:hover,
		.pagination li a.page-link:focus,
		.pagination li.active a.page-link:hover,
		.pagination li.active a.page-link {
			color: #f95959;
			background: transparent;
			margin-right: 10px;
		}

		.pagination li a.page-link:before {
			content: '';
			background-color: #f95959;
			height: 100%;
			width: 100%;
			border-radius: 5px;
			border: 5px solid #fff;
			box-shadow: 0 0 0 3px #f95959;
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
			color: #f959598a !important;
		}
	</style>
</head>

<body>
	<div class="container mt-5">

		<h3 class="mb-4">Create Advanced Pagination Using PHP and MySQLi</h3>
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th style='width:50px;'>ID</th>
					<th style='width:150px;'>Name</th>
					<th style='width:50px;'>Age</th>
					<th style='width:150px;'>Department</th>
				</tr>
			</thead>
			<tbody>
				<?php
				include('db.php');

				if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
					$page_no = $_GET['page_no'];
				} else {
					$page_no = 1;
				}

				$total_records_per_page = 3;
				$offset = ($page_no - 1) * $total_records_per_page;
				$previous_page = $page_no - 1;
				$next_page = $page_no + 1;
				$adjacents = "2";

				$result_count = mysqli_query($con, "SELECT COUNT(*) As total_records FROM `pagination_table`");
				$total_records = mysqli_fetch_array($result_count);
				$total_records = $total_records['total_records'];
				$total_no_of_pages = ceil($total_records / $total_records_per_page);
				$second_last = $total_no_of_pages - 1; // total page minus 1

				$result = mysqli_query($con, "SELECT * FROM `pagination_table` LIMIT $offset, $total_records_per_page");
				while ($row = mysqli_fetch_array($result)) {
					echo "<tr>
			  <td>" . $row['id'] . "</td>
			  <td>" . $row['name'] . "</td>
	 		  <td>" . $row['age'] . "</td>
		   	  <td>" . $row['dept'] . "</td>
		   	  </tr>";
				}
				mysqli_close($con);
				?>
			</tbody>
		</table>

		<div class="pagecount">
			<strong>Page <?php echo $page_no . " of " . $total_no_of_pages; ?></strong>
		</div>

		<ul class="pagination">
			<?php // if($page_no > 1){ echo "<li><a href='?page_no=1'>First Page</a></li>"; } 
			?>

			<li <?php if ($page_no <= 1) {
					echo "class=' page-item disabled'";
				} ?>>
				<a class='page-link prevnext' <?php if ($page_no > 1) {
													echo "href='?page_no=$previous_page'";
												} ?>>Previous</a>
			</li>

			<?php
			if ($total_no_of_pages <= 10) {
				for ($counter = 1; $counter <= $total_no_of_pages; $counter++) {
					if ($counter == $page_no) {
						echo "<li class='active class='page-item''><a class='page-link'>$counter</a></li>";
					} else {
						echo "<li class='page-item'><a class='page-link' href='?page_no=$counter'>$counter</a></li>";
					}
				}
			} elseif ($total_no_of_pages > 10) {

				if ($page_no <= 4) {
					for ($counter = 1; $counter < 8; $counter++) {
						if ($counter == $page_no) {
							echo "<li class='page-item active'><a class='page-link'>$counter</a></li>";
						} else {
							echo "<li class='page-item'><a class='page-link' href='?page_no=$counter'>$counter</a></li>";
						}
					}
					echo "<li class='page-item'><a class='page-link'>...</a></li>";
					echo "<li class='page-item'><a class='page-link' href='?page_no=$second_last'>$second_last</a></li>";
					echo "<li class='page-item'><a class='page-link' href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
				} elseif ($page_no > 4 && $page_no < $total_no_of_pages - 4) {
					echo "<li class='page-item'><a class='page-link' href='?page_no=1'>1</a></li>";
					echo "<li class='page-item'><a class='page-link' href='?page_no=2'>2</a></li>";
					echo "<li class='page-item'><a class='page-link'>...</a></li>";
					for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {
						if ($counter == $page_no) {
							echo "<li class='active'><a class='page-link'>$counter</a></li>";
						} else {
							echo "<li class='page-item'><a class='page-link' href='?page_no=$counter'>$counter</a></li>";
						}
					}
					echo "<li class='page-item'><a class='page-link'>...</a></li>";
					echo "<li class='page-item'><a class='page-link' href='?page_no=$second_last'>$second_last</a></li>";
					echo "<li class='page-item'><a class='page-link' href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
				} else {
					echo "<li class='page-item'><a class='page-link' href='?page_no=1'>1</a></li>";
					echo "<li class='page-item'><a class='page-link' href='?page_no=2'>2</a></li>";
					echo "<li class='page-item'><a class='page-link'>...</a></li>";

					for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
						if ($counter == $page_no) {
							echo "<li class='page-item active'><a class='page-link'>$counter</a></li>";
						} else {
							echo "<li  class='page-item'><a class='page-link' href='?page_no=$counter'>$counter</a></li>";
						}
					}
				}
			}
			?>

			<li <?php if ($page_no >= $total_no_of_pages) {
					echo "class='disabled page-item'";
				} ?>>
				<a class='page-link prevnext' <?php if ($page_no < $total_no_of_pages) {
													echo "href='?page_no=$next_page'";
												} ?>>Next</a>
			</li>
			<?php if ($page_no < $total_no_of_pages) {
				echo "<li class='page-item'><a class='page-link prevnext' href='?page_no=$total_no_of_pages'>Last &rsaquo;&rsaquo;</a></li>";
			} ?>
		</ul>


		<br /><br />
		For More Web Snippets or project Visit: <a href="https://www.atechseva.com/"><strong>atechseva.com</strong></a>
	</div>
</body>

</html>