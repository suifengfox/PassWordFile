<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $siteTitle;?></title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* 整体页面布局和美化 */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f9f9f9;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* 标题样式 */
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
        }

        /* 下载按钮样式 */
        #downloadButton {
            padding: 18px 40px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 18px;
            border: none;
            background-color: #007BFF;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #downloadButton:hover {
            background-color: #0056b3;
        }

        /* 密码模态框样式 */
       .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

       .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 30px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        /* 关闭按钮样式 */
       .close {
            color: #aaa;
            float: right;
            font-size: 32px;
            font-weight: bold;
            transition: color 0.3s ease;
        }

       .close:hover,
       .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* 输入框和确认按钮样式 */
        #passwordInput {
            width: 100%;
            padding: 16px 20px;
            margin: 15px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 16px;
        }

        #confirmPassword {
            padding: 16px 30px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        #confirmPassword:hover {
            background-color: #0056b3;
        }

        /* 修改密码链接样式 */
       .change-password-link {
            color: #007BFF;
            text-decoration: none;
            display: block;
            margin-top: 20px;
            text-align: center;
            font-size: 16px;
            transition: color 0.3s ease;
        }

       .change-password-link:hover {
            color: #0056b3;
        }
    </style>
</head>

<body>
    <h1><?php echo $siteTitle;?></h1>
    <button id="downloadButton"><?php echo $downloadButtonText;?></button>
    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>请输入下载密码：</p>
            <input type="password" id="passwordInput">
            <button id="confirmPassword">确定</button>
        </div>
    </div>
    <a href="change_password.php" class="change-password-link">管理中心</a>

    <script>
        // 获取页面元素
        const downloadButton = document.getElementById('downloadButton');
        const passwordModal = document.getElementById('passwordModal');
        const closeSpan = document.getElementsByClassName("close")[0];
        const passwordInput = document.getElementById('passwordInput');
        const confirmPassword = document.getElementById('confirmPassword');

        // 点击下载按钮，显示密码模态框
        downloadButton.addEventListener('click', function () {
            <?php if ($downloadPassword === "无密码") {?>
                // 如果下载密码为“无密码”，直接执行下载操作
                const fileName = "<?php echo basename($filePath);?>";
                const downloadLink = document.createElement('a');
                downloadLink.href = "<?php echo $filePath;?>";
                console.log("下载链接: " + downloadLink.href);
                downloadLink.download = fileName;
                document.body.appendChild(downloadLink);
                downloadLink.click();
                document.body.removeChild(downloadLink);
            <?php } else {?>
                passwordModal.style.display = "block";
            <?php }?>
        });

        // 点击密码模态框的关闭按钮，隐藏密码模态
        closeSpan.addEventListener('click', function () {
            passwordModal.style.display = "none";
        });

        // 点击密码模态框的确认按钮，验证密码（这里仅为模拟，实际需后端验证）
        confirmPassword.addEventListener('click', function () {
            const password = passwordInput.value;
            <?php if ($downloadPassword!== "无密码") {?>
                if (password === "<?php echo $downloadPassword;?>") {
                    // 这里模拟密码验证，实际应通过后端接口验证
                    const fileName = "<?php echo basename($filePath);?>";
                    const downloadLink = document.createElement('a');
                    downloadLink.href = "<?php echo $filePath;?>";
                    downloadLink.download = fileName;
                    document.body.appendChild(downloadLink);
                    downloadLink.click();
                    document.body.removeChild(downloadLink);

                    alert('密码验证成功，进行下载操作');
                    passwordModal.style.display = "none";
                } else {
                    alert('密码错误，请重新输入');
                }
            <?php }?>
        });

        // 点击页面空白处，隐藏密码模态框
        window.onclick = function (event) {
            if (event.target === passwordModal) {
                passwordModal.style.display = "none";
            }
        };
    </script>
</body>

</html>
