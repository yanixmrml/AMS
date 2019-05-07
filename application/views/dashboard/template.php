<?php 
$dashboard_url = base_url() . "index.php/dashboard/";
$icon_url = base_url() . "assets/css/images/dashboard/";
$current_page = $dashboard_url . "home";
$data["dashboard_url"] = $dashboard_url;
$data["page"] = $page;
$data["webTitle"] = "AMS Dashboard";
$data["semesters"] = array("1st semester","2nd semester","Summer");
$data["footer_creator"] = "<br /><span class='footer_creator'>Developed by KKD Group, Inc., College of Engineering, MSU-Marawi</span>";
$this->load->view("include/header.php",$data);
$menus = array(array("URL"=>$current_page,"NAME"=>"Home","ICON"=>$icon_url . "profile.png","ID"=>"home-button"),
              array("URL"=>$dashboard_url . "faculty","NAME"=>"Faculty","ICON"=>$icon_url . "profile.png","ID"=>"home-faculty"),
              array("URL"=>"#passwordModal","NAME"=>"Change Password","ICON"=>$icon_url . "logout.png", "ID"=>"home-change-password"),
              array("URL"=>$dashboard_url . "logout","NAME"=>"Logout","ICON"=>$icon_url . "logout.png" ,"ID"=>"home-logout")
              );

$data["menus"] = $menus;
$selectedPage = $menus[0];
?>
<!-- Fixed navbar -->
<div>
  <nav class="navbar navbar-expand-lg navbar-light bg-light" role="navigation">
    <a class="navbar-brand" href="<?php echo $current_page; ?>"><img src='<?php echo base_url(); ?>/assets/css/images/msu-logo.png'/ class="d-inline-block align-top">&nbsp;AMS Dashboard</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportContent" aria-expanded="false" aria-label="Toggle Navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <div class="navbar-nav ml-auto">
        <?php
           $i = 0;
           for($i=0;$i<count($menus)-2;$i++){
              if(isset($page) && $menus[$i]['NAME']==$page){
                $selectedPage = $menus[$i];
                echo '<a class="nav-link nav-item active" href="' . $menus[$i]['URL'] . '">' . $menus[$i]['NAME'] . '</a>';
              }else{
                echo '<a class="nav-link nav-item"  href="' . $menus[$i]['URL'] . '">' . $menus[$i]['NAME'] . '</a>';
              }
           }
           echo '<a class="nav-link nav-item"  href="' . $menus[$i+1]['URL'] . '">' . $menus[$i+1]['NAME'] . '</a>';
        ?>
      </div>
    </div><!--/.nav-collapse -->
  </nav>
</div>
<div class="container-fluid" id="atspms-dashboard-wrapper">  
  <div class="row">
    <div class="col-md-10">
      <div id="row-path" class="row">
        <span id="path-date" class="col align-middle">Today is <?php echo date("l, F d, Y"); ?></span>
        <span id="path-body" class="col align-middle">You are here: <?php echo "<a href='" . $menus[0]["URL"] . "'>" . $data["webTitle"] . "</a> | <a href='" . $selectedPage["URL"] .
          "'>" . $selectedPage["NAME"] . "</a>"; ?>
        </span>
        <span class="clear-both">&nbsp;</span>
      </div>
      <hr />
      <?php $this->load->view($page_include,$data); ?>
    </div><!-- /.col-sm-4 -->
    <div class="col-md-2" id="left-nav">
      <?php  $this->load->view("include/leftnav.php"); ?>
     
       <!---<div class="jumbotron">
        </div> --->
    </div><!-- /.col-sm-4 -->   
  </div>
</div> <!-- /container -->
<?php $this->load->view("include/footer.php",$data); ?>
