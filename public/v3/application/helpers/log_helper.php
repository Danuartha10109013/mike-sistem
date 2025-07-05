<?php
defined('BASEPATH') or exit('No direct script access allowed');

function log_activity($user_id, $action, $description = null, $data = null)
{
    $CI = &get_instance();
    $CI->load->model('M_activitylog');
    $CI->M_activitylog->log_activity($user_id, $action, $description, $data);
}
