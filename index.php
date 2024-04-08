<?php
include 'dbcon.php';

$IMGUR_CLIENT_ID = "277e5eeabe9206a";
$statusMsg = $valErr = '';
$status = 'danger';
$imgurData = array();


if(isset($_POST['submit'])){
 
    $validUploads = true; 

    for ($i = 0; $i < 4; $i++) {
        $fieldName = "image_" . $i;

        if (empty($_FILES[$fieldName]["name"])){
            $validUploads = false;
            $valErr = 'Please select all four files to upload.<br/>';
            break;
        }
    }

    if ($validUploads) {
        for ($i = 0; $i < 4; $i++) {
            $fieldName = "image_" . $i;
            $fileType = pathinfo($_FILES[$fieldName]["name"], PATHINFO_EXTENSION);
            $image_source = file_get_contents($_FILES[$fieldName]['tmp_name']);
            $postFields = array('image' => base64_encode($image_source));

          
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.imgur.com/3/image');
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Client-ID '.$IMGUR_CLIENT_ID));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
            $response = curl_exec($ch);
            curl_close($ch);

    
            $responseArr = json_decode($response);

          
            if (!empty($responseArr->data->link)) {
                $imgurData[] = $responseArr;
                $status = 'success';
                $statusMsg = 'The images have been uploaded to Imgur successfully.';
            } else {
                $statusMsg = 'Image upload failed, please try again after some time.';
            }
        }

        $imgurLinks = array();
        foreach ($imgurData as $imageData) {
            $imgurLinks[] = $imageData->data->link;
        }

        $name_sp = $_POST['name_product'];
        $discount_sp = $_POST['discount'];
        $price_sp = $_POST['price_new'];
        $type = $_POST['type_product'];

        $sql_insert_sp = "INSERT INTO tb_plants(id, name, discount, price_new, type_plant) VALUES 
        (NULL,'$name_sp', $discount_sp, $price_sp, '$type')";
        $result_insert_sp = mysqli_query($conn, $sql_insert_sp);

        $lastInsertedID = mysqli_insert_id($conn);
        $update_sql = "UPDATE tb_plants SET picture_main='$imgurLinks[0]', picture_other_1='$imgurLinks[1]', picture_other_2='$imgurLinks[2]', picture_other_3='$imgurLinks[3]' WHERE id=$lastInsertedID";
        $result_update = mysqli_query($conn, $update_sql);
    } else {
        $statusMsg = $valErr;
    }
}
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Thêm sản phẩm mới</title>
    <meta charset="utf-8">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link href="index.css" rel="stylesheet" />
</head>
<body>
    <div class="container">
        <h1 class="title">Nhập thông tin sản phẩm</h1>

        <div class="wrapper">
            <?php if (!empty($statusMsg)) { ?>
                <div class="alert alert-<?php echo $status; ?>"><?php echo $statusMsg; ?></div>
            <?php } ?>
            <div class="column">
                <form method="post" action="" class="form" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Tên sản phẩm</label>
                        <input type="text" name="name_product" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>% giảm giá</label>
                        <input type="text" name="discount" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Giá nhập</label>
                        <input type="text" name="price_new" class="form-control">
                    </div>
                    <?php for ($i = 0; $i < 4; $i++) { ?>
                        <div class="form-group">
                            <label>Image <?php echo $i + 1; ?></label>
                            <input type="file" name="image_<?php echo $i; ?>" class="form-control">
                        </div>
                    <?php } ?>
                    
                    <div class="form-group">
                        <label>Thể loại</label>
                        <input type="text" name="type_product" class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="submit" class="form-control btn-primary" name="submit" value="Thêm sản phẩm"/>
                    </div>
                </form>
            </div>
            <?php if (!empty($imgurData)) { ?>
                <div class="card">
                    <?php foreach ($imgurData as $imageData) { ?>
                        <img src="<?php echo $imageData->data->link; ?>" class="card-img-top" alt="">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $imageData->data->title; ?></h5>
                            <p class="card-text"><?php echo $imageData->data->description; ?></p>
                            <p><b>Imgur URL:</b> <a href="<?php echo $imageData->data->link; ?>" target="_blank"><?php echo $imageData->data->link; ?></a></p>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>
