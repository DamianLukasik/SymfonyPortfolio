<?php
if(isset($_GET['run'])){
    $output = shell_exec('./run_project.sh');
    sleep(15);
    //header("Location: http://127.0.0.1:8000"); 
}
?>

<link rel="stylesheet" href="public/fontawesome-free-5.10.2-web/css/all.css" crossorigin="anonymous">
<link rel="stylesheet" href="public/css/mainstyle.css" crossorigin="anonymous">
<link rel="stylesheet" href="public/css/bootstrap.min.css" crossorigin="anonymous">
<script src="public/js/jquery.min.js" crossorigin="anonymous"></script>    
<script src="public/js/bootstrap.min.js" crossorigin="anonymous"></script> 

<div style="padding:8% 50%">
    <form action="" method="get">
        <button  type="submit" name="run" value="run" class="btn btn-success"><i class="fas fa-play"></i> Run me!</button>
    </form>
</div>

