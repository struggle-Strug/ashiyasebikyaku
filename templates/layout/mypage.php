<!doctype html>
<html lang="ja">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <title><?= $this->fetch('title') ?> | 脚やせ美脚リバースエイジングプログラム</title>

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

    <!-- Bootstrap core CSS     -->
    <link href="<?= $this->Url->build('paper-dashboard/') ?>css/bootstrap.min.css" rel="stylesheet" />

    <!--  Paper Dashboard core CSS    -->
    <link href="<?= $this->Url->build('paper-dashboard/') ?>css/paper-dashboard.css" rel="stylesheet" />

    <!--  Fonts and icons     -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'>
    <link href="<?= $this->Url->build('paper-dashboard/') ?>css/themify-icons.css" rel="stylesheet">
    <link href="<?= $this->Url->build('/') ?>css/mypage.css?20230731" rel="stylesheet">
    <?= $this->Html->css('/lightbox/css/lightbox.min.css') ?>

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
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-HQJS5TYF85"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-HQJS5TYF85');
    </script>

    <!-- Swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
</head>

<body>
    <div class="wrapper">
        <div class="sidebar" data-background-color="white" data-active-color="primary">
            <div class="logo" style="padding-top:15px;padding-left:10px">
                <a class="simple-text logo-normal">
                    <img src="/img/logo2.png" style="width:94%;">
                </a>
            </div>
            <div class="sidebar-wrapper">
                <div class="user">
                    <div class="photo">
                        <img src="/img/face.png" />
                    </div>
                    <div class="info">
                        <a data-toggle="collapse" href="#collapseExample" class="collapsed">
                            <span>
                                <?= $this->Identity->get('last_name') ?> <?= $this->Identity->get('first_name') ?>さん<br/>
                                <?php if ($this->Identity->get('light_plan') == 1): ?>（ライト）<?php endif; ?>
                                <?php if ($this->Identity->get('staff_plan') == 1): ?>（講師育成）<?php endif; ?>
                                <b class="caret"></b>
                            </span>
                        </a>
                        <div class="clearfix"></div>

                        <div class="collapse" id="collapseExample">
                            <ul class="nav">
                                <li>
                                    <a
                                        href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'logout']); ?>">
                                        <span class="sidebar-mini"><i class="fa-solid fa-right-from-bracket"></i></span>
                                        <span class="sidebar-normal">ログアウト</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <ul class="nav">
                    <li class="<?php if ($this->request->getParam('controller') == 'Users')
                        echo 'active'; ?>">
                        <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'top']); ?>">
                            <i class="fa-solid fa-home"></i>
                            <p>マイページトップ</p>
                        </a>
                    </li>
                    <li class="<?php if ($this->request->getParam('controller') == 'Videos')
                        echo 'active'; ?>">
                        <a href="<?= $this->Url->build(['controller' => 'Videos', 'action' => 'index']); ?>">
                            <i class="fa-solid fa-film"></i>
                            <p>動画</p>
                        </a>
                    </li>
                    <li class="<?php if ($this->request->getParam('controller') == 'Archives')
                        echo 'active'; ?>">
                        <a href="<?= $this->Url->build(['controller' => 'Archives', 'action' => 'index']); ?>">
                            <i class="fa-solid fa-file-video"></i>
                            <p>アーカイブ</p>
                        </a>
                    </li>
                    <li class="<?php if ($this->request->getParam('controller') == 'Courses')
                        echo 'active'; ?>">
                        <a href="<?= $this->Url->build(['controller' => 'Courses', 'action' => 'index']); ?>">
                            <i class="fa-solid fa-user-lock"></i>
                            <p>初めての方は</p>
                        </a>
                    </li>
                    <?php if ($this->request->getSession()->read('Auth.User.staff_plan') == 0) : ?>
                    <li class="<?php if ($this->request->getParam('controller') == 'DailyReports')
                        echo 'active'; ?>">
                        <a href="<?= $this->Url->build(['controller' => 'DailyReports', 'action' => 'index']); ?>">
                            <i class="fa-solid fa-file-signature"></i>
                            <p>毎日の報告</p>
                        </a>
                    </li>
                    <?php endif ?>
                    <li class="<?php if ($this->request->getParam('controller') == 'WeeklyReports')
                        echo 'active'; ?>">
                        <a href="<?= $this->Url->build(['controller' => 'WeeklyReports', 'action' => 'index']); ?>">
                            <i class="fa-solid fa-photo-film"></i>
                            <p><?php echo ($this->request->getSession()->read('Auth.User.staff_plan') == 1) ? '成果履歴' : '毎週の報告'; ?></p>
                        </a>
                    </li>
                    <li class="<?php if ($this->request->getParam('controller') == 'Events')
                        echo 'active'; ?>">
                        <a href="<?= $this->Url->build(['controller' => 'Events', 'action' => 'index']); ?>">
                            <i class="fa-regular fa-calendar-days"></i>
                            <p>予約</p>
                        </a>
                    </li>
                    <li class="<?php if ($this->request->getParam('controller') == 'Messages')
                        echo 'active'; ?>">
                        <a href="<?= $this->Url->build(['controller' => 'Messages', 'action' => 'index']); ?>">
                            <i class="fa-solid fa-comment-dots"></i>
                            <p>コミュニケーションツール</p>
                        </a>
                    </li>
                    <li class="<?php if ($this->request->getParam('controller') == 'Blogs')
                        echo 'active'; ?>">
                        <a href="<?= $this->Url->build(['controller' => 'Blogs', 'action' => 'index']); ?>">
                            <i class="fa-solid fa-scroll"></i>
                            <p>美脚虎の巻ブログ</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="main-panel">
            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <div class="navbar-minimize">
                        <button id="minimizeSidebar" class="btn btn-fill btn-icon"><i class="ti-more-alt"></i></button>
                    </div>
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar bar1"></span>
                            <span class="icon-bar bar2"></span>
                            <span class="icon-bar bar3"></span>
                        </button>
                        <a class="navbar-brand" href="#"><?= $this->fetch('title') ?></a>
                    </div>
                </div>
            </nav>

            <div class="content">
                <div class="container-fluid">
                    <div class="row">

                        <div class="col-md-12">
                            <?= $this->Flash->render() ?>
                        </div>

                        <?= $this->fetch('content') ?>

                    </div>
                </div>
            </div>

            <footer class="footer">
                <div class="container-fluid">
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
    <?= $this->Html->script('/lightbox/js/lightbox.min.js') ?>
</body>

</html>