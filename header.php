<?php 
session_start();
ini_set('zlib.output_compression_level', 6);
ob_start('ob_gzhandler');
?>
<!DOCTYPE html>
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />    
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title><?php echo $page_title; ?></title>
 
    <!-- some custom CSS -->
    <style>
    table#allVins .tablesorter-filter-row td:nth-child(4n+1) .tablesorter-filter {
        width: 350px;
    }
    table#allVins .tablesorter-filter-row td:nth-child(4n+2) .tablesorter-filter {
        width: 35px;
    }
    table#allVins .tablesorter-filter-row td:nth-child(4n+3) .tablesorter-filter {
        width: 40px;
    }
    table#allVins .tablesorter-filter-row td:nth-child(4n+4) .tablesorter-filter {
        width: 35px;
    }
    table#allVins .tablesorter-filter-row td:nth-child(4n+5) .tablesorter-filter {
        width: 100px;
    }
    table#allVins .tablesorter-filter-row td:nth-child(4n+6) .tablesorter-filter {
        width: 60px;
    }
    table#allVins .tablesorter-filter-row td:nth-child(4n+7) .tablesorter-filter {
        width: 60px;
    }
    table#allVins .tablesorter-filter-row td:nth-child(4n+8) .tablesorter-filter {
        width: 120px;
    }
    table#allVins .tablesorter-filter-row td:nth-child(4n+9) .tablesorter-filter {
        width: 60px;
    }
    table#allVins .tablesorter-filter-row td:nth-child(4n+10) .tablesorter-filter {
        width: 120px;
    }
    table#allVins tbody tr td a.btn.btn-primary {
        color: white;
    }
    table#allVins thead tr.tablesorter-filter-row.tablesorter-ignoreRow td select.tablesorter-filter,    
    table#allVins thead tr.tablesorter-filter-row.tablesorter-ignoreRow td input.tablesorter-filter {
        height: 26px;
    }
    table#allVins tbody tr td.textAndImg{
    overflow: hidden;
    text-indent: -22px;
    }
    table#allVins tbody tr th span {
        width:10px;
        border: none;
        background: none;
        display:inline;
    }

    table#allVins tbody tr th {
        cursor: pointer;
    }
    .apogee {
        color: red !important;
        font-weight:900;
    }
    .left-margin{
        margin:0 .5em 0 0;
    }
 
    .right-margin{
        margin:0 0 0 .5em;
    }
    .right-button-margin {
        float: right;
        margin: 0 0 1em;
        overflow: hidden;
        width: 40%;
    }
    html body div.container div.row div.col-md-4 img {
        width:100%;
    }
    div.input-group.bootstrap-touchspin span.input-group-btn button.btn.btn-default.bootstrap-touchspin-down,
    div.input-group.bootstrap-touchspin span.input-group-btn button.btn.btn-default.bootstrap-touchspin-up {
    background-color: #eee;
    border: 1px solid #ccc;
    color: #555;
    font-size: 14px;
    font-weight: 400;
    padding: 6px 12px;
    text-align: center;
    }
    .form-signin {
        margin: 0 auto;
        max-width: 330px;
        padding: 15px;
    }
    .navbar a.btn {
        color: #fff !important;
        font-weight: 700;
    }
    h2 {
        margin-top: 0px !important;
    }
    .espaceForm {
        height: 70px;
        display:block;
    }
    .separatorForm {
        border-right: 1px solid #DCDCDC;
    }
    .errorMessage {
    display: block;
    margin-top: 10px;
    color: red;
    font-weight: 700;
    }
    .successMessage {
    display: block;
    margin-top: 10px;
    color: green;
    font-weight: 700;
    }
    #totalBouteilles, #totalVins, #totalUsers {
    font-style: italic;
    color: #808080;
    }
    .pager {
        background-color: #F3F3F3;
    }
    .pager img, .pager select {
        margin: 0 10px !important;
    }
    .footer {
  position: absolute;
  bottom: 0;
  width: 100%;
  /* Set the fixed height of the footer here */
  height: 50px;
  background-color: #f5f5f5;
}
.footer > .container {
  padding-right: 15px;
  padding-
  left: 15px;
}
.footer .container p {
    margin-top: 10px;
    font-size: 10px;
}
table tr td a, table tr td a:link, table tr td a:visited {
    color:white !important;
}
    </style>
 
 
 
    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="lib/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="lib/datepicker/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="lib/jquery/jquery-ui.min.css">
    <link rel="stylesheet" href="lib/jquery/bootstrap-slider.min.css">
    <link rel="stylesheet" href="lib/tablesorter/css/theme.dropbox.min.css">
    <link rel="stylesheet" href="lib/tablesorter/addons/pager/jquery.tablesorter.pager.css">

 
    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
 
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="lib/jquery/jquery-1.11.2.min.js"></script>
    <script src="lib/jquery/jquery-ui.min.js"></script>
 
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <!-- Latest compiled and minified JavaScript -->
   
    <script src="lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="lib/datepicker/js/bootstrap-datetimepicker.js"></script>
    <script src="lib/datepicker/js/bootstrap-datetimepicker.fr.js"></script>
    <script src="lib/jquery/bootstrap.touchspin.js"></script>
    <script src="lib/jquery/bootstrap-slider.min.js"></script>
    <script src="lib/tablesorter/js/jquery.tablesorter.min.js"></script>
    <script src="lib/tablesorter/addons/pager/jquery.tablesorter.pager.js"></script>
    <script src="lib/tablesorter/js/jquery.tablesorter.widgets.min.js"></script>

</head>
<body>


<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php">Ma cave</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Nomenclatures <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="nomenc_cepage.php">Cépage</a></li>
            <li><a href="nomenc_aoc.php">Appelation</a></li>
            <li><a href="nomenc_contenance.php">Contenance</a></li>
            <li class="divider"></li>
            <li><a href="nomenc_vins.php">Vins</a></li>
          </ul>
        </li>
      </ul>

      <form action='index.php' method='POST' class="navbar-form " role="search" name="filtre">
         <?php
        if ($_SESSION && isset($_SESSION['id_utilisateur']) ) {
            ?>
                <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['nom_utilisateur']; ?> <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="mon_compte.php">Mon compte</a></li>
                    <li><a href="emplacement.php">Mes emplacements</a></li>
                    <li><a href="export_csv.php">Export CSV</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="logout.php">Déconnexion</a></li>
                  </ul>
                </li>
                </ul>
              <?php
        }
        else {
            echo "<a href='login.php' class='btn btn-primary navbar-right'>Connexion</a>";
        }
        ?>
      </form>
      
    </div><!-- /.navbar-collapse -->

  </div><!-- /.container-fluid -->
</nav>
<!-- container -->
<div class="container">
    <br><br><br><br>