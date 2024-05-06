<!DOCTYPE html>

<head>
    <?= view('template/head') ?>
</head>

<body>
    <div class="container-content">
        <?= view('template/admin/sidebar') ?>

        <div class="main-content">
            <div class="px-4 mt-4">
                <?=view('template/breadcrumb')?>

                <?= $this->renderSection('content') ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>