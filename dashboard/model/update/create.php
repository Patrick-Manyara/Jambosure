<?php

use PhpOffice\PhpSpreadsheet\Reader\Csv;

require_once '../../path.php';
require_once MODEL_PATH . "operations.php";
include_once('../vendor/autoload.php');
include_once('../../vendor/autoload.php');
include_once '../../meeting/create_meeting.php';

$action = (isset($_GET['action']) && $_GET['action'] != '') ? security('action', 'GET') : '';



if (!csrf_verify(security('csrf_token'))) render_warning(admin_url);
unset($_POST['csrf_token']);



foreach ($_GET as $key => $val) {
    $conn = connect();
    $_GET[$key] = mysqli_real_escape_string($conn, $_GET[$key]);
}
foreach ($_POST as $key => $val) {
    $conn = connect();
    if (!is_array($_POST[$key])) {
        $_POST[$key] = mysqli_real_escape_string($conn, $_POST[$key]);
    }
}


switch ($action) {
    case 'admin_login':
        get_user_login();
        break;
    case 'user_login':
        get_login();
        break;

    case 'admin':
        post_admin();
        break;
    case 'register':
        post_user();
        break;
    case 'therapist':
        post_therapist();
        break;
    case 'writer':
        post_writer();
        break;
    case 'user':
        post_client();
        break;
    case 'password':
        post_password();
        break;
    case 'admin_password':
        post_admin_password();
        break;
    case 'product':
        post_product();
        break;
    case 'policy':
        post_policy();
        break;
    case 'banner':
        post_banner();
        break;
    case 'test':
        post_test();
        break;
    case 'simple':
        post_simple($_GET['table'], $_GET['url']);
        break;
}


function post_simple($table, $url)
{
    global $arr;
    global $error;
    global $success;

    $return_url = admin_url . $url;


    for_loop();


    $param = '';
    if (isset($_SESSION['edit'])) {
        $param = "?id=" . encrypt($_SESSION['edit']);
    }

    if (!empty($error)) {
        $url = $return_url . $param;
        error_checker($url);
    }

    if (isset($_SESSION['edit'])) {
        $id = $_SESSION['edit'];
        unset($_SESSION['edit']);

        if (!build_sql_edit($table, $arr, $id, $table . '_id')) {
            $error[$table] = 149;
            error_checker($return_url . '   ?id=' . encrypt($id));
        }

        $success[$table] = 221;
        render_success($return_url . '?id=' .  encrypt($id));
    }

    $id = $arr[$table . '_id'] = create_id($table, $table . '_id');
    //  var_dump($arr);
    //  var_dump($table);

    if (!build_sql_insert($table, $arr)) {
        $error[$table] = 150;
        error_checker($return_url);
    }

    $success[$table] = 220;
    render_success($return_url);
}




function post_banner()
{
    global $arr;
    global $error;
    global $success;
    $return_url = admin_url . "view_banners";

    if (!empty($_FILES['banner_poster']['name']))    $arr['banner_poster']   = upload('banner_poster');

    for_loop();

    if (!empty($error)) {
        error_checker($return_url);
    }


    if (isset($_SESSION['edit'])) {
        $id = $_SESSION['edit'];
        unset($_SESSION['edit']);

        if (!empty($arr['banner_poster']))   delete_file('banner_poster', 'banner', 'banner_id', $id);

        if (!build_sql_edit('banner', $arr, $id, 'banner_id')) {
            $error['banner'] = 132;
            error_checker($return_url);
        }

        $success['banner'] = 206;
        render_success($return_url);
    }

    $arr['banner_id'] = create_id('banner', 'banner_id');

    if (!build_sql_insert('banner', $arr)) {
        $error['banner'] = 134;
        error_checker($return_url);
    }

    $success['banner'] = 205;
    render_success($return_url);
}

