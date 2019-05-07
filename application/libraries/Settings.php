<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings{
        
    var $online_enrollment = 1; //allowed online enrollment, 1 is on / 0 is off
    var $offline = 0; //site is offline - 1 - if offline and 0 - if online
    var $offline_message = "Sorry! ATSPMS Dashboard is <b>OFFLINE</b> at this moment due to some maintenance. You can log-in your dashboard account again later.";
}

?>