<?=$this->extend('/template/admin/tema')?>

<?=$this->section('content');?>

<div class="">
    <img src="<?=site_url('public/assets/image/profile.png')?>" class="rounded-circle"
        alt="Gambar dengan sudut yang bulat">
    <div class='text-username'><?=$session['nama']?></div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="mb-3">
            <label for="nama kandidat" class="form-label">Nama Kandidat</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-at"></i></span>
                <input type="text" disabled class="form-control" value="<?=$session['nama']?>">

            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="nama_barang" class="form-label">Posisi kandidat</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-briefcase"></i></span>
                <input type="text" disabled class="form-control" value="<?=$session['level']?>">

            </div>
        </div>
    </div>
</div>

<?=$this->endSection('');?>