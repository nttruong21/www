<?php

	session_start();
	require_once('../connect_db.php');
	// Kiểm tra người dùng đã đăng nhập chưa 
	if (!isset($_SESSION['maNhanVien'])) {
		header("Location: ../login.php");
		die();
	}

	// Kiểm tra người dùng đã đổi mật khẩu chưa
	if (isset($_SESSION['doiMatKhau'])) {
        if ($_SESSION['doiMatKhau'] == 0) {
            header("Location: ../change_pwd_first.php");
            die();
        } 
    }

	// Kiểm tra người dùng có phải trưởng phòng?
    if (!$_SESSION['maChucVu'] == 'TP') {
        header("Location: /no_access.html");
    }

    
    if(isset($_POST['guiDon'])) {
        $message = '';
        if (!isset($_POST['maNVDayOff'])  || !isset($_POST['time']) || !isset($_POST['lyDo'])){
          $message = 'vui lòng nhập đầy đủ thông tin!!';
          
        }else if (empty($_POST['maNVDayOff']) || empty($_POST['time']) || empty($_POST['lyDo'])){
          $message = 'KHông để giá trị rỗng!!';
          
        }else{
            $maNVien = $_POST['maNVDayOff'];
            $maPBan = $_SESSION['maPB'];
            $soNgayNghi = $_POST['time'];
            $lyDo = $_POST['lyDo'];
            $ngayTao = date("Y-m-d");
            $trangThai = $_POST['trangThai'];

            if (!isset($_FILES["file"]))
            {
                $message =  "Dữ liệu không đúng cấu trúc";
                
            }

            $tapTin = $_FILES["file"]['name'];
            if ($tapTin == ''){
                $sql2 = "INSERT INTO DonXinNghiPhep (maNhanVien, maPhongBan, soNgayNghi, trangThai, lyDo, ngayTao, tapTin) VALUES(?, ?, ?,?, ?, ?,?)";
                $conn2 = connect_db();
                $stm2 = $conn2->prepare($sql2);
                $stm2->bind_param('sssssss', $maNVien, $maPBan, $soNgayNghi, $trangThai, $lyDo, $ngayTao, $tapTin);
                $stm2->execute();
                if($stm2->affected_rows == 1){
            
                    header("Location: dayOff_list.php");
                  }else{
                    $message = "cập nhật thất bại";
                  }
            
            }else {
                $tname = $_FILES["file"]["tmp_name"];
                $uploads_dir = '../files';

                $sql2 = "INSERT INTO DonXinNghiPhep (maNhanVien, maPhongBan, soNgayNghi, trangThai, lyDo, ngayTao, tapTin) VALUES(?, ?, ?,?, ?, ?,?)";
                $conn2 = connect_db();
                $stm2 = $conn2->prepare($sql2);
                $stm2->bind_param('sssssss', $maNVien, $maPBan, $soNgayNghi, $trangThai, $lyDo, $ngayTao, $tapTin);
                $stm2->execute();
                if($stm2->affected_rows == 1){
                    move_uploaded_file($tname,$uploads_dir.'/'.$tapTin);
                    header("Location: dayOff_list.php");
                }else{
                    $message = "cập nhật thất bại";
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="/style.css"> <!-- Sử dụng link tuyệt đối tính từ root, vì vậy có dấu / đầu tiên -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
	<title>Trang thông tin nhiệm vụ mới</title>
</head>

<body>
	<?php
		require_once('sidebar_searchTask.php');
	?>
	<div>
		<div class="row m-0">
			
			<?php
				require_once('sidebar.php');
			?>
			<div class="col-xl-10 col-lg-10 col-md-10 col-sm-10 col-10 rounded border border-left-0 border-right-0 border-bottom-0">
				<div class="e__dayOff-content">
					<h5 class="p-2 d-flex justify-content-center bg-dark text-white tex-center">
						<i class="fas fa-hand-point-left"></i>
						ĐƠN XIN NGHỈ PHÉP
					</h5>
					
					<form action="" validate method="post" enctype="multipart/form-data">
                            <div class="modal-body mx-5">
                                <div class="form-group">
                                    <label for="maNVDayOff">Mã nhân viên</label>
                                    <input type="text" value="<?=$_SESSION['maNhanVien']?>"class="form-control" id="maNVDayOff" name="maNVDayOff" readonly />
                                </div>
                                <div class="form-group">
                                    <label for="time">Chọn số ngày nghĩ</label>
                                    <select name="time" class="form-control">
                                        <?php
                                            for($i=1;$i<=12;$i++){
                                                echo "<option value='$i'>$i</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="lyDo">Lý do</label>
                                    <textarea rows="2" id="lyDo" class="form-control" name="lyDo" placeholder="Nhập lý do" required></textarea>
                                
                                </div>
                                <div class="form-group">
                                    <label for="file">Tệp đính kèm</label>
                                    <input type="file" class="form-control" name="file">
                                </div>
                                <div class="form-group">
                                    <label for="trangThai">Trạng thái</label>
                                    <input type="text" readonly  class="form-control" id="m-trangThai"  name="trangThai" value="WAITING" />
                                </div>
                                
                            </div>

                            <div class="modal-footer">

                                <button type="submit" id='m-smguiDon' name="guiDon" class='btn btn-outline-success '>Gửi đơn</button>
                                
                            </div>
                        </form>
				</div>
			</div>
    	</div>	
	</div>


	<!-- message response -->
	
	<div class="modal fade" id="message-respone">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Notification</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <p id="responseMess"></p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Confirm</button>
                </div>
            </div>
        </div>
    </div>


	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>

	<!-- Sử dụng link tuyệt đối tính từ root, vì vậy có dấu / đầu tiên -->
	
</body>

</html>