<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->fetch('title') ?> | 脚やせ美脚リバースエイジングプログラム インストラクターサイト</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex" />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&amp;display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="<?= $this->Url->build('AdminLTE/') ?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="<?= $this->Url->build('AdminLTE/') ?>plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="<?= $this->Url->build('AdminLTE/') ?>css/adminlte.min.css?v=3.2.0">
    <link href="<?= $this->Url->build('/') ?>lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="<?= $this->Url->build('/') ?>css/admin.css?20230731" rel="stylesheet">

    <script src="<?= $this->Url->build('AdminLTE/') ?>plugins/jquery/jquery.min.js"></script>
    <script src="<?= $this->Url->build('AdminLTE/') ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= $this->Url->build('AdminLTE/') ?>plugins/select2/js/select2.full.min.js"></script>
    <script src="<?= $this->Url->build('AdminLTE/') ?>js/adminlte.min.js?v=3.2.0"></script>
    <script src="<?= $this->Url->build('/') ?>lightbox/js/lightbox.min.js"></script>

    <!-- 郵便番号 -->
    <script src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>

    <!-- 横スクロールナビ -->
    <link rel="stylesheet" href="https://unpkg.com/scroll-hint@latest/css/scroll-hint.css">
    <script src="https://unpkg.com/scroll-hint@latest/js/scroll-hint.min.js"></script>

    <!-- 並び替え -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
    <style>
        #sortable td span {
            cursor: move;
        }
    </style>

    <!-- Swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
</head>

<body class="hold-transition text-sm">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark navbar-gray">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <span class="nav-link">
                        <i class="fas fa-user"></i> <?= $this->Identity->get('name') ?>
                    </span>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                        href="<?= $this->Url->build(['controller' => 'Staffs', 'action' => 'logout']); ?>"><i class="fas fa-sign-out-alt"></i> ログアウト</a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-info elevation-4">
            <!-- Brand Logo -->
            <div>
                <a class="brand-link">
                    <img src="/img/logo.png" class="brand-image img-circle elevation-3" style="opacity: .8">
                    <span class="brand-text font-weight-light">インストラクターサイト</span>
                </a>
            </div>

            <!-- Sidebar -->
            <div class="sidebar">

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">

                        <li class="nav-item">
                            <a href="<?= $this->Url->build(['controller' => 'Staffs', 'action' => 'top']); ?>"
                                class="nav-link <?php if ($this->request->getParam('controller') == 'Staffs') echo 'active'; ?>">
                                <i class="nav-icon fa-solid fa-house"></i>
                                <p>
                                    事務局からのお知らせ
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'index']); ?>"
                                class="nav-link <?php if ($this->request->getParam('controller') == 'Users') echo 'active'; ?>">
                                <i class="nav-icon fa-regular fa-address-card"></i>
                                <p>
                                    会員管理
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $this->Url->build(['controller' => 'Events', 'action' => 'index']); ?>"
                                class="nav-link <?php if ($this->request->getParam('controller') == 'Events') echo 'active'; ?>">
                                <i class="nav-icon fa-regular fa-calendar-days"></i>
                                <p>
                                    個別コンサルティング
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $this->Url->build(['controller' => 'DailyReports', 'action' => 'index']); ?>"
                                class="nav-link <?php if ($this->request->getParam('controller') == 'DailyReports') echo 'active'; ?>">
                                <i class="nav-icon fa-solid fa-file-signature"></i>
                                <p>
                                    日次報告
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $this->Url->build(['controller' => 'WeeklyReports', 'action' => 'index']); ?>"
                                class="nav-link <?php if ($this->request->getParam('controller') == 'WeeklyReports') echo 'active'; ?>">
                                <i class="nav-icon fa-solid fa-photo-film"></i>
                                <p>
                                    週次報告
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $this->Url->build(['controller' => 'ComparedImages', 'action' => 'index']); ?>"
                                class="nav-link <?php if ($this->request->getParam('controller') == 'ComparedImages') echo 'active'; ?>">
                                <i class="nav-icon fa-solid fa-people-arrows"></i>
                                <p>
                                    画像比較
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $this->Url->build(['controller' => 'Lessons', 'action' => 'index']); ?>"
                                class="nav-link <?php if ($this->request->getParam('controller') == 'Lessons') echo 'active'; ?>">
                                <i class="nav-icon fa-solid fa-book"></i>
                                <p>
                                    レッスン管理
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $this->Url->build(['controller' => 'CustomerCsvs', 'action' => 'index']); ?>"
                                class="nav-link <?php if ($this->request->getParam('controller') == 'CustomerCsvs') echo 'active'; ?>">
                                <i class="nav-icon fa-solid fa-file-excel"></i>
                                <p>
                                    顧客カルテCSV
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1><?= $this->fetch('title') ?></h1>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <section class="content">
                <!-- Message -->
                <?= $this->Flash->render() ?>
            </section>

            <!-- Main content -->
            <section class="content">
                <?= $this->fetch('content') ?>
            </section>
            <!-- /.content -->

        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <script>
        $(function() {
            // Select2
            $('.select2').select2();

            // 横スクロールナビ
            new ScrollHint('.table-responsive-md', {
                suggestiveShadow: true,
                remainingTime: 5000,
                i18n: {
                    scrollable: 'スクロールできます'
                }
            });
        });
    </script>
</body>

</html>
