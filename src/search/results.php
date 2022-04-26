<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

ini_set('memory_limit', '512M');

include 'normal_search.php';
include 'advanced_search_action.php';

$results = [];
$search_v = '';

$search = "";

$title = $author = $abstract = $publisher = $subject
    = $department = $degree = $beg_date = $end_date = '';

$title_v = $author_v = $abstract_v = $publisher_v =
    $subject_v = $department_v = $degree_v = $beg_date_v
    = $end_date_v = '';

// If the normal search button was clicked, perform the 
// normal search.
if (isset($_GET['search'])) {

    // Set the query variable with the inputted search data.
    $search = $_GET['search'];

    // Filter query from possible XSS attacks.
    $search = filter_var($search, FILTER_SANITIZE_STRING);

    $search_v = $search;

    $temp_array = array($search);
    $replace = array('<mark class="highlight">' . $search . '</mark>');

    // Retrieve results from the normal search function.
    $results = normal_search($search);
}

// If the advanced search button was clicked, perform the
// advanced search.
if (isset($_GET['advanced_search'])) {

    $temp = '';

    $temp_array = [];
    $replace = [];

    // Set all of the variables and sanitize the strings.
    if (isset($_GET['title'])) {
        $title = $_GET['title'];
        $title = filter_var($title, FILTER_SANITIZE_STRING);
        $title_v = $title;

        if (!empty($title)) {
            array_push($temp_array, $title);
            array_push($replace, '<mark class="highlight"> ' . $title . ' </mark>');
        }
    }

    if (isset($_GET['author'])) {
        $author = $_GET['author'];
        $author = filter_var($author, FILTER_SANITIZE_STRING);
        $author_v = $author;

        if (!empty($author)) {
            array_push($temp_array, $author);
            array_push($replace, '<mark class="highlight">' . $author . '</mark>');
        }
    }

    if (isset($_GET['abstract'])) {
        $abstract = $_GET['abstract'];
        $abstract = filter_var($abstract, FILTER_SANITIZE_STRING);
        $abstract_v =  $abstract;

        if (!empty($abstract)) {
            array_push($temp_array, $abstract);
            array_push($replace, '<mark class="highlight">' . $abstract . '</mark>');
        }
    }

    if (isset($_GET['publisher'])) {
        $publisher = $_GET['publisher'];
        $publisher = filter_var($publisher, FILTER_SANITIZE_STRING);
        $publisher_v =  $publisher;

        if (!empty($publisher)) {
            array_push($temp_array, $publisher);
            array_push($replace, '<mark class="highlight">' . $publisher . '</mark>');
        }
    }

    if (isset($_GET['subject'])) {
        $subject = $_GET['subject'];
        $subject = filter_var($subject, FILTER_SANITIZE_STRING);
        $subject_v = $subject;

        if (!empty($subject)) {
            array_push($temp_array, $subject);
            array_push($replace, '<mark class="highlight">' . $subject . '</mark>');
        }
    }

    if (isset($_GET['department'])) {
        $department = $_GET['department'];
        $department = filter_var($department, FILTER_SANITIZE_STRING);
        $department_v = $department;

        if (!empty($department)) {
            array_push($temp_array, $department);
            array_push($replace, '<mark class="highlight">' . $department . '</mark>');
        }
    }

    if (isset($_GET['dgree'])) {
        $degree = $_GET['dgree'];
        $degree = filter_var($degree, FILTER_SANITIZE_STRING);
        $degree_v = $degree;

        if (!empty($degree)) {
            array_push($temp_array, $degree);
            array_push($replace, '<mark class="highlight">' . $degree . '</mark>');
        }
    }

    if (isset($_GET['start_date'])) {
        $beg_date = $_GET['start_date'];
        $beg_date = filter_var($beg_date, FILTER_SANITIZE_STRING);
        $beg_date_v = $beg_date;

        if (!empty($beg_date)) {
            array_push($temp_array, $beg_date);
            array_push($replace, '<mark class="highlight">' . $beg_date . '</mark>');
        }
    }

    if (isset($_GET['end_date'])) {
        $end_date = $_GET['end_date'];
        $end_date = filter_var($end_date, FILTER_SANITIZE_STRING);
        $end_date_v = $end_date;

        if (!empty($end_date)) {
            array_push($temp_array, $end_date);
            array_push($replace, '<mark class="highlight">' . $end_date . '</mark>');
        }
    }

    // Retrieve the boolean for if all the inputs are empty.
    $all_empty = no_inputs(
        $title,
        $author,
        $abstract,
        $publisher,
        $subject,
        $department,
        $degree,
        $beg_date,
        $end_date
    );

    if ($all_empty) {
        // If all of the inputs are empty, redirect the user and warn them.
        echo "<script> alert('At least one field must be filled in.');
            window.location = 'results.php'; </script>";
    }

    $results = advanced_search(
        $title,
        $author,
        $abstract,
        $publisher,
        $subject,
        $department,
        $degree,
        $beg_date,
        $end_date
    );
}

