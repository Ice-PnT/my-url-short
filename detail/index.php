<?php 
    if($_GET['KEY']){

        require_once("../db/urls.php");

        $KEY = $_GET['KEY'];

        $sql = "SELECT * FROM urls WHERE url_key = :url_key";
        $sth = $URL_DB->prepare($sql);
        $sth->execute(array(':url_key' => $KEY));
        $data = $sth->fetch(PDO::FETCH_ASSOC);
        if($data){
             $sql = "SELECT * FROM urls_load WHERE load_url_key = :url_key";
            $sth = $URL_DB->prepare($sql);
            $sth->execute(array(':url_key' => $KEY));
            while($row = $sth->fetch(PDO::FETCH_ASSOC)){
                $data['DATA_LOAD']['ALL'] += 1;
                $data['DATA_LOAD']['STT']['Os'][$row['load_os']] += 1;
                $data['DATA_LOAD']['STT']['Browser'][$row['load_browser']] += 1;
                $data['DATA_LOAD']['STT']['Device'][$row['load_device']] += 1;
            }
        }else{
            header('Location: https://myurlshort.com/');
            exit;
        }
    }else{
        header('Location: https://myurlshort.com/');
        exit;
    }
?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Short URL</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Kodchasan:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
        <link rel="stylesheet" href="../assets/css/style.css">
        <link rel="stylesheet" href="../assets/css/detail.css">
    </head>
    <body>
        <div class="root">
            <div class="container-xl mt-3">
                <h3>รายงานเข้าเข้าชม</h3>
                <p>
                    รายงานการเข้าชมของ URL : <a class="me-2" href="https://myurlshort.com/<?php echo $data['url_key'] ?>">https://myurlshort.com/<?php echo $data['url_key'] ?></a>
                    Qr Code : <a href="https://myurlshort.com/image/qr/<?php echo $data['url_key'] ?>.png" class="icon-hover">
                        <svg xmlns="http://www.w3.org/2000/svg" height="4em" viewBox="0 0 448 512">
                            <path d="M0 80C0 53.5 21.5 32 48 32h96c26.5 0 48 21.5 48 48v96c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V80zM64 96v64h64V96H64zM0 336c0-26.5 21.5-48 48-48h96c26.5 0 48 21.5 48 48v96c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V336zm64 16v64h64V352H64zM304 32h96c26.5 0 48 21.5 48 48v96c0 26.5-21.5 48-48 48H304c-26.5 0-48-21.5-48-48V80c0-26.5 21.5-48 48-48zm80 64H320v64h64V96zM256 304c0-8.8 7.2-16 16-16h64c8.8 0 16 7.2 16 16s7.2 16 16 16h32c8.8 0 16-7.2 16-16s7.2-16 16-16s16 7.2 16 16v96c0 8.8-7.2 16-16 16H368c-8.8 0-16-7.2-16-16s-7.2-16-16-16s-16 7.2-16 16v64c0 8.8-7.2 16-16 16H272c-8.8 0-16-7.2-16-16V304zM368 480a16 16 0 1 1 0-32 16 16 0 1 1 0 32zm64 0a16 16 0 1 1 0-32 16 16 0 1 1 0 32z"/>
                        </svg>
                    </a>
                </p>
                <div class="row mt-3">
                    <?php if(!isset($data['DATA_LOAD']['ALL'])){ ?>
                    <div class="col">
                        <p class="no-data">ข้ออภัยตอนนี้ ไม่พบข้อมูลการเข้าชมใดๆ</p>
                    </div>
                    <?php }  ?>
                    <?php foreach($data['DATA_LOAD']['STT'] as $key => $val){ ?>
                    <div class="col">
                        <div class="card card-body mb-5">
                            <h4><?php echo $key ?></h4>
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th><?php echo $key ?></th>
                                        <th>จำนวน</th>
                                        <th>%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($val as $key2 => $val2){ ?>
                                    <tr>
                                        <td><?php echo $key2 ?></td>
                                        <td><?php echo $val2 ?></td>
                                        <td><?php echo number_format(($val2/$data['DATA_LOAD']['ALL'])*100) ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="modal-contents">
            <div class="modal fade modal-notify" data-bs-backdrop="static" data-bs-keyboard="false" id="modal-notify" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="modal-notify-content">
                                <div class="modal-notify-icon">
                                    <i></i>
                                </div>
                                <div class="modal-notify-text">
                                    <h6></h6>
                                    <p></p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">ปิด</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
        <script src="../assets/js/script.js"></script> 
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    </body>

</html>