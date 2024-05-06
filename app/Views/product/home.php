<?=$this->extend('/template/admin/tema')?>

<?=$this->section('content');?>

<div class="">
    <div class="row">
        <div class="col-md-6">
        <div class="d-flex">
            <input class="form-control me-2" id="search" type="text" placeholder="Cari min 3 kata" aria-label="Search">

            <select class="bg-white rounded-2 pe-5 me-4 kategori" id="kategori" aria-label="Default select example">
            <option value="" selected>Semua</option>

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
        <div class="col-md-6 mt-3 mt-md-0 text-md-end">
            <button  class="btn btn-success BtnExport px-4 mx-4"><i class="fas fa-file-export me-2"></i>Export Excel</button>
            <a href="<?=site_url('/product/add')?>" class="btn btn-danger px-4 me-2"><i class="fas fa-plus-circle me-2"></i>Tambah Produk</a>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col">
            <table class="table  table-hover">
                <thead>
                    <tr>
                        <td>No</td>
                        <td>Image</td>
                        <td>Nama Produk</td>
                        <td>Kategori Produk</td>
                        <td>Harga Beli (Rp)</td>
                        <td>Harga Jual (Rp)</td>
                        <td>Stok produk</td>
                        <td>Aksi</td>
                    </tr>
                </thead>
                <tbody id="table_konten" >
                <tbody>
            </table>

            <div class="row">
                <div class="col-md-6">
                        <span id="total_data" >Total : 10 of 20</span>
                        <span id="page_active" >Page 1</span>
                </div>
                <div class="col-md-6 mt-3 mt-md-0 text-md-end">
                    <span class="pagging" >

                    </span>
                </div>
            </div>
        </div>
    </div>

    
</div>

<script>

    $('#search').on('input',function(e){
        var value = e.target.value;
        if(value.length === 0 || value.length > 2) {
            load_data();
        }
        console.log()
        
    })

    var current_page = 1;
    var token = '<?=csrf_hash()?>';


    load_data();


    $('#kategori').on('change',function(){
        load_data();
    })


    $('.BtnExport').on('click',function(event){
        event.preventDefault();
        var kategori = $('#kategori').val();

        window.location.href="<?=site_url('/download/excel/product?kategori=')?>"+kategori;

    })

    function load_data(){
        var key = $('#search').val();
        var kategori = $('#kategori').val();

        var data = {
            key : key,
            kategori : kategori,
            page : current_page
        }

        $.ajax({
            url: "<?= site_url('/product/list') ?>",
            type: "get",
            data: data,
            success: function(json) {
                /* 
                CARA LOAD DATA INI BISA JUGA DILAKUKAN MENGGUNAKAN VIEW (FOKUS KE PHP TIDAK FORMAT JS) 
                DAN LEBIH ENAK LAGI PAKE DATA TABLE SERVERSIDE 

                */
               var html = "";
               var no=0;
                if(json.data.length > 0){
                    
                json.data.map((row,index)=>{
                    html += "<tr>";
                    html += `<td>${index+1}</td>`;
                    html += `<td><img height='100px' src='${row.image}'/></td>`;
                    html += `<td>${row.nama_barang} </td>`;
                    html += `<td>${row.nama_kategori}</td>`;
                    html += `<td>${row.harga_beli}</td>`;
                    html += `<td>${row.harga_jual}</td>`;
                    html += `<td>${row.stok}</td>`;
                    html += `<td>
                                <a class="btn-aksi" onClick='hapus_data("${row.id}","${row.nama_barang}")' ><img src='<?=site_url('public/assets/image/delete.png')?>'></a>
                                <a class="btn-aksi" href="<?=site_url('product/edit/')?>${row.id}" ><img src='<?=site_url('public/assets/image/edit.png')?>'></a>
                            </td>`;
                    html += "<tr>";
               })

               
                }else{
                    if(current_page !==1){
                        current_page = current_page - 1;
                        load_data();
                    }
                    html +="<tr><td colspan='8' >Data tidak tersedia</td></tr>"
                }

               //pagination
               pagination(json.pagging.total_page);

               $("#table_konten").html(html);

               $("#total_data").html(json.pagging.data_info)
               $("#page_active").html(`of Page ${current_page}`)

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
    }

    const pagination = (total_halaman)=>{
        var paging = `
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-end">`;

            //jika halaman aktiv di halaman 2,3,4 dst akan muncul tombol prev
            if(current_page !== 1){
            paging +=  `<li class="page-item">
                            <button class="page-link"  onClick="prev_page()"aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </button>
                        </li>`;

            }


        for(var i=1;i<=total_halaman;i++){
            if(i < 3){
                paging += `<li class="page-item"><button class="page-link"  onClick="change_page(${i})"  >${i}</button></li>`
            }

            if(total_halaman >= 3){

                if(i === total_halaman){
                    paging +=`<li class="page-item"><button class="page-link"   >....</button></li>`
                    paging += `<li class="page-item "><button class="page-link"  onClick="change_page(${i})"  >${i}</button></li>`
                }
            }            

        }

        //jika halaman terakhir sudah di buka, next page di matikan
        if(total_halaman !== current_page){

            paging +=`<li class="page-item">
                    <button class="page-link" onClick="next_page()" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </button>
                    </li>
                    `
                  
        }

        pagging =`
                    
                </ul>
            </nav>`;
     

        $('.pagging').html(paging);
    }

    const next_page = ()=>{
       
        current_page = current_page + 1;
        load_data();
    }

    const prev_page = ()=>{
        current_page = current_page - 1;
        load_data()
    }


    const change_page = (page)=>{
       
        current_page = page
        
        load_data();

        console.log(current_page);
    }

    const hapus_data = (id,nama_barang)=>{

        console.log(id);
        Swal.fire({
            title: "Yakin akan dihapus ?",
            text: nama_barang+" akan dihapus permanent!", //menampilkan pesan berhasil
            icon: "info",
            confirmButtonText: "Ya Yakin",
            confirmButtonColor: '#cb190d',
            showCancelButton: true,
        }).then((oke)=>{
            if (oke.isConfirmed) {
               proses_hapus(id)
            }
        });
    }

    const proses_hapus = (id)=>{
        $.ajax({
            url: "<?= site_url('/product/delete/') ?>"+id,
            type: "DELETE",
            data: {csrf_test_name : token},
            success: function(json) {

                if(json.status){
                    iziToast.success({
                        title: 'Berhasil',
                        message: json.message,
                        position: 'topRight'
                    });
                    load_data();
                }else{
                    iziToast.error({
                        title: 'Opps',
                        message: json.message,
                        position: 'topRight'
                    });
                }

                token = json.new_token
              

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
    }

</script>


<?=$this->endSection();?>