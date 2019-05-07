        <footer id="footer">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <a href='<?php echo base_url(); ?>' title='Proceed to MSU Marawi Campus Website'><img id='atspms-dashboard-logo' src='<?php echo base_url(); ?>/assets/css/images/dashboard-logo.jpg'></a>
                    </div>
                    <div class="col text-muted" id="footer-left">
                        <?php echo $webTitle; ?><br />
                        This page is maintained by KKD Group, Inc.
                        College of Engineering,<br/>
                        Mindanao State University,<br/>
                        Marawi City
                        <br /><br/>
                        <b>All rights reserved &copy 2017 KKD Group, Inc.</b>
                        <?php echo (isset($footer_creator)?$footer_creator:"---"); ?>
                    </div>
                    <div class="col text-muted" id="footer-right">
                        For AMS related comments and suggestions,
                        please email us at ats_pms@gmail.com
                        <br/><br/>
                        <p><b>Follow us</b><br/><br/>
                            <a href='https://www.facebook.com/LPD-ATSPMS' title='Follow us on our Facebook page'><img src='<?php echo base_url(); ?>/assets/css/images/facebook.png' class="img-fluid" style="padding: 3px;"></a>
                            <a href='https://www.twitter.com/LPD-ATSPMS' title='Follow us on our Twitter account'><img src='<?php echo base_url(); ?>/assets/css/images/twitter.png' class="img-fluid" style="padding: 3px;"></a>
                        </p>                        
                    </div>
                </div>
            </div>
        </footer>
        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
        <!--[if lt IE 9]>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ie8-responsive-file-warning.js"></script>
        <![endif]-->
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ie-emulation-modes-warning.js"></script>
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/html5shiv.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/respond.min.js"></script>
        <![endif]-->        
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-toggle.min.js"></script>      
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/highcharts.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/highcharts-more.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/solid-gauge.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.jcarousellite.js"></script>
        <script type="text/javascript" src="<?php echo base_url() . $page_javascript; ?>"></script>
        <?php
            if(isset($page_specialized_script)){
                echo '<script type="text/javascript" src="' . base_url() . $page_specialized_script . '"></script>';
            }
        ?>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.easing.js"></script>
        <!---<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/docs.min.js"></script>--->
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="<?php echo base_url(); ?>assets/js/ie10-viewport-bug-workaround.js"></script>
    </body>
</html>