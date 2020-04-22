<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH . '/libraries/CILogViewer/CILogViewer.php';
use \CILogViewer\CILogViewer;
class Logview extends System_Controller {
    private $logViewer;

    function __construct() {
        parent::__construct();
        $this->route_access();
        $this->logViewer = new \CILogViewer\CILogViewer();
    }

    public function index() {
        echo $this->logViewer->showLogs();
        return;
    }

}