include '../../public/views/header.php';
?>

<body>
    <?php
    session_start();
    include '../../public/views/menu.php';
    include '../../public/views/search_bar.php';
    require_once '../dissertation.php';

    $total_results = $results->num_rows;
    $results_per_page = 10;
    $total_pages = ceil($total_results / $results_per_page);

    if (!isset($_GET['page'])) {
        $page = 1;
    } else {
        $page = $_GET['page'];
    }

    $offset = ($page - 1) * $results_per_page;

    $rows = [];

    while ($r = $results->fetch_assoc()) {
        array_push($rows, $r);
    }

    $rows = array_slice($rows, $offset, $results_per_page);
    // Print out the number of returned search results.
    echo '<p class="num-results"> ' . $total_results . ' search results for ' . $search .
        $title_v . ' ' . $author_v . ' ' . $publisher_v . ' ' . $abstract_v .
        $subject_v . ' ' . $department_v . ' ' . $degree_v . ' ' .
        $beg_date_v . ' ' . $end_date_v . '</p>';

    $search_url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    echo '<p id="url" hidden>' . $search_url . '</p>';

    include 'search_saved.php';

    $entry = 0;

    include '../../public/views/faceted_search.php';

    echo '<div class="results-container">';
    foreach ($rows as $r) {
        // Set the dissertation data variables.
        $abstract = strip_tags($r['abstract']);
        $title = $r['title'];
        $author = $r['author'];
        $publisher = $r['university'];
        $year = $r['year'];

        $dissertation = new Dissertation();

        $dissertation->set_id($r['id']);
        $dissertation->set_url($r['URI']);

        // Replace strings with highlighted text.
        $dissertation->set_title(str_ireplace($temp_array, $replace, $title));
        $dissertation->set_author(str_ireplace($temp_array, $replace, $author));
        $dissertation->set_publisher(str_ireplace($temp_array, $replace, $publisher));
        $dissertation->set_abstract(str_ireplace($temp_array, $replace, $abstract));
        $dissertation->set_year(str_ireplace($temp_array, $replace, $year));

        $dissertation->result($entry);

        $entry += 1;
        
    }

    echo '</div>';

    $current_url = "https://".$_SERVER['HTTP_HOST']."/~penzias".$_SERVER['REQUEST_URI'];

    $previous_page = $page - 1;
    $first_page = 1;
    $last_page = $total_pages;
    $max_page_links = $page + 10;

    echo '<div class="pagination">';

    $counter = 0;

    if ($total_pages > 0) {
        if ($page > 2) {
            echo '<a href="' . $current_url . '&page=' . $first_page . '"> << </a>';
        }

        if ($page != 1) {
            echo '<a href="' . $current_url . '&page=' . $previous_page . '"> < </a>';
        }

        for ($page; $page <= $max_page_links; $page++) {
            if ($counter == 0) {
                echo '<a href="' . $current_url . '&page=' . $page . '" style="background-color: #00BFFF;">' . $page . '</a>';
                $counter++;
            } else {
                echo '<a href="' . $current_url . '&page=' . $page . '">' . $page . '</a>';
            }
            if ($page == $total_pages) {
                break;
            }
        }
        if ($page != $total_pages) {
            echo '<a href="' . $current_url . '&page=' . $total_pages . '"> >> </a>';
        }

        echo '</div>';
    }
    ?>

    <script src="../../public/js/searchFunctions.js" type="text/javascript"></script>
</body>

</html>