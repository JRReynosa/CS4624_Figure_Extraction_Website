<?php
 
// Check if form was submitted
if(isset($_POST['submit'])) {
 
 
    // Checks if user sent an empty form
    if(!empty(array_filter($_FILES['files']['name']))) {
        // Loop through each file in files[] array
        foreach ($_FILES['files']['tmp_name'] as $key => $value) {
            //str_replace(' ', '', $_FILES['files']['tmp_name'])
            $file_name = str_replace(' ', '', $_FILES['files']['name'][$key]);
            $file_tmpname = str_replace(' ', '', $_FILES['files']['tmp_name'][$key]);
            //$file_size = $_FILES['files']['size'][$key];
            //$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

            $destFile = "/var/www/html/figures/src/figure_extraction/pdf_files/".$_FILES['files']['name'][$key];

            // Set upload file path
            $filepath = $upload_dir.$file_name;
                
                echo "<br /> filename -> {$file_name}";
                echo "<br /> filetempName -> {$file_tmpname} ";
                echo "<br /> destFile -> {$destFile} ";
                
                // If file with name already exist then append time in
                // front of name of the file to avoid overwriting of file
                
                if( move_uploaded_file($file_tmpname, $destFile)) {
                    echo "{$file_name} successfully uploaded <br />";
                }
                else {
                        echo "Error uploading {$file_name} <br />";
                }
    
        }

        $curDir = getcwd() . "hey";
        echo $curDir;

        $command = escapeshellcmd('python3 /var/www/html/figures/src/figure_extraction/extracting_script.py');
        echo $command;
        $output = shell_exec($command);
        echo $output;
    }
    else {
         
        // If no files selected
        echo "No files selected.";
    }
}
 
?>