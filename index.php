<?php 
  if($_GET&&$_GET['KEY']){

    $KEY = $_GET['KEY']? $KEY = explode("/", $_GET['KEY']) : [];
    if(count($KEY) === 1){

      require_once("db/urls.php");
      require_once("getOS.php");
      require_once("getBrowser.php");
      require_once("getDevice.php");

      $url_key = $KEY[0];
      $sql = "SELECT url_full FROM urls WHERE url_key = :url_key";
      $sth = $URL_DB->prepare($sql);
      $sth->execute(array(':url_key' => $url_key));
      $data = $sth->fetch(PDO::FETCH_ASSOC);

      if($data&&$data['url_full']){
        $sql = "INSERT INTO urls_load (load_url_key, load_datetime, load_os, load_browser, load_device) VALUES (:load_url_key, :load_datetime, :load_os, :load_browser, :load_device)";
        $sth = $URL_DB->prepare($sql);

        $load_datetime = date("Y-m-d H:i:s");
        $load_os = getOS();
        $load_browser = getBrowser();
        $load_device = getDevice();

        $sth->execute(array(':load_url_key' => $url_key, ':load_datetime' => $load_datetime, ':load_os' => $load_os, ':load_browser' => $load_browser, ':load_device' => $load_device));
        header("Location:".$data['url_full']);
        exit;
      } 
    }
  }