function post_therapist()
{
    global $arr;
    global $error;
    global $success;

    $return_url = admin_url . "view_therapists";


    if (!empty($_FILES['therapist_image']['name']))     $arr['therapist_image']     = upload('therapist_image');
    if (!empty($_FILES['therapist_video']['name']))     $arr['therapist_video']     = uploadvid('therapist_video');
    if (isset($_POST['category_id'])) {
        $arr['category_id'] = implode(",", $_POST['category_id']);
        unset($_POST['category_id']);
    }
    for_loop();

    if (!empty($error)) {
        error_checker($return_url);
    }


    if (isset($_SESSION['edit'])) {
        $id = $_SESSION['edit'];
        unset($_SESSION['edit']);

        if (!empty($arr['therapist_image']))    delete_file('therapist_image', 'therapist', 'therapist_id');
        if (!empty($arr['therapist_video']))    delete_file('therapist_video', 'therapist', 'therapist_id', 'videos');


        if (!build_sql_edit('therapist', $arr, $id, 'therapist_id')) {
            $error['view_therapists'] = 153;
            error_checker($return_url);
        }

        $success['view_therapists'] = 224;
        render_success($return_url);
    }

    $arr['therapist_id'] = create_id('therapist', 'therapist_id');
    $arr['therapist_password']   = password_hashing_hybrid_maker_checker($arr['therapist_password']);

    if (!build_sql_insert('therapist', $arr)) {
        header("Location: $return_url?error=Details not updated!");
        exit;
    }

    header("Location: $return_url?success=Details updated successfully");
}

function post_writer()
{
    global $arr;
    global $error;
    global $success;

    $return_url = admin_url . "view_writers";

    if (!empty($_FILES['writer_image']['name']))     $arr['writer_image']     = upload('writer_image');

    for_loop();

    if (!empty($error)) {
        error_checker($return_url);
    }



    if (isset($_SESSION['edit'])) {
        $id = $_SESSION['edit'];
        unset($_SESSION['edit']);

        if (!empty($arr['writer_image']))    delete_file('writer_image', 'writer', 'writer_id');


        if (!build_sql_edit('writer', $arr, $id, 'writer_id')) {
            $error['view_writers'] = 153;
            error_checker($return_url);
        }

        $success['view_writers'] = 224;
        render_success($return_url);
    }

    $arr['writer_id'] = create_id('writer', 'writer_id');
    if (!build_sql_insert('writer', $arr)) {
        header("Location: $return_url?error=Details not updated!");
        exit;
    }

    header("Location: $return_url?success=Details updated successfully");
}

function post_test()
{
    global $arr;
    global $error;
    global $success;

    $return_url = admin_url . "view_writers";

    // cout($_POST);
    // exit();
}

function post_policy()
{
    global $arr;
    global $error;
    global $success;

    $return_url = admin_url . "view_writers";



    $arr['policy_id'] = create_id('policy', 'policy_id');
    $arr['user_id'] = '0jog3yIUCGA';
    $arr['policy_make'] = security('make');
    $arr['policy_model'] = security('model');

    $arr['policy_year'] = security('year');
    $arr['policy_value'] = security('value');
    $arr['category_id'] = security('category_id');
    if (isset($_POST['subcategory_id'])) {
        $arr['subcategory_id'] = security('subcategory_id');
    }
    $arr['policy_price'] = security('officialPrice');
    $arr['product_id'] = security('product_id');
    $arr['policy_benefits'] = security('benefitsString');

    if (!build_sql_insert('policy', $arr)) {
        $error['view_policies'] = 154;
        error_checker($return_url);
    }

    // $subject    = APP_NAME . ' Account Creation';
    // $name       = APP_NAME;
    // $body       = '<p style="font-family:Poppins, sans-serif;"> ';
    // $body       .= 'Hello, <b> ' . $arr['user_name'] . ' </b> <br>';
    // $body       .= 'Your account has been successfully created.';
    // $body       .= '<br>';
    // $body       .= 'You may log in to your account in the future with these credentials';
    // $body       .= '<br>';
    // $body       .= '<b>EMAIL:</b> ' . $arr['user_email'] . ' <br>';
    // $body       .= '<br>';
    // $body       .= '<b>PASSWORD:</b> ' . security('user_password') . ' <br>';
    // $body       .= '<br>';


    // email($arr['user_email'], $subject, $name, $body);

    $success['view_users'] = 220;
    render_success($return_url);
}


