<?php
        require_once("departmental_db.php");
        header("Access-Control-Allow-Origin: *");
        header("Content-type: application/json");
        require_once("../../response_api.php");

        // Kiểm tra phương thức 
        if ($_SERVER['REQUEST_METHOD'] != 'PUT') {
            error_response(1, "API chỉ hỗ trợ phương thức PUT");
        }

        // Đọc JSON từ Client 
        $input = json_decode(file_get_contents('php://input'));

        // Kiểm tra dữ liệu 
        if (is_null($input)) {
            error_response(2, "Dữ liệu phải ở dạng JSON");
        }
        if (!property_exists($input, 'id') || !property_exists($input, 'name') || !property_exists($input, 'num') || 
        !property_exists($input, 'desc')) {
            error_response(3, "Dữ liệu không đầy đủ");
        }

        // Lấy dữ liệu 
        $id = $input -> id;
        $name = $input -> name;
        $num = $input -> num;
        $desc = $input -> desc;

       // Kiểm tra dữ liệu có hợp lệ?
        if($id === "" || $name === "" || ($num) === "" || ($desc) === "") {
            error_response(4, "Dữ liệu không hợp lệ");
        }

        $result = update_departmental($id, $name, $num, $desc);
        if ($result) {
            success_response(0, "Cập nhật thông tin phòng ban thành công");
        } else {
            error_response(5, "Cập nhật thông tin phòng ban thất bại");
        }
?>