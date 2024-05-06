<?=$this->extend('/template/login/tema')?>

<?=$this->section('content');?>

<div class="container-fluid">
    <div class="row login-container">
        <!-- Kolom Kiri (Gambar) -->

        <!-- Kolom Kanan (Form Login) -->
        <div class="col-md-6 d-flex align-items-center justify-content-center">

            <?php
              $attributes = ['class' => 'w-75 login-form', 'id' => 'FormLogin'];
              //membuka tag <Form id="" action="" id="" >
              echo form_open('', $attributes);
            ?>
            <div class="text-center">
                <h2 class="mb-4">SIM web APP</h2>
                <h4 class="mb-4">Login</h4>
            </div>




            <div class="form-group">
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-at"></i></span>
                        <input type="email" name="email" placeholder="Masukan email" class="form-control" value="">

                    </div>
                </div>
            </div>
            <div class="form-group mb-2">
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" name="password" placeholder="Masukan password" class="form-control "
                            value="">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn BtnLogin btn-danger w-100">Login</button>
            <?=form_close();?>
        </div>

        <div class="col-md-6 login-image">
            <img src="<?=site_url('public/assets/image/bg-login.png')?>" class="img-fluid" alt="Gambar Latar Belakang">
        </div>
    </div>
</div>





<script style="javascript">
$(document).on('submit', "#FormLogin", function(e) {
    e.preventDefault();

    $('.BtnLogin').html('....');

    $.ajax({
        url: "<?= site_url('/auth/proses-login') ?>",
        type: "post",
        data: new FormData(this),
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        success: function(json) {
            $('.BtnLogin').html('Login');
            if (json.status) {

                iziToast.success({
                    title: 'Yeaay',
                    message: json.message, //menampilkan pesan berhasil
                    position: 'topRight'
                });
                //jika berhasil akan reaload halaman
                //sistem akan membaca session, jika session ada akan di arahkan ke halaman home/ dashboard
                window.location.reload();
            } else {
                iziToast.error({
                    title: 'Ops',
                    message: json.message, //Menampilkan pesan kesalahan
                    position: 'topRight'
                });
                // fungsi CSRF untuk mencegah scraping;
                //jika gagal akan mengupdate token csrf
                $("[name='csrf_test_name']").val(json.new_token);
            }
        },
        error: function(jqXHR) {
            $('.BtnLogin').html('Login');
            if (jqXHR.status == 0) {
                iziToast.error({
                    title: 'Opps',
                    message: 'Gagal Terhubung keserver',
                    position: 'topRight'
                });
            }
        }
    });
});
</script>

<?=$this->endSection('');?>