function post_product()
{
    global $arr;
    global $error;
    global $success;
    $return_url = admin_url . 'view_products';


    $conn = connect();

    //product DATA
    $arr['product_id']      = create_id('product', 'product_id');
    $arr['product_code']    = 'JAMBO-' . generateRandomString('6');
    $arr['category_id']     = security('category_id');
    $arr['writer_id']     = security('writer_id');
    $arr['product_name']     = security('product_name');
    $arr['product_mode']     = security('product_mode');



    if (isset($_POST['subcategory_id'])) {
        $arr['subcategory_id']  = security('subcategory_id');
    }

    if (isset($_POST['product_premium'])) {
        $arr['product_premium']  = security('product_premium');
    }

    if (isset($_POST['product_mincap'])) {
        $arr['product_mincap']  = security('product_mincap');
    }

    if (isset($_POST['product_price'])) {
        $arr['product_price']  = security('product_price');
    }

    build_sql_insert("product", $arr);

    //BENEFIT DATA
    if ($_POST['benefit_name'][0] != "") {
        echo 'here';
        foreach ($_POST['benefit_name'] as $key => $value) {
            $arr2['benefit_id']          = create_id('benefit', 'benefit_id');
            $arr2['benefit_name']        = mysqli_real_escape_string($conn, $_POST['benefit_name'][$key]);
            $arr2['benefit_free']        = mysqli_real_escape_string($conn, $_POST['benefit_free'][$key]);
            $arr2['benefit_mode']       = mysqli_real_escape_string($conn, $_POST['benefit_mode'][$key]);
            $arr2['benefit_price']   = mysqli_real_escape_string($conn, $_POST['benefit_price'][$key]);
            $arr2['benefit_rate']   = mysqli_real_escape_string($conn, $_POST['benefit_rate'][$key]);
            $arr2['product_id']        = $arr['product_id'];
            build_sql_insert("benefit", $arr2);
        }
    }
    //LEVY DATA
    if ($_POST['levy_name'][0] != "") {
        echo 'here2';
        foreach ($_POST['levy_name'] as $key => $value) {
            $arr3['levy_id']          = create_id('levy', 'levy_id');
            $arr3['levy_name']        = mysqli_real_escape_string($conn, $_POST['levy_name'][$key]);
            $arr3['levy_rate']        = mysqli_real_escape_string($conn, $_POST['levy_rate'][$key]);
            $arr3['levy_mode']       = mysqli_real_escape_string($conn, $_POST['levy_mode'][$key]);
            $arr3['levy_price']   = mysqli_real_escape_string($conn, $_POST['levy_price'][$key]);
            $arr3['product_id']        = $arr['product_id'];
            build_sql_insert("levy", $arr3);
        }
    }

    $success['medication'] = 203;
    render_success($return_url);
}


function post_user()
{
    global $arr;
    global $error;
    global $success;

    if (isset($_GET['payment'])) {
        $return_url = base_uri . 'payment';
    } else {
        $return_url = base_uri;
    }


    for_loop();


    $id = $arr['user_id']   = create_id('user', 'user_id');
    $arr['user_password']   = password_hashing_hybrid_maker_checker($arr['user_password']);

    if (!build_sql_insert('user', $arr)) {
        $error['user'] = 139;
        error_checker($return_url);
    }

    $header   = APP_NAME;
    $subject    = APP_NAME . " Client Sign Up";
    $user_name = $arr['user_name'];
    $user_email = $arr['user_email'];
    $user_phone = $arr['user_phone'];
    $body = '';


    email($arr['user_email'], $subject, $header, $body);


    $emails = select_rows('SELECT admin_email FROM admin');

    $adminEmails = [];

    foreach ($emails as $emailObject) {
        if (isset($emailObject['admin_email']) && !empty($emailObject['admin_email'])) {
            $adminEmails[] = $emailObject['admin_email'];
        }
    }

    foreach ($adminEmails as $adminEmail) {
        email($adminEmail, $subject, $header, $body);
    }

    $success['user'] = 203;
    render_success($return_url);
}


