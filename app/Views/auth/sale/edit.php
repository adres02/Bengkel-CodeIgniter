<?= $this->extend('layout/script') ?>

<?= $this->section('judul') ?>
<div class="col-sm-6">
    <h4 class="page-title">Sale</h4>
</div>
<div class="col-sm-6">
    <ol class="breadcrumb float-right">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Sale</a></li>
        <li class="breadcrumb-item active">Edit Sale</li>
    </ol>
</div>
<?= $this->endSection('judul') ?>

<?= $this->section('isi') ?>
<div class="card card-default color-palette-box">
    <div class="card-header">
        <button type="button" class="btn btn-warning" onclick="window.location='<?= site_url('sale/index') ?>'">
            <i class="fa fa-backward"></i> Kembali
        </button>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Faktur</label>
                    <input type="text" class="form-control" name="nofaktur" id="nofaktur" value="<?= $nofaktur; ?>" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" class="form-control" name="tanggal" id="tanggal" readonly value="<?= date('Y-m-d'); ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Nama Pelanggan</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="nama_supplier" id="nama_supplier" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-sm btn-primary" type="button" id="tombolCariSupplier">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header bg-primary text-white">
                Cari Data Barang
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="kodebarcode">Barcode</label>
                            <input type="text" class="form-control" name="kodebarcode" id="kodebarcode">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nama_sparepart">Nama Sparepart</label>
                            <input type="text" style="font-weight: bold;" class="form-control" name="nama_sparepart" id="nama_sparepart" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="stok">Stok Tersedia</label>
                            <input type="text" style="font-weight: bold;" class="form-control" name="stok" id="stok" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="qty">Qty</label>
                            <input type="number" class="form-control" name="qty" id="qty" onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)" min="1" max="999" maxlength="3">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="tanggal">Aksi</label>
                            <div class="input-group">
                                <button style="display: none;" class="btn btn-sm btn-primary " type="button" id="btnEdit" title="Edit Data">
                                    <i class="fa fa-edit"></i>
                                </button>&nbsp;
                                <button class="btn btn-sm btn-secondary " type="button" id="btnReload" title="Reload Data">
                                    <i class="fa fa-sync-alt"></i>
                                </button>&nbsp;
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md">
                <div class="form-group">
                    <label for="totalharga">Total Harga</label>
                    <input type="text" class="form-control form-control-lg" name="totalharga" id="totalharga" style="text-align: right; color:blue; font-weight : bold; font-size:30pt;" value="0" readonly>
                </div>
            </div>
        </div>

        <div class="row">
            <input type="hidden" id="iddetail">
            <input type="hidden" id="hargajual">
            <div class="col-md dataDetailSale"></div>
        </div>

    </div>
</div>

<div class="viewmodal" style="display: none;"></div>

<div class="viewmodalsupplier" style="display: none;"></div>

<script>
    //Membatasi inputan angka pada qty
    function maxLengthCheck(object) {
        if (object.value.length > object.maxLength)
            object.value = object.value.slice(0, object.maxLength)
    }

    function isNumeric(evt) {
        var theEvent = evt || window.event;
        var key = theEvent.keyCode || theEvent.which;
        key = String.fromCharCode(key);
        var regex = /[0-9]|\./;
        if (!regex.test(key)) {
            theEvent.returnValue = false;
            if (theEvent.preventDefault) theEvent.preventDefault();
        }
    }

    function dataDetailSale() {
        let nofaktur = $('#nofaktur').val()

        $.ajax({
            type: "post",
            url: "<?= site_url('sale/dataDetailSale') ?>",
            dataType: "json",
            data: {
                nofaktur: nofaktur
            },
            beforeSend: function() {
                $('.dataDetailSale').attr('disable', 'disable');
                $('.dataDetailSale').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <i>Loading...</i>');
            },
            success: function(response) {
                if (response.data) {
                    $('.dataDetailSale').html(response.data);
                    $('#totalharga').val(response.totalharga);
                }
            },
            error: function(xhr, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    }
    //produk
    $(document).ready(function() {

        dataDetailSale();

        $('#btnEdit').click(function(e) {
            e.preventDefault();

            let iddetail = $('#iddetail').val();
            let qty = $('#qty').val();

            $.ajax({
                type: "post",
                url: "<?= site_url('sale/updateItem') ?>",
                dataType: "json",
                data: {
                    iddetail: iddetail,
                    qty: qty
                },
                success: function(response) {
                    if (response.sukses) {
                        Swal.fire({
                            title: "Berhasil!",
                            icon: "success",
                            text: response.sukses
                        });
                        dataDetailSale();
                        kosong();
                    }
                },
                error: function(xhr, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        });

        $('#btnReload').click(function(e) {
            e.preventDefault();
            dataDetailSale();

            $('#btnEdit').hide();
            kosong();
        });

        $('#kodebarcode').keydown(function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                cekKode();
            }
        });
    });

    function kosong() {
        $('#kodebarcode').val('')
        $('#nama_sparepart').val('')
        $('#stok').val('')
        $('#qty').val('')
        $('#kodebarcode').focus()
    }

    function cekKode() {
        let nofaktur = $('#nofaktur').val();
        let kode = $('#kodebarcode').val();
        let qty = $('#qty').val();
        let nama_sparepart = $('#nama_sparepart').val();

        if (kode.length == 0) {
            $.ajax({
                type: "post",
                url: "<?= site_url('sale/viewDataProduk') ?>",
                dataType: "json",
                success: function(response) {
                    $('.viewmodal').html(response.data).show();

                    $('#modalproduk').modal('show');
                },
                error: function(xhr, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        } else if (qty.length == 0) {
            Swal.fire({
                title: "Error",
                icon: "error",
                text: 'Maaf, Qty tidak boleh kosong'
            });
        } else {
            $.ajax({
                type: "post",
                url: "<?= site_url('sale/detailSale') ?>",
                data: {
                    kodebarcode: kode,
                    nama_sparepart: nama_sparepart,
                    qty: qty,
                    nofaktur: nofaktur
                },
                dataType: "json",
                success: function(response) {
                    if (response.totaldata == 'banyak') {
                        $.ajax({
                            url: "<?= site_url('sale/viewDataProduk') ?>",
                            dataType: "json",
                            data: {
                                keyword: kode
                            },
                            type: "post",
                            success: function(response) {
                                $('.viewmodal').html(response.data).show();

                                $('#modalproduk').modal('show');
                            },
                            error: function(xhr, thrownError) {
                                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                            }
                        });
                    }

                    if (response.sukses == 'berhasil') {
                        dataDetailSale();
                        kosong();
                    }
                },
                error: function(xhr, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
    }
</script>
<?= $this->endSection('isi') ?>