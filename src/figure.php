<?php

class Figure
{
    private $id;
    private $path;
    private $caption;
    private $figtype;
    private $imagetext;

    function set_id($id)
    {
        $this->id = $id;
    }

    function get_id()
    {
        return $this->id;
    }

    function set_path($path)
    {
        $this->path = $path;
    }

    function get_path()
    {
        return $this->path;
    }

    function set_caption($caption)
    {
        $this->caption = $caption;
    }

    function get_caption()
    {
        return $this->caption;
    }

    function set_figtype($figtype)
    {
        $this->figtype = $figtype;
    }

    function get_figtype()
    {
        return $this->figtype;
    }

    function set_imagetext($imagetext)
    {
        $this->imagetext = $imagetext;
    }

    function get_imagetext()
    {
        return $this->imagetext;
    }

    function result($entry)
    {
        $current_url = "$_SERVER[HTTP_HOST]/~penzias$_SERVER[REQUEST_URI]";
        $move_dir = "../";
        echo "<img src='". $move_dir . substr($this->get_path(), 26) . "' ><br><br>"; 
        echo '<b><u>Caption(s):</u></b> ' . $this->get_caption() . '<br>';
        echo '<b><u>Figure Type:</u></b> ' . $this->get_figtype() . '<br>';
        echo '<b><u>Image Text:</u></b> ' . $this->get_imagetext() . '<br><br>';
        echo "<input type='hidden' name='previous-url' value='$current_url'/>";

        echo "<button id='show-more-button-$entry' class='download' onClick=''>Download</button>";
        echo "<br><br><br><br>";

        $downloadId = $entry . '-download';

        // echo "<form action='../../src/elasticsearch/download_docs.php' method='post'>
        //         <button class='download' value='" .$this->get_id()."'name='download_id' type='submit'>Download</button>
        //         </form>";

    }
}