function post_client()
{
    global $arr;
    global $error;
    global $success;
    $return_url = admin_url . "view_users";

    if (!empty($_FILES['user_image']['name']))    $arr['user_image']   = upload('user_image');

    for_loop();

    if (!empty($error)) {
        error_checker($return_url);
    }


    if (isset($_SESSION['edit'])) {
        $id = $_SESSION['edit'];
        unset($_SESSION['edit']);

        if (!empty($arr['user_image']))   delete_file('user_image',   'user', 'user_id');

        if (!build_sql_edit('user', $arr, $id, 'user_id')) {
            $error['view_users'] = 153;
            error_checker($return_url);
        }

        $success['view_users'] = 224;
        render_success($return_url);
    }

    $arr['user_id'] = create_id('user', 'user_id');
    $arr['user_password']   = password_hashing_hybrid_maker_checker($arr['user_password']);

    if (!build_sql_insert('user', $arr)) {
        $error['view_users'] = 154;
        error_checker($return_url);
    }

    $subject    = APP_NAME . ' Account Creation';
    $name       = APP_NAME;
    $body       = '<p style="font-family:Poppins, sans-serif;"> ';
    $body       .= 'Hello, <b> ' . $arr['user_name'] . ' </b> <br>';
    $body       .= 'Your account has been successfully created.';
    $body       .= '<br>';
    $body       .= 'You may log in to your account in the future with these credentials';
    $body       .= '<br>';
    $body       .= '<b>EMAIL:</b> ' . $arr['user_email'] . ' <br>';
    $body       .= '<br>';
    $body       .= '<b>PASSWORD:</b> ' . security('user_password') . ' <br>';
    $body       .= '<br>';


    email($arr['user_email'], $subject, $name, $body);

    $success['view_users'] = 220;
    render_success($return_url);
}



function post_voucher()
{
    global $arr;
    global $error;
    global $success;
    $return_url = admin_url . "pay?id=";

    $num = security('voucher_num');

    $id = $arr['voucher_batch'] = 'BATCH-' . generateRandomString('6');
    $cid = $_SESSION['corporate_id'];
    $corporate_name  =  get_by_id('corporate', $cid)['corporate_name'];
    $parts = explode(" ", $corporate_name);
    $first_part = $parts[0];


    for ($i = 1; $i <= $num; $i++) {
        for_loop();
        $arr['voucher_id'] = create_id('voucher', 'voucher_id');
        $arr['voucher_added_by'] = 'corporate';
        $arr['voucher_code'] = $first_part . '-' . generateRandomString('5');
        build_sql_insert('voucher', $arr);
    }

    $success['view_vouchers'] = 225;
    render_success($return_url . encrypt($id));
}


function post_password()
{
    global $arr;
    global $error;
    global $success;
    $return_url = client_url . 'password';

    $current_password = security('current_password');
    $new_password = security('new_password');
    $confirm_password = security('confirm_password');

    // cout($_POST);

    $user = get_by_id('user', $_SESSION['user_id']);

    // if (password_hashing_hybrid_maker_checker($current_password, $user['user_password'])) {
    //     $error['user'] = 157;
    //     error_checker($return_url);
    // }

    // if (password_hashing_hybrid_maker_checker($new_password, $user['user_password'])) {
    //     $error['user'] = 156;
    //     error_checker($return_url);
    // }

    if ($new_password != $confirm_password) {
        $error['user'] = 145;
        error_checker($return_url);
    }

    $arr['user_password'] = password_hashing_hybrid_maker_checker($new_password);

    if (!build_sql_edit('user', $arr, $user['user_id'], 'user_id')) {
        $error['user'] = 160;
        error_checker($return_url);
    }

    $success['user'] = 226;
    render_success($return_url);
}


