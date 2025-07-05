<?php



function is_super_admin()

{

    $ci = get_instance();

    $role = $ci->session->userdata('role');



    $status = false;

    if (in_array("Super_Admin", $role)) {

        $status = true;
    }



    return $status;
}


function is_admin()

{

    $ci = get_instance();

    $role = $ci->session->userdata('role');



    $status = false;

    if (in_array("Admin", $role)) {

        $status = true;
    }



    return $status;
}



function is_fabrikasi()

{

    $ci = get_instance();

    $role = $ci->session->userdata('role');



    $status = false;



    if (in_array("fabrikasi", $role)) {

        $status = true;
    }



    return $status;
}



function is_supplier()

{

    $ci = get_instance();

    $role = $ci->session->userdata('role');



    $status = false;



    if (in_array("supplier", $role)) {

        $status = true;
    }



    return $status;
}

function is_finance()
{
    $ci = get_instance();
    $role = $ci->session->userdata('role');
    $status = false;
    if (in_array("Finance", $role)) {
        $status = true;
    }
    return $status;
}



function set_pesan($pesan, $tipe = true)

{

    $ci = get_instance();

    if ($tipe) {

        $ci->session->set_flashdata('pesan', '<div class="alert alert-primary alert-dismissible show fade"><div class="alert-body"><button class="close" data-dismiss="alert"><span>&times;</span></button>' . $pesan . '</div></div>');
    } else {

        $ci->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible show fade"><div class="alert-body"><button class="close" data-dismiss="alert"><span>&times;</span></button>' . $pesan . '</div></div>');
    }
}

function set_pesan_two($pesan, $tipe = true)

{

    $ci = get_instance();

    if ($tipe) {

        $ci->session->set_flashdata('pesan_two', '<div class="alert alert-primary alert-dismissible show fade"><div class="alert-body"><button class="close" data-dismiss="alert"><span>&times;</span></button>' . $pesan . '</div></div>');
    } else {

        $ci->session->set_flashdata('pesan_two', '<div class="alert alert-danger alert-dismissible show fade"><div class="alert-body"><button class="close" data-dismiss="alert"><span>&times;</span></button>' . $pesan . '</div></div>');
    }
}



function output_json($data)

{

    $ci = get_instance();

    $data = json_encode($data);

    $ci->output->set_content_type('application/json')->set_output($data);
}
