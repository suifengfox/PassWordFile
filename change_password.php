<?php
// 处理修改密码和路径的逻辑
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'change_password_path') {
    $originalPassword = $_POST['original_password'];
    $newPassword = isset($_POST['new_password']) && $_POST['new_password']!== ""
       ? $_POST['new_password'] : null;
    $newFilePath = isset($_POST['new_file_path']) && $_POST['new_file_path']!== ""
       ? $_POST['new_file_path'] : null;
    $newDownloadPassword = isset($_POST['new_download_password']) && $_POST['new_download_password']!== ""
       ? $_POST['new_download_password'] : null;

    require_once 'config.php';
    if ($originalPassword === $correctPassword) {
        $currentPassword = $newPassword?? $correctPassword;
        $currentFilePath = $newFilePath?? $filePath;
        $downloadPassword = $newDownloadPassword?? $downloadPassword;

        // 更新密码、路径到配置文件
        $configContent = "<?php \$correctPassword = \"$currentPassword\"; \$filePath = \"$currentFilePath\"; \$getPasswordText = \"$getPasswordText\"; \$siteTitle = \"$siteTitle\"; \$downloadPassword = \"$downloadPassword\"; \$downloadButtonText = \"$downloadButtonText\";?>";
        file_put_contents('config.php', $configContent);
        echo "<script>alert('修改成功！');</script>";
    } else {
        echo "<script>alert('管理员密码错误，修改失败！');</script>";
    }
}

// 用于存储网站标题验证状态
$siteTitleVerificationSuccess = false;
// 处理修改网站标题的逻辑
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] =='site_info') {
    $originalPasswordForSite = $_POST['original_password'];
    $newSiteTitle = isset($_POST['new_site_title']) && $_POST['new_site_title']!== ""
       ? $_POST['new_site_title'] : null;
    $newDownloadButtonText = isset($_POST['new_download_button_text']) && $_POST['new_download_button_text']!== ""
       ? $_POST['new_download_button_text'] : null;

    require_once 'config.php';
    if ($originalPasswordForSite === $correctPassword) {
        $siteTitle = $newSiteTitle?? $siteTitle;
        $downloadButtonText = $newDownloadButtonText?? $downloadButtonText;

        // 更新网站标题和主页标题到配置文件
        $configContent = "<?php \$correctPassword = \"$correctPassword\"; \$filePath = \"$filePath\"; \$getPasswordText = \"$getPasswordText\"; \$siteTitle = \"$siteTitle\"; \$downloadPassword = \"$downloadPassword\"; \$downloadButtonText = \"$downloadButtonText\";?>";
        file_put_contents('config.php', $configContent);
        echo "<script>alert('修改成功！');</script>";
    } else {
        echo "<script>alert('管理员密码错误，无法进入网站信息界面！');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理中心</title>
    <!-- 引入 mdui 的 CSS 文件 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/mdui@1.0.2/dist/css/mdui.min.css">
    <style>
       .mdui-container {
            max-width: 500px;
            margin: 0 auto;
        }

       .mdui-textfield {
            margin-bottom: 20px;
        }

       .fixed-btn {
            position: fixed;
            transition: background-color 0.3s ease;
        }

       .back-to-index-btn {
            left: 20px;
            top: 20px;
            padding: 10px 20px;
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

       .back-to-index-btn:hover {
            background-color: #5a6268;
        }

       .forgot-password-btn {
            right: 20px;
            top: 20px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

       .forgot-password-btn:hover {
            background-color: #0056b3;
        }

       .site-info-btn {
            left: 20px;
            bottom: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

       .site-info-btn:hover {
            background-color: #218838;
        }

       .site-info-popup {
            display: none;
            position: fixed;
            z-index: 3;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 30px;
            border: 1px solid #888;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 80%;
            max-width: 500px;
        }

       .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
        }

       .mdui-textfield-input {
            padding: 12px;
            font-size: 14px;
        }

       .mdui-textfield-label {
            font-size: 14px;
            line-height: 1.5;
            padding-left: 5px;
        }
    </style>
</head>

<body>
    <button class="back-to-index-btn" onclick="window.location.href = 'index.php'">返回首页</button>
    <button class="forgot-password-btn" onclick="showForgotPasswordAlert()">忘记密码</button>
    <button class="site-info-btn" onclick="showSiteInfoForm()">网站信息</button>

    <div class="mdui-container">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <input type="hidden" name="action" value="change_password_path">
            <div class="mdui-textfield mdui-textfield-floating-label">
                <label class="mdui-textfield-label">输入管理员密码：</label>
                <input type="password" id="original_password" name="original_password" required class="mdui-textfield-input">
            </div>
            <div class="mdui-textfield mdui-textfield-floating-label">
                <label class="mdui-textfield-label">输入新密码（可选）：</label>
                <input type="password" id="new_password" name="new_password" class="mdui-textfield-input">
            </div>
            <div class="mdui-textfield mdui-textfield-floating-label">
                <label class="mdui-textfield-label">输入新的文件下载路径（可选）：</label>
                <input type="text" id="new_file_path" name="new_file_path" class="mdui-textfield-input">
            </div>
            <div class="mdui-textfield mdui-textfield-floating-label">
                <label class="mdui-textfield-label">输入新的下载密码（可选，填“无密码”不需要密码）：</label>
                <input type="text" id="new_download_password" name="new_download_password" class="mdui-textfield-input">
            </div>
            <button type="submit" class="mdui-btn mdui-color-theme-accent mdui-ripple">确认修改</button>
        </form>
    </div>

    <div id="siteInfoForm" class="site-info-popup">
        <span class="close-btn" onclick="closeSiteInfoForm()">&times;</span>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <input type="hidden" name="action" value="site_info">
            <div class="mdui-textfield mdui-textfield-floating-label">
                <label class="mdui-textfield-label">输入管理员密码：</label>
                <input type="password" id="original_password_for_site" name="original_password" required class="mdui-textfield-input">
            </div>
            <div class="mdui-textfield mdui-textfield-floating-label">
                <label class="mdui-textfield-label">输入新的网站标题（可选）：</label>
                <input type="text" id="new_site_title" name="new_site_title" class="mdui-textfield-input">
            </div>
            <div class="mdui-textfield mdui-textfield-floating-label">
                <label class="mdui-textfield-label">输入主页下载按钮文字（可选，文件下载按钮文字）（可选）：</label>
                <input type="text" id="new_download_button_text" name="new_download_button_text" class="mdui-textfield-input">
            </div>
            <button type="submit" class="mdui-btn mdui-color-theme-accent mdui-ripple">确认修改</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/mdui@1.0.2/dist/js/mdui.min.js"></script>
    <script>
        function showForgotPasswordAlert() {
            alert('请前往 config.php 文件中修改指定参数');
        }

        function showSiteInfoForm() {
            document.getElementById('siteInfoForm').style.display = 'block';
        }

        function closeSiteInfoForm() {
            document.getElementById('siteInfoForm').style.display = 'none';
        }
    </script>
</body>

</html>
