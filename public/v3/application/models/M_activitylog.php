<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_activitylog extends CI_Model
{

    public function log_activity($user_id, $action, $description = null, $data = null)
    {
        $text = '';
        if (!empty($data)) {
            $text =  implode(", ", array_map(
                function ($key, $value) {
                    return "$key: $value";
                },
                array_keys($data),
                $data
            ));

            $text .= ', data:' . $text;
        }
        $data = [
            'user_id'     => $user_id,
            'action'      => $action,
            'description' => $description .  $text,
            'ip_address'  => $this->input->ip_address(),
            'user_agent'  => $this->input->user_agent(),
            'created_at'  => date('Y-m-d H:i:s')
        ];
        $this->db->insert('activity_log', $data);
    }

    public function get_logs($limit = 50)
    {
        return $this->db->select('activity_log.*,user.*')->join('user', 'user.id_user = activity_log.user_id', 'left')->order_by('created_at', 'DESC')->limit($limit)->get('activity_log')->result();
    }
}
