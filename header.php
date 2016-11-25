<?php 
session_start();
ini_set('zlib.output_compression_level', 6);
ob_start('ob_gzhandler');
include_once 'config/util.php';
isCookieOk();
?>
<!DOCTYPE html>
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />    
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="mobile-web-app-capable" content="yes">
<meta name="description" content="Wine cellar application">
<meta name="author" content="Lionel C.">
<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
<link rel="icon" href="img/favicon.ico" type="image/x-icon">

<title><?php echo $page_title; ?></title>
 
    <!-- some custom CSS -->
    <style>
    @media (min-width:1px)and (max-width:767px) {

        .tablesorter-filter-row > td:nth-child(2),
        .tablesorter-filter-row > td:nth-child(3),
        .tablesorter-filter-row > td:nth-child(4),
        .tablesorter-filter-row > td:nth-child(5),
        .tablesorter-filter-row > td:nth-child(6),
        .tablesorter-filter-row > td:nth-child(7),
        .tablesorter-filter-row > td:nth-child(8)
         {
            display: none !important;
        };
    }
    @media (min-width:768px)and (max-width:991px) {
        .tablesorter-filter-row > td:nth-child(5),
        .tablesorter-filter-row > td:nth-child(6),
        .tablesorter-filter-row > td:nth-child(7),
        .tablesorter-filter-row > td:nth-child(8)
         {
            border: 1px solid red;
            display: none !important;
            width: 0px;
        };
    }
    @media (min-width:992px)and (max-width:1199px) {
        .tablesorter-filter-row > td:nth-child(6),
        .tablesorter-filter-row > td:nth-child(8) {
            border: 1px solid red;
            display: none !important;
            width: 0px;
        };
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
        background-color: #e6eeee;
    }
.pager {
  padding: 5px;
}
/* pager wrapper, in thead/tfoot */
td.pager {
  background-color: #e6eeee;
}
/* pager navigation arrows */
.pager img {
  vertical-align: middle;
  margin-right: 2px;
}
/* pager output text */
.pager .pagedisplay {
  font-size: 11px;
  padding: 0 5px 0 5px;
  width: 50px;
  text-align: center;
}
.tablesorter-headerRow {
        height: 34px;
    }
.tablesorter-ignoreRow {
        height: 24px;
    }
.tablesorter-ignoreRow td.pager{
        padding: 2px;
    }
.pagerSavedHeightSpacer {
	display:none;
	height:0px;
}
/*** loading ajax indeterminate progress indicator ***/
#tablesorterPagerLoading {
  background: rgba(255,255,255,0.8) url(icons/loading.gif) center center no-repeat;
  position: absolute;
  z-index: 1000;
}

/*** css used when "updateArrows" option is true ***/
/* the pager itself gets a disabled class when the number of rows is less than the size */
.pager.disabled {
  display: none;
}
/* hide or fade out pager arrows when the first or last row is visible */
.pager img.disabled {
  /* visibility: hidden */
  opacity: 0.5;
  filter: alpha(opacity=50);
}
    .footer {
    position: fixed;
    bottom: 0;
    width: 100%;
    /* Set the fixed height of the footer here */
    height: 50px;
    background-color: #f5f5f5;
    z-index: 99;
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
.footer {
    border-top: 1px solid #e7e7e7;
}
#allVins {
	width: 100% !important;
}
table tr td a.btn, table tr td a.btn:link, table tr td a.btn:visited {
    color:white !important;
}
html body nav.navbar.navbar-default.navbar-fixed-top div.container div.navbar-header a.navbar-brand {
    color: #7a7a7a;
    font-weight: 700;
    font-size: 20px;
}
.navbar-right {
    margin-right: -7px !important;
}
#allVins tbody a.enlarge span {
    color: #286090 !important;
}


    </style>
 
 
 
    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="lib/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="lib/datepicker/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="lib/jquery/jquery-ui.min.css">
    <link rel="stylesheet" href="lib/jquery/bootstrap-slider.min.css">
    <link rel="stylesheet" href="lib/tablesorter/css/theme.blue.min.css">
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
      <a class="navbar-brand" href="index.php">Mes vins</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
         <?php
        if ($_SESSION && isset($_SESSION['id_utilisateur']) ) {
            // Liste des nomenclatures et critères de recherche uniquement sur la page d'accueil
            if (basename($_SERVER['PHP_SELF'])=="index.php") {
            ?>
                <ul class="nav navbar-nav hidden-sm">
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
                <form class="navbar-form navbar-left visible-lg visible-md"  method='GET' action='index.php'>
                    <div class="form-group">
                      <input type="text" class="form-control" name='comment' placeholder="Commentaires" value='<?php if($_GET && isset($_GET['comment'])) echo $_GET['comment']; ?>'>
                    </div>
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                </form>
                <form class="navbar-form navbar-left visible-xs visible-sm"  method='GET' action='index.php'>
                    <div class="form-group">
                      <input type="text" class="form-control input-sm" name='comment' placeholder="Commentaires" value='<?php if($_GET && isset($_GET['comment'])) echo $_GET['comment']; ?>'>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary">Rechercher</button>
                </form>
                <form class="navbar-form navbar-left visible-lg visible-md"  method='POST' action='index.php'>
                    <button type="submit" class="btn btn-primary reset">Effacer le filtre</button>
                </form>
                <form class="navbar-form navbar-left visible-xs visible-sm"  method='POST' action='index.php'>
                    <button type="submit" class="btn btn-sm btn-primary reset">Effacer</button>
                </form>
            <?php  } ?>
 
                <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['nom_utilisateur']; ?> <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="mon_compte.php">Mes préférences</a></li>
                    <li><a href="mon_mot_de_passe.php">Mot de passe</a></li>
                    <li><a href="emplacement.php">Mes emplacements</a></li>
                    <li><a href="export_csv.php">Export CSV</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="logout.php">Déconnexion</a></li>
                  </ul>
                </li>
                </ul>
        <?php } ?>
      
    </div><!-- /.navbar-collapse -->

  </div><!-- /.container-fluid -->
</nav>


<script type="text/javascript">
	(function($) {
	'use strict';
		function initWines() {
		// Bug Safari sur iphone : 
		//http://stackoverflow.com/questions/2898740/iphone-safari-web-app-opens-links-in-new-window
		$("a").click(function (event) {
		    event.preventDefault();
	    	window.location = $(this).attr("href");
		});
		}
		$(document).ready(initWines);
	})(jQuery);



  </script>



<!-- container -->
<div class="container">
    <br><br><br>