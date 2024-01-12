<!doctype html>
<html lang="ja">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <title>ログイン | 脚やせ美脚リバースエイジングプログラム</title>

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

    <!-- Bootstrap core CSS     -->
    <link href="<?= $this->Url->build('paper-dashboard/') ?>css/bootstrap.min.css" rel="stylesheet" />

    <!--  Paper Dashboard core CSS    -->
    <link href="<?= $this->Url->build('paper-dashboard/') ?>css/paper-dashboard.css" rel="stylesheet" />

    <!--  Fonts and icons     -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'>
    <link href="<?= $this->Url->build('paper-dashboard/') ?>css/themify-icons.css" rel="stylesheet">
    <link href="<?= $this->Url->build('/') ?>css/mypage.css" rel="stylesheet">

    <!--   Core JS Files. Extra: TouchPunch for touch library inside jquery-ui.min.js   -->
    <script src="<?= $this->Url->build('paper-dashboard/') ?>js/jquery.min.js" type="text/javascript"></script>
    <script src="<?= $this->Url->build('paper-dashboard/') ?>js/jquery-ui.min.js" type="text/javascript"></script>
    <script src="<?= $this->Url->build('paper-dashboard/') ?>js/perfect-scrollbar.min.js" type="text/javascript">
    </script>
    <script src="<?= $this->Url->build('paper-dashboard/') ?>js/bootstrap.min.js" type="text/javascript"></script>

    <!--  Forms Validations Plugin -->
    <script src="<?= $this->Url->build('paper-dashboard/') ?>js/jquery.validate.min.js"></script>
    <!-- Promise Library for SweetAlert2 working on IE -->
    <script src="<?= $this->Url->build('paper-dashboard/') ?>js/es6-promise-auto.min.js"></script>
    <!--  Plugin for Date Time Picker and Full Calendar Plugin-->
    <script src="<?= $this->Url->build('paper-dashboard/') ?>js/moment.min.js"></script>
    <!--  Date Time Picker Plugin is included in this js file -->
    <script src="<?= $this->Url->build('paper-dashboard/') ?>js/bootstrap-datetimepicker.js"></script>
    <!--  Select Picker Plugin -->
    <script src="<?= $this->Url->build('paper-dashboard/') ?>js/bootstrap-selectpicker.js"></script>
    <!--  Switch and Tags Input Plugins -->
    <script src="<?= $this->Url->build('paper-dashboard/') ?>js/bootstrap-switch-tags.js"></script>
    <!-- Circle Percentage-chart -->
    <script src="<?= $this->Url->build('paper-dashboard/') ?>js/jquery.easypiechart.min.js"></script>
    <!--  Charts Plugin -->
    <script src="<?= $this->Url->build('paper-dashboard/') ?>js/chartist.min.js"></script>
    <!--  Notifications Plugin    -->
    <script src="<?= $this->Url->build('paper-dashboard/') ?>js/bootstrap-notify.js"></script>
    <!-- Sweet Alert 2 plugin -->
    <script src="<?= $this->Url->build('paper-dashboard/') ?>js/sweetalert2.js"></script>
    <!-- Wizard Plugin    -->
    <script src="<?= $this->Url->build('paper-dashboard/') ?>js/jquery.bootstrap.wizard.min.js"></script>
    <!--  Bootstrap Table Plugin    -->
    <script src="<?= $this->Url->build('paper-dashboard/') ?>js/bootstrap-table.js"></script>
    <!--  Plugin for DataTables.net  -->
    <script src="<?= $this->Url->build('paper-dashboard/') ?>js/jquery.datatables.js"></script>
    <!--  Full Calendar Plugin    -->
    <script src="<?= $this->Url->build('paper-dashboard/') ?>js/fullcalendar.min.js"></script>
    <!-- Paper Dashboard PRO Core javascript and methods for Demo purpose -->
    <script src="<?= $this->Url->build('paper-dashboard/') ?>js/paper-dashboard.js"></script>
</head>

<body>
    <div class="wrapper wrapper-full-page">
        <div class="register-page">
            <!--   you can change the color of the filter page using: data-color="blue | azure | green | orange | red | purple" -->
            <div class="content">
                <div class="container">

                    <?= $this->fetch('content') ?>

                </div>

                <footer class="footer footer-transparent">
                    <div class="container">
                        <div class="copyright text-center">
                            &copy; someone like you, LLC.
                            <script>
                            document.write(new Date().getFullYear() + '.')
                            </script>
                            All rights reserved.
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>
</body>

</html>
