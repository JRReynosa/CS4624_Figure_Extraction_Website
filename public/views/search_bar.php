<div class="results">
    <br>
    <form class="search" autocomplete="off" style="width: 50%;" action="../../src/elasticsearch/results.php" method="get">
        <input type="text" placeholder="Search..." value="<?php echo $search_v ?>" name="search" id="search" oninput="suggestResults('search')" required>
        <button class="search" type="submit" id="search" name="normal_search">&#128269</button>
    </form>
    &nbsp;

    <br><br><br><br>