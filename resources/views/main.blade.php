<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <link rel="icon" href="/icons/iconpage.jpg" type="">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="fonts/icomoon/style.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Style -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/styless.css"> <!-- dropdown -->
    <title>Convert Files</title>
</head>

<body>
    <div>
        <nav class="navbar navbar-expand-lg fixed-top" style="background-color: #333333;">
            <div class="container-fluid">
                <a class="navbar-brand" href="">
                    <img width="40px" height="40px" src="/images/logo.png" alt="">
                </a>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a style="color: white;" class="nav-link active" aria-current="page" href="">Home</a>
                        </li>
                        <li class="nav-item">
                            <a style="color: white;" class="nav-link" href="{{ route('profile')}}">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a style="position: absolute;text-align: start; right: 20px;color:white" class="nav-link" href="{{ route('login')}}">Log Out</a>
                        </li>
                        <li class="nav-item">
                            <a style="color: white;" class="nav-link" href="{{ route('profile')}}">About Us</a>
                        </li>
                    </ul>

                </div>
            </div>
        </nav>
    </div>
    <div style="display: flex;" class="container shadow">
        <div class="border-right" style="flex-basis: 40%;padding-top: 100px;">
            <h1>Logo</h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sed quasi quisquam assumenda corrupti, odit accusamus. Dignissimos placeat tempora quos, voluptatum vel eos quaerat obcaecati nisi nobis animi a ex quasi.</p>
        </div>
        <div style="flex-basis: 60%;">
            <div style="margin-left: 10px;">
                <title>Upload file</title>
                <style>
                    #drop-area {
                        margin-top: 100px;
                        border: 2px dashed #ccc;
                        padding: 20px;
                        text-align: center;
                    }

                    #file-input {
                        display: none;
                    }

                    #file-label {
                        display: block;
                        margin: 20px auto;
                        text-align: center;
                        cursor: pointer;
                        padding: 10px;
                        background-color: #2b2d42;
                        color: white;
                        border-radius: 5px;
                        width: 150px;
                    }
                </style>
                <form action="" method="post">
                    <div id="drop-area">
                        <h1 id="text">Drag and Drop file here</h1>
                        <p id="or">or</p>
                        <label for="file-input" id="file-label">Select File</label>
                        <input type="file" id="file-input" name="file-input" accept=".pdf,.doc,.docx,.txt" />
                    </div>
                </form>
                <script>
                    const dropAreaa = document.getElementById('drop-area');
                    const fileInput = document.getElementById('file-input');
                    const fileLabel = document.getElementById('file-label');

                    document.getElementById('file-input').addEventListener('change', function() {
                        var file = this.files[0];
                        var fileName = file.name;
                        var filename = fileName.split('.').pop();
                        if (filename == 'docx') {
                            $("#1").css({
                                "pointer-events": "none",
                                "background-color": "#cccccc"
                            });
                        }
                        // if (file != null) {
                        //     var myname = document.getElementById('text'); // Tìm phần tử span trong HTML
                        //     myname.innerHTML = fileName; // Gán giá trị vào phần tử span
                        // }

                    });

                    // Prevent default drag behaviors
                    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                        dropAreaa.addEventListener(eventName, preventDefaults, false);
                        document.body.addEventListener(eventName, preventDefaults, false);
                    });

                    // Highlight drop area when item is dragged over
                    ['dragenter', 'dragover'].forEach(eventName => {
                        dropAreaa.addEventListener(eventName, highlight, false);
                    });

                    // Unhighlight drop area when item is dragged out
                    ['dragleave', 'drop'].forEach(eventName => {
                        dropAreaa.addEventListener(eventName, unhighlight, false);
                    });

                    // Handle dropped files
                    dropAreaa.addEventListener('drop', handleDrop, false);
                    fileInput.addEventListener('change', handleFileSelect, false);

                    function preventDefaults(e) {
                        e.preventDefault();
                        e.stopPropagation();
                    }

                    function highlight(e) {
                        dropAreaa.classList.add('highlight');
                    }

                    function unhighlight(e) {
                        dropAreaa.classList.remove('highlight');
                    }

                    function handleDrop(e) {
                        const files = e.dataTransfer.files;
                        handleFiles(files);
                    }

                    function handleFileSelect(e) {
                        const files = e.target.files;
                        handleFiles(files);
                    }

                    function handleFiles(files) {
                        if (files.length > 1) {
                            alert('Chỉ cho phép tải lên một tệp duy nhất.');
                            return;
                        }
                        const file = files[0];
                        var fileName = file.name;
                        if ((fileName.match('.txt')) || (fileName.match('.pdf')) || (fileName.match('.docx'))) {
                            var myname = document.getElementById('text'); // Tìm phần tử span trong HTML
                            myname.innerHTML = fileName;
                        } else {
                            alert('Chỉ cho phép tải lên các định dạng .pdf, .docx, .txt');
                        }
                        // Do something with the file, such as uploading to server
                        // console.log(file);
                    }
                    // xử lí
                </script>
            </div>
            <div style="padding-top: 40px; margin: 20px 0 300px 0;">
                <p style="color:black;margin-left: 10px;">Convert to</p>
                <div style="display: flex;justify-content: center;">
                    <div class="mr-3">
                        <a id="1" class="btn btn-light shadow-sm" style="border-radius: 10px;width: 200px; height: 60px;padding-top: 15px;" href="">
                            <img src="/icons/vnd.openxmlformats-officedocument.wordprocessingml.document.png" style="width: 30px;height: 30px;" alt="">
                            Word</a>
                    </div>
                    <div class="mr-3">
                        <a id="2" class="btn btn-light shadow-sm" style="border-radius: 10px;width: 200px; height: 60px;padding-top: 15px;" href="https://code.jquery.com/jquery-3.6.3.min.js">
                            <img src="/icons/pdf.png" style="width: 30px;height: 30px;" alt="">
                            PDF</a>
                    </div>
                    <div id="3" class="mr-3">
                        <a class="btn btn-light shadow-sm" style="border-radius: 10px;width: 200px; height: 60px;padding-top: 15px;" href="">
                            <img src="/icons/txt.png" style="width: 30px;height: 30px;" alt="">
                            TXT</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .ul {
            text-align: center;
        }

        .ul .li {
            display: inline-block;
            margin-right: 15px;
            transition: all 0.3s ease-in-out;
        }

        .ul .li:hover .submenu {
            height: 150px;
            /* chiều cao của hover dropdown */
        }

        .ul .li:hover .a {
            color: #000000;
        }

        .ul .li:hover .a::before {
            visibility: visible;

            /* transform: scale(1, 1); */
        }

        .ul .li .submenu {
            overflow: hidden;
            position: absolute;
            left: 0;
            width: 100%;
            background-color: white;
            height: 0;
            line-height: 40px;
            box-sizing: border-box;
            transition: height 0.3s ease-in-out;
            transition-delay: 0.1s;
        }

        .ul .li .submenu .a {
            color: #fff;
            margin-top: 20px;
            font-size: 16px;
        }

        .ul .li .submenu .a:hover {
            color: #fff;
            text-decoration: underline;
        }

        .ul .li .a {
            color: #999;
            display: block;
            padding: 8px 0 7px 0px;
            margin: 0 0 10px;
            text-decoration: none;
            position: relative;
        }

        .ul .li .a::before {
            content: "";
            position: absolute;
            width: 100%;
            height: 3px;
            bottom: -10px;
            left: 0px;
            background-color: #3862a0;
            transition: all 0.2s ease-in-out;
            transform: scale(0, 0);
            visibility: hidden;
        }
    </style>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</body>
