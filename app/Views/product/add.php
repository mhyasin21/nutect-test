<?=$this->extend('/template/admin/tema')?>

<?=$this->section('content');?>
<?=form_open('',[
    'class'=>'FormInput',
    'id'=>'FormInput'
])?>
<div class="">
    <div class="row">
        <div class="col-md-4">
            <div class="mb-3">
                <label for="kategori" class="form-label">Kategori</label>
                <select class="form-select" id="kategori" name="kategori">
                    <option value="" selected>Pilih kategori</option>
                    <?php
                foreach($option_kategori as $option) : 
                ?>
                    <option value="<?=$option['id']?>"><?=$option['nama_category']?></option>
                    <?php
                endforeach;
            ?>
                </select>
            </div>
        </div>
        <div class="col-md-8">
            <div class="mb-3">
                <label for="nama_barang" class="form-label">Nama Barang</label>
                <input type="text" class="form-control" id="nama_barang" name="nama_barang">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="mb-3">
                <label for="harga_beli" class="form-label">Harga Beli</label>
                <input type="text" class="form-control" id="harga_beli" name="harga_beli">
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3">
                <label for="harga_jual" class="form-label">Harga Jual</label>
                <input type="text" class="form-control" id="harga_jual" name="harga_jual">
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3">
                <label for="stok_barang" class="form-label">Stok Barang</label>
                <input type="text" class="form-control" id="stok_barang" name="stok_barang">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <label for="gambar" class="form-label">Upload Gambar</label>
                <div class="custom-file-container" onclick="document.getElementById('gambar').click()">
                    <input type="file" class="form-control custom-file-input" id="gambar" name="gambar" accept="image/*"
                        onchange="previewImage(event)">
                    <label class="custom-file-label" id="custom-file-label" for="gambar">Silahkan upload gambar</label>
                    <div class="image-preview mt-2" id="imagePreview">
                        <span id="imageName" class="file-name"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12  d-flex justify-content-end">
            <a href="javascript:history.back()" class="btn px-5 btn-outline-primary me-2">Batalkan</a>
            <button type="submit" class="btn BtnSimpan btn-primary px-5">Simpan</button>
        </div>
    </div>
</div>
<?=form_close()?>

<script>
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function() {
        var imagePreview = document.getElementById('imagePreview');
        var imageName = document.getElementById('imageName');
        var label = document.getElementById('custom-file-label');

        label.hidden = true;

        imagePreview.innerHTML = '<img src="' + reader.result + '" alt="Preview">';
        imageName.textContent = event.target.files[0].name;
        var customFileContainer = document.querySelector('.custom-file-container');
        customFileContainer.style.height = '200px';

    }
    reader.readAsDataURL(event.target.files[0]);
}


$("#harga_beli").on('input', function() {
    // Akan update harga jual.
    var harga_beli = $('#harga_beli').val();

    // Mengonversi nilai input menjadi angka desimal
    var harga_beli_numeric = parseFloat(harga_beli.replace(/[^\d]/g, ''));

    // Menghitung harga jual
    var up_harga_percent = 0.30; // 30%
    var total_up_harga = harga_beli_numeric * up_harga_percent;
    var harga_jual_numeric = harga_beli_numeric + total_up_harga;

    // Mengonversi harga jual dan beli menjadi format Rupiah
    var harga_beli_formatted = formatRupiah(harga_beli_numeric, 'Rp.');
    var harga_jual_formatted = formatRupiah(harga_jual_numeric, 'Rp.');

    // Mengatur nilai input harga_beli dan jual dengan format Rupiah
    $("#harga_beli").val(harga_beli_formatted);
    $("#harga_jual").val(harga_jual_formatted);
});

$("#harga_jual").on('input', function() {

    var harga_jual = $('#harga_jual').val();
    var harga_jual_formatted = formatRupiah(harga_jual, 'Rp.');

    $("#harga_jual").val(harga_jual_formatted);
});

$("#stok_barang").on('input', function() {

    var a = $('#stok_barang').val();
    var aa = formatRupiah(a);

    $("#stok_barang").val(aa);
});

function formatRupiah(angka, format = '') {
    // Memastikan angka berupa string
    angka = angka.toString();
    // Menghilangkan karakter non-digit, kecuali tanda koma (,)
    angka = angka.replace(/[^\d,]/g, '');

    // Menghilangkan semua koma (,) selain yang pertama
    angka = angka.replace(/,/g, match => match === ',' ? match : '');

    // Memisahkan angka menjadi bagian pecahan dan ribuan
    var split = angka.split(',');
    var ribuan = split[0];
    var pecahan = split[1] || '';
    // Mengatur format ribuan dengan titik (.) setiap 3 digit
    ribuan = ribuan.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    // Mengembalikan nilai dalam format Rupiah dengan 2 angka desimal
    return format + ribuan + (pecahan ? ',' + pecahan.slice(0, 2) : '');
}


$(document).on('submit', "#FormInput", function(e) {
    e.preventDefault();

    $('.BtnSimpan').html('Loding...');

    $.ajax({
        url: "<?= site_url('/product/add') ?>",
        type: "post",
        data: new FormData(this),
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        success: function(json) {
            $('.BtnSimpan').html('Simpan');
            if (json.status) {
                Swal.fire({
                    title: "Yeay!",
                    text: json.message, //menampilkan pesan berhasil
                    icon: "success"
                }).then((oke) => {
                    if (oke.isConfirmed) {
                        window.location.href = "<?=site_url('/product')?>"
                    }
                });
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
            $('.BtnSimpan').html('Simpan');
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




<?=$this->endSection();?>