<?php

class SearchHistory
{
    private $id;
    private $user;
    private $title;
    private $author;
    private $abstract;
    private $publisher;
    private $subject;
    private $department;
    private $degree;
    private $beg_date;
    private $end_date;
    private $date_searched;
    private $normal_search;
    private $url;

    function set_id($id)
    {
        $this->id = $id;
    }

    function get_id()
    {
        return $this->id;
    }


    function set_user($user)
    {
        $this->user = $user;
    }

    function get_user()
    {
        return $this->user;
    }

    function set_title($title)
    {
        $this->title = $title;
    }

    function get_title()
    {
        return $this->title;
    }

    function set_author($author)
    {
        $this->author = $author;
    }

    function get_author()
    {
        return $this->author;
    }

    function set_abstract($abstract)
    {
        $this->abstract = $abstract;
    }

    function get_abstract()
    {
        return $this->abstract;
    }

    function set_publisher($publisher)
    {
        $this->publisher = $publisher;
    }

    function get_publisher()
    {
        return $this->publisher;
    }

    function set_subject($subject)
    {
        $this->subject = $subject;
    }

    function get_subject()
    {
        return $this->subject;
    }

    function set_department($department)
    {
        $this->department = $department;
    }

    function get_department()
    {
        return $this->department;
    }

    function set_degree($degree)
    {
        $this->degree = $degree;
    }

    function get_degree()
    {
        return $this->degree;
    }

    function set_beg_date($beg_date)
    {
        $this->beg_date = $beg_date;
    }

    function get_beg_date()
    {
        return $this->beg_date;
    }

    function set_end_date($end_date)
    {
        $this->end_date = $end_date;
    }

    function get_end_date()
    {
        return $this->end_date;
    }

    function set_date_searched($date_searched)
    {
        $this->date_searched = $date_searched;
    }

    function get_date_searched()
    {
        return $this->date_searched;
    }

    function set_normal_search($normal_search)
    {
        $this->normal_search = $normal_search;
    }

    function get_normal_search()
    {
        return $this->normal_search;
    }

    function set_url($url)
    {
        $this->url = $url;
    }

    function get_url()
    {
        return $this->url;
    }

    function save_search()
    {

        include '../../src/mysql_login.php';

        $div1 = "','";
        $div2 = "','";
        $div3 = "','";

        if (empty($this->get_beg_date())) {
            $this->set_beg_date('NULL');
            $div1 = "',";
        }

        if (empty($this->get_end_date())) {
            $this->set_end_date('NULL');
            $div2 = ",";
            $div3 = ",'";
        }

        $sql = "INSERT INTO search_history(user, title, author, abstract, " .
            "publisher, subject, department, degree, beg_date, end_date, date_searched, normal_search, url)" .
            " VALUES ('" . $this->get_user() . "','" . $this->get_title() . "','" .
            $this->get_author() . "','" . $this->get_abstract() . "','" . $this->get_publisher()
            . "','" . $this->get_subject() . "','" . $this->get_department() . "','" .
            $this->get_degree() . $div1 . $this->get_beg_date() . $div2 . $this->get_end_date() .
            $div3 . $this->get_date_searched() . "','" . $this->get_normal_search() .
            "','" . $this->get_url() .  "');";

        if ($connection->query($sql)) {
            return true;
        } else {
            echo $connection->error;
        }
    }


    function is_saved()
    {
        include '../../src/mysql_login.php';

        $sql = "SELECT * FROM search_history WHERE url='" . $this->get_url() . "';";
        $result = $connection->query($sql);

        return ($result->num_rows > 0);
    }

    function clear_search()
    {
        include '../../src/mysql_login.php';

        $sql = "DELETE FROM search_history WHERE url='" . $this->get_url() . "';";

        if ($connection->query($sql)) {
            header('Location: ../../public/views/search_history_page.php');
        } else {
            echo "Error: " . $sql . "<br>" . $connection->error;
        }
    }

    function clear_all()
    {
        include '../../src/mysql_login.php';

        $sql = "DELETE FROM search_history";

        if ($connection->query($sql)) {
            header('Location: ../../public/views/search_history_page.php');
        } else {
            echo "Error: " . $sql . "<br>" . $connection->error;
        }
    }

    function entry()
    {
        echo '<input type="checkbox" class="delete" style="float:left" id="' . $this->get_id() . '" value="' . $this->get_url() . '">';
        echo '<div class="history" data-value="' . $this->get_id() . '-history"  onclick="window.location=\'http://' . $this->get_url() . '\'">';
        if (!empty($this->get_title())) {
            echo '<b> Title:</b> ' . $this->get_title() . '<br>';
        }

        if (!empty($this->get_author())) {
            echo '<b> Author(s):</b> ' . $this->get_author() . '<br>';
        }

        if (!empty($this->get_abstract())) {
            echo '<b> Abstract:</b> ' . $this->get_abstract() . '<br>';
        }

        if (!empty($this->get_publisher())) {
            echo '<b> Publisher:</b> ' . $this->get_publisher() . '<br>';
        }

        if (!empty($this->get_subject())) {
            echo '<b> Subject:</b> ' . $this->get_subject() . '<br>';
        }

        if (!empty($this->get_department())) {
            echo '<b> Department:</b> ' . $this->get_department() . '<br>';
        }

        if (!empty($this->get_publisher())) {
            echo '<b> Degree:</b> ' . $this->get_degree() . '<br>';
        }

        if (!empty($this->get_beg_date())) {
            echo '<b> Issued Between:</b> ' . $this->get_beg_date() . ' — ' . $this->get_end_date() . '<br>';
        }

        if (!empty($this->get_normal_search())) {
            echo '<b> Normal Search:</b> ' . $this->get_normal_search() . '<br>';
        }

        $time_searched = explode(' ', $this->get_date_searched());

        echo '<br><i> Searched on ' . $time_searched[0] . " at " . $time_searched[1] . '</i>';

        echo '</div>';
        echo '<br>';
    }
}
