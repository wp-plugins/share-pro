<?php
// this file contains the contents of the popup window

$curr_url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$curr_url = explode('wp-content', $curr_url);
$sp_ajaxurl = 'http://'.$curr_url[0].'wp-admin/admin-ajax.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Pick a file to share</title>
<meta http-equiv="Expires" content="Sat, 1 Jan 2000 08:00:00 GMT" />
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
<script language="javascript" type="text/javascript" src="../../../../wp-includes/js/tinymce/tiny_mce_popup.js"></script>
<script language="javascript" type="text/javascript" src="js/script_tinymce.js"></script>
<script type="text/javascript"> var sp_ajaxurl = '<?php echo $sp_ajaxurl; ?>'; </script>
<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/smoothness/jquery-ui.css" />
<style type="text/css"> 
    .sp_file_list { list-style-type: none; padding:0; min-height:405px; }
    .sp_file_list li { 
        background-color: #d6d6d6; 
        background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#ffffff), to(#d6d6d6));
        background: -webkit-linear-gradient(top, #ffffff, #d6d6d6);
        background: -moz-linear-gradient(top, #ffffff, #d6d6d6);
        background: -ms-linear-gradient(top, #ffffff, #d6d6d6);
        background: -o-linear-gradient(top, #ffffff, #d6d6d6);
        
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        border: 1px solid #b5b5b5;
        padding: 7px;
        height: 20px;
        line-height: 20px;
        margin-bottom: 5px;
        
        cursor: pointer;
        
        font-size: 13px;
    }
    .sp_file_list li:hover {
        border-color: #0a6fb6;
        color: #0a6fb6;
    }
</style>
</head>
<body class="">
    <form action="/" method="get" accept-charset="utf-8">   
    <div id="ScrollContainer">
        <div id="Scroller">
            <!-- Pick file -->
            <div id="PickingFile" class="scrollPanel">
                <input type="hidden" class="sp_file_pag" value="0" />
                <input type="hidden" class="sp_file_pick" value="" />
                <ul class="sp_file_list">

                </ul>
                <a href="#" class="prev"> Previous </a>
                <a href="#" class="next" style="margin-left:20px;"> Next </a>
                <p> Click on a file to share </p>
            </div>      
        </div><!--EOF:Scroller-->
    </div>
    
    </div>
</body>
</html>