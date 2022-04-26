<?php include 'header.php' ?>

<body>
    <?php include 'menu.php' ?>
    <div class="flex-center position-ref full-height">
        <div class="content">

            <form action="file_upload.php" method="POST" enctype="multipart/form-data">

                <h2>Upload Files</h2>

                <p>
                    Select files to upload:

                    <!-- name of the input fields are going to
                be used in our php script-->
                    <input type="file" name="files[]" multiple accept="application/pdf">

                    <br><br>

                    <input type="submit" name="submit" value="Upload">
                </p>

            </form>


            <div class="title m-b-md">
                Figure Search Engine
            </div>
            <form class="search" autocomplete="off" action="../../src/elasticsearch/results.php" method="get">
                <input type="text" placeholder="Search..." name="search" id="search" oninput="suggestResults('search')"
                    required>
                <button class="search" type="submit" name="normal_search"> &#128269</button>

            </form>
        </div>
    </div>

    <script src="../js/searchFunctions.js"></script>

</body>

</html>