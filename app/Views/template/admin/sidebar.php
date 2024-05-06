<div class="sidebar">

    <div class="p-1 d-flex ">
        <div class="p-2 bd-highlight"> <img src="<?=site_url('public/assets/image/Handbag.png')?>"></div>
        <div class="p-2 flex-grow-1"> <span class="text-white">SIMS Web app </span></div>
        <div class="p-2 text-white bd-highlight">
            <a href="#" class="btn-aksi">
                <i class="fa-solid fa-bars"></i>
            </a>
        </div>
    </div>




    <ul class="navb">
        <li class="navb-item <?=$sidebar_active == "products" ? "active" : ""?>">
            <img src="<?=site_url('public/assets/image/Package.png')?>" />
            <a class="link-nav" href="<?=site_url('/product')?>">Product</a>
        </li>
        <li class="navb-item <?=$sidebar_active == "profile" ? "active" : ""?> ">
            <img src="<?=site_url('public/assets/image/User.png')?>" />
            <a class="link-nav" href="<?=site_url('/profile')?>">Profile</a>
        </li>
        <li class="navb-item">
            <img src="<?=site_url('public/assets/image/SignOut.png')?>" />
            <a class="link-nav" href="<?=site_url('/logout')?>">Logout</a>
        </li>
        <!-- Tambahkan link menu lain sesuai kebutuhan -->
    </ul>
</div>