function post_admin_password()
{
    global $arr;
    global $error;
    global $success;
    $return_url = admin_url . 'password.php';

    $new_password = security('new_password');
    $confirm_password = security('confirm_password');

    // cout($_POST);

    $admin = get_by_id('admin', $_SESSION['admin_id']);

    if ($new_password != $confirm_password) {
        $error['admin'] = 145;
        error_checker($return_url);
    }

    $arr['admin_password'] = password_hashing_hybrid_maker_checker($new_password);

    if (!build_sql_edit('admin', $arr, $admin['admin_id'], 'admin_id')) {
        header("Location: $return_url?error=Details not updated!");
        exit;
    }

    header("Location: $return_url?success=Details updated successfully");
}

function post_admin()
{
    global $arr;
    global $error;
    global $success;
    $return_url = admin_url . 'admin';
    $success_url = admin_url . 'view_admins';

    for_loop();

    if (!empty($error)) {
        $url = $return_url . (isset($_SESSION['edit']) ? "?id=" . encrypt($_SESSION['edit']) : '');
        error_checker($url);
    }

    if (isset($_SESSION['edit'])) {
        $id = $_SESSION['edit'];
        unset($_SESSION['edit']);

        if (!build_sql_edit('admin', $arr, $id, 'admin_id')) {
            $error['admin'] = 141;
            error_checker($return_url . '   ?id=' . encrypt($id));
        }

        $success['admin'] = 208;
        render_success($return_url . '?id=' .  encrypt($id));
    }


    $password               = generateRandomString();
    $arr['admin_password']  = password_hashing_hybrid_maker_checker($password);
    $arr['admin_id']        = create_id('admin', 'admin_id');
    $id                     = $arr['admin_id'];

    // cout($arr);

    if (!build_sql_insert('admin', $arr)) {
        $error['admin'] = 140;
        error_checker($return_url);
    }

    $email      = $arr['admin_email'];
    $subject    = 'PSYCHX Admin Addition';
    $name       = 'PSYCHX';
    $body       = '<p style="font-family:Poppins, sans-serif;"> ';
    $body       .= 'Hello, <b>' . $arr['admin_name'] . '</b> <br>';
    $body       .= 'You have been successfully onboarded as a <b>' . $name . '</b> admin.<br>';
    $body       .= 'Use <b>' . $password . '</b> as the password to log into the dashboard. <br> ';
    $body       .= '</p>';

    email($email, $subject, $name, $body);
    $success['admin'] = 207;
    render_success($success_url);
}




function post_inquiry()
{
    global $arr;
    global $error;
    global $success;
    $return_url = base_uri . 'contact?suc';

    for_loop();

    $arr['inquiry_id'] = create_id('inquiry', 'inquiry_id');

    if (!build_sql_insert('inquiry', $arr)) {
        $error['inquiry'] = 152;
        error_checker($return_url);
    }

    $email      = 'info@lunafrica.com';
    $subject    = APP_NAME . ' Inquiry';
    $name       = APP_NAME;
    $body       = '<p style="font-family:Poppins, sans-serif;"> ';
    $body       .= 'Hello, admin</b> <br>';
    $body       .= 'You have a new inquiry';
    $body       .= '<br>';
    $body       .= '<b>NAME:</b> ' . $arr['inquiry_name'] . ' <br>';
    $body       .= '<br>';
    $body       .= '<b>EMAIL:</b> ' . $arr['inquiry_email'] . ' <br>';
    $body       .= '<br>';
    $body       .= '<b>PHONE:</b> ' . $arr['inquiry_phone'] . ' <br>';
    $body       .= '<br>';
    $body       .= '<b>MESSAGE:</b> ' . $arr['inquiry_message'] . ' <br>';
    $body       .= '<br>';
    $body       .= 'Log in to your admin dashboard : <a href=" ' . admin_url . ' "> CLICK HERE </a> to respond to the request';


    email($email, $subject, $name, $body);
    $success['inquiry'] = 223;
    render_success($return_url);
}