<footer class="" style="background-color: #333333;">

    <div style="display: flex;justify-content: center;">
        <div class="footer-left">

            <img width="200px" height="200px" src="/images/logo.png" alt="">

            <p class="" style="color:white">Công ty TNHH T4TEK © 2022</p>
        </div>

        <div class="" style="color:white;margin: 55px 200px 0 200px;">
            <div class="" style="margin-bottom: 20px;width: 500px;height: 50px;">

                <p style="color:white"><i class="fa-solid fa-location-dot" style="margin-right: 5px;"></i>L18-11-13, Tầng 18 Tòa nhà Vincom Center Đồng Khởi, Số 72 Lê Thánh Tôn, Phường Bến Nghé, Quận 1, Thành phố Hồ Chí Minh</p>
            </div>
            <div style="margin-bottom: 20px;">
                <p style="color:white"> <i class="fa-solid fa-phone" style="margin-right: 5px;"></i> 0965643046</p>

            </div>
            <div>
                <p style="color:white"><i class="fa-solid fa-envelope" style="margin-right: 5px;"></i>contact@t4tek.co</p>
            </div>

        </div>

        <div class="" style="display: flex;">
            <a href="">
                <div style="margin:55px 10px 0 0;">
                    <img style="width: 50px;height: 50px;" class=" rounded-circle" src="/images/fb.png" alt="">
                </div>
            </a>
            <a href="http://zalo.me/518410350895218680?src=qr">
                <div style="margin:55px 10px 0 0;">
                    <img style="width: 50px;height: 50px;" class=" rounded-circle" src="https://cdn.haitrieu.com/wp-content/uploads/2022/01/Logo-Zalo-Arc.png" alt="">
                </div>
            </a>
            <a href="">
                <div style="margin:55px 10px 0 0;">
                    <img style="width: 50px;height: 50px;" class=" rounded-circle" src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e7/Instagram_logo_2016.svg/2048px-Instagram_logo_2016.svg.png" alt="">
                </div>
            </a>
            <a href="">
                <div style="margin:55px 10px 0 0;">
                    <img style="width: 50px;height: 50px;" class=" rounded-circle" src="/images/link.png" alt="">
                </div>
            </a>
        </div>
    </div>

</footer>

</html>