<?php
    require_once("application_db.php");
    header("Access-Control-Allow-Origin: *");
    header("Content-type: application/json");
    require_once("../../response_api.php");
    
    // Kiểm tra phương thức 
    if ($_SERVER['REQUEST_METHOD'] != 'GET') {
        error_response(1, "API chỉ hỗ trợ phương thức GET");
    }

    $applications = get_applications();
    success_response($applications, "Lấy danh sách đơn thành công");
?>