function post_new_password()
{
    global $arr;
    global $error;
    global $success;
    $return_url = admin_url . 'profile';

    // 	$password = md5($_POST['password']);

    $arr['user_password']       = password_hashing_hybrid_maker_checker($_POST['user_password']);

    if (!build_sql_edit('user', $arr, $_SESSION['user_id'], 'user_id')) {
        $error['user'] = 153;
        error_checker($return_url . '?failed');
    }


    $subject    = APP_NAME . ' Password Change';
    $name       = APP_NAME;
    $body       = '<p style="font-family:Poppins, sans-serif;"> ';
    $body       .= 'Hello, <b> ' . $_SESSION['user_name'] . ' </b> <br>';
    $body       .= 'Your account\'s password has been successfully changed.';
    $body       .= '<br>';
    $body       .= 'You may log in to your account in the future with these new credentials';
    $body       .= '<br>';
    $body       .= '<b>EMAIL:</b> ' . $_SESSION['user_email'] . ' <br>';
    $body       .= '<br>';
    $body       .= '<b>PASSWORD:</b> ' . $_POST['user_password'] . ' <br>';
    $body       .= '<br>';


    email($_SESSION['user_email'], $subject, $name, $body);

    $success['user'] = 224;
    render_success($return_url);
}

function create_id($table, $id)
{
    $date_today = date('Ymd');

    $table_prifix = array(
        'admin'             => 'ADM' . $date_today,
        'banner'            => 'BNR' . $date_today,
        'filter'            => 'FIL' . $date_today,
        'inquiry'           => 'INQ' . $date_today,
        'statistic'         => 'STT' . $date_today,
        'therapist'         => 'THP' . $date_today,
        'speciality'        => 'SPE' . $date_today,
        'company'           => 'CMP' . $date_today,
        'category'          => 'CAT' . $date_today,
        'product'           => 'PRO' . $date_today,
        'bookmark'          => 'BKM' . $date_today,
        'user'              => 'USR' . $date_today,
        'note'              => 'NOT' . $date_today,
        'post'              => 'PST' . $date_today,
        'board'             => 'BRD' . $date_today,
        'therapist_move'    => 'TPM' . $date_today,
        'subscription'      => 'SUB' . $date_today,
        'session'           => 'SES' . $date_today,
        'voucher'           => 'VOC' . $date_today,
        'subscriber'        => 'SUB' . $date_today,
        'corporate'         => 'CPR' . $date_today,
        'employee'          => 'EMP' . $date_today,
        'writer'            => 'UWR' . $date_today,
        'interested_corporate'   => 'ICP' . $date_today,

    );

    $random_str = $table_prifix[$table] . rand_str();

    $get_id     = get_ids($table, $id, $random_str);

    while ($get_id == true) {
        $random_str = $table_prifix[$table] . rand_str();
        $get_id     = get_ids($table, $id, $random_str);
    }
    return $random_str;
}

function delete_file($image, $table, $id_name, $default_path = 'images')
{
    $id_value = $_SESSION['edit'];

    $sql = "select $image from $table where $id_name = '$id_value'";
    $row = select_rows($sql)[0];

    return unlink(TARGET_DIR  . $default_path . '/' . $row[$image]);
}

function for_loop()
{
    global $arr;

    foreach ($_POST as $key => $value) {
        $arr[$key] = security($key);
    }
}