?>
<!doctype html>
<html>
 <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <title>Short URL</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Kodchasan:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/style.css">
  </head>
  <body>
    <div class="root">
        <section>
          <div class="container mt-5 ">
              <h1>Short URL</h1>
              <p>คุณสามารถนำ URL ที่ต้องลดความยาวหรือย่อลง ผ่าน <a href="https://myurlshort.com/">https://myurlshort.com/</a> ใช้ไม่มีหมดอายุการใช้งาน</p>
              <div class="content-form">
                <form class="row needs-validation" novalidate>
                  <div class="col-12">
                    <div class="mb-3">
                      <label for="url-full">ระบุ URL ที่ต้องการย่อ</label>
                      <input type="text" class="form-control form-control-lg" id="url-full" name="url_full" placeholder="ระบุ URL ที่ต้องการ..." required>
                      <div class="invalid-feedback">
                        โปรดระบุ URL บนช่องนี้
                      </div>
                    </div>
                  </div>
                  <div class="col-12">
                    <label for="url-key">ระบุ URL ที่ต้องการด้วยตัวเอง</label>
                    <div class="input-group mb-3">
                      <span class="input-group-text" id="basic-url-key">https://myurlshort.com/</span>
                      <input type="text" class="form-control form-control-lg" id="url-key" pattern="[A-Za-z0-9]{5}" placeholder="ระบุอักษร หรือ ตัวเลข 5 ตัว" aria-describedby="basic-url-key">
                      <div class="invalid-feedback">
                        โปรดระบุอักษรภาษาอังกฤษ หรือ ตัวเลข 5 ตัว บนช่องนี้
                      </div>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="mt-2">
                      <button class="btn btn-primary btn-lg" type="submit" id="button-sunmit-short">Short URL</button>
                    </div>
                  </div>
                </form>
              </div>
              <div class="content-list">
                <div class="list-group urls-group">
                  <div class="list-group-item list-group-item-action">
                      <div class="content-left">
                        <div class="short-qr">
                          <img src="" alt="image" />
                        </div>
                        <div class="short-url">
                          <div class="url-new">
                            <label>URL ใหม่: </label>
                            <a href="" class="url"></a>
                            <a href="javascript:;" class="btn-copy icon-hover">
                              <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                                <path d="M384 336H192c-8.8 0-16-7.2-16-16V64c0-8.8 7.2-16 16-16l140.1 0L400 115.9V320c0 8.8-7.2 16-16 16zM192 384H384c35.3 0 64-28.7 64-64V115.9c0-12.7-5.1-24.9-14.1-33.9L366.1 14.1c-9-9-21.2-14.1-33.9-14.1H192c-35.3 0-64 28.7-64 64V320c0 35.3 28.7 64 64 64zM64 128c-35.3 0-64 28.7-64 64V448c0 35.3 28.7 64 64 64H256c35.3 0 64-28.7 64-64V416H272v32c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16V192c0-8.8 7.2-16 16-16H96V128H64z"/>
                              </svg>
                            </a>
                          </div>
                          <div class="url-old">
                            <label>URL เดิม: </label>
                            <p></p>
                          </div>
                        </div>
                      </div>
                      <div class="content-right">
                        <div class="load-url">
                          <label>เข้าชม</label>
                          <a href="#"></a>
                        </div>
                      </div>
                  </div>
                </div>
              </div>
          </div>
        </section>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="assets/js/script.js"></script> 
    <script>
      //
      const textAlertKey = "โปรดระบุอักษรภาษาอังกฤษ หรือ ตัวเลข 5 ตัว บนช่องนี้";

      //Set Element
      const elUrlsGroup = document.querySelector('.content-list .urls-group');
      const elListGroup = elUrlsGroup.querySelector('.list-group-item');
      const cloneListGroup = elListGroup.cloneNode(true);
      elListGroup.remove();


      async function postData(url = "", data = {}) {
        const response = await fetch(url, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            // 'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: JSON.stringify(data), 
        });
        return response.json(); 
      }

      async function getData(url = "") {
        const response = await fetch(url);
        return response.json(); 
      }

      const setListURL = (data, badge = false) => {
        data.map((value) => {
            //remove .badge-new
            document.querySelectorAll(".badge-new").forEach((el)=>{
              console.log(el)
              el.remove();
            })
            //
            if(!document.querySelector('.list-group-item[data-key="'+value.url_key+'"]')){
              let thisListGroup = cloneListGroup.cloneNode(true);
              //Add .badge-new
              if(badge){
                let span = document.createElement("span")
                span.setAttribute("class", "badge badge-new text-bg-danger");
                span.innerHTML = "NEW";
                thisListGroup.append(span);
              }
              thisListGroup.setAttribute("data-key", value.url_key);
              thisListGroup.querySelector('.short-qr img').src = `https://myurlshort.com/image/qr/${value.url_key}.png`;
              thisListGroup.querySelector('.short-url .url-new a.url').href = `https://myurlshort.com/${value.url_key}`;
              thisListGroup.querySelector('.short-url .url-new a.url').innerHTML = `https://myurlshort.com/${value.url_key}`;
              thisListGroup.querySelector('.short-url .url-new a.btn-copy').addEventListener("click", (event)=>{
                navigator.clipboard.writeText(`https://myurlshort.com/${value.url_key}`);
              })
              thisListGroup.querySelector('.short-url .url-old p').innerHTML = value.url_full;
              thisListGroup.querySelector('.load-url a').href = `https://myurlshort.com/detail/?KEY=${value.url_key}`
              thisListGroup.querySelector('.load-url a').innerHTML = value.url_count_load;
              // elUrlsGroup.append(thisListGroup);
              elUrlsGroup.insertAdjacentElement('afterbegin', thisListGroup)
            }
          })
      }
    </script>
    <script>

      //Get data
      getData("https://myurlshort.com/api/ALL").then((data) => {
        if(data&&data.length > 0){
          setListURL(data);
        }
      });

      //Check Key
      pntEvent("keyup", "#url-key", (event) => {
        const el = event.target;
        const urlkey = el.value;
        urlkey.length > 0? el.required = true : el.required = false;

        el.nextElementSibling.innerHTML = textAlertKey;
        if(urlkey.length === 5){
          const found = urlkey.match(/\w/g);
          getData("https://myurlshort.com/api/"+urlkey).then((data) => {
            if(data&& Object.values(data).length > 0){
              el.nextElementSibling.innerHTML = "ไม่สามารถใช้ URL นี้ได้ โปรดระบุใหม่อีกครั้ง"
              el.nextElementSibling.style.display = 'block';
            }else if(found.length === 5){
              el.nextElementSibling.style.display = 'none';
            }else{
              el.nextElementSibling.style.display = 'block';
            }
          });
        }
      })

      //Submit form
      pntEvent('submit', 'form', (event)=>{
        event.preventDefault();

        const form = event.target;
        if (!form.checkValidity()) {
          event.stopPropagation()
          form.classList.add('was-validated');
        }else{
          form.classList.remove('was-validated');

          postData("https://myurlshort.com/api/", {
            url_full: form.querySelector('#url-full').value,
            url_key: form.querySelector('#url-key').value
          }).then((data) => {
            setListURL([data], true);

            form.querySelector('#url-full').value = '';
            form.querySelector('#url-key').value = '';
          });
        }
      })
    </script>
  </body>
</html>