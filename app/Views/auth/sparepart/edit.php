<!-- Modal -->
<div class="modal fade" id="modaledit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?= $title ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('sparepart/update', ['class' => 'formsparepart']) ?>
            <?= csrf_field(); ?>
            <div class="modal-body">

                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label">Barcode</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="kodebarcode" value="<?= $kodebarcode ?>" name="kodebarcode" readonly>
                        <div class="invalid-feedback errorKodebarcode">

                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label">Nama</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="nama_sparepart" value="<?= $nama_sparepart ?>" name="nama_sparepart">
                        <div class="invalid-feedback errorNama_sparepart">

                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label">Harga Beli</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="harga_beli" value="<?= $harga_beli ?>" name="harga_beli">
                        <div class="invalid-feedback errorHargabeli">

                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label">Harga Jual</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="harga_jual" value="<?= $harga_jual ?>" name="harga_jual">
                        <div class="invalid-feedback errorHargajual">

                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btnsimpan"><i class="fa fa-share-square"></i> Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>

            <?= form_close() ?>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        $('#harga_beli').autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '0'
        });

        $('#harga_jual').autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '0'
        });

        $('.formsparepart').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "post",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: "json",
                beforeSend: function() {
                    $('.btnsimpan').attr('disable', 'disable');
                    $('.btnsimpan').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <i>Loading...</i>');
                },
                complete: function() {
                    $('.btnsimpan').removeAttr('disable', 'disable');
                    $('.btnsimpan').html('<i class="fa fa-share-square"></i>  Simpan');
                },
                success: function(response) {
                    if (response.error) {
                        if (response.error.kodebarcode) {
                            $('#kodebarcode').addClass('is-invalid');
                            $('.errorKodebarcode').html(response.error.kodebarcode);
                        } else {
                            $('#kodebarcode').removeClass('is-invalid');
                            $('.errorKodebarcode').html('');
                        }

                        if (response.error.nama_sparepart) {
                            $('#nama_sparepart').addClass('is-invalid');
                            $('.errorNama_sparepart').html(response.error.nama_sparepart);
                        } else {
                            $('#nama_sparepart').removeClass('is-invalid');
                            $('.errorNama_sparepart').html('');
                        }

                        if (response.error.harga_beli) {
                            $('#harga_beli').addClass('is-invalid');
                            $('.errorHargabeli').html(response.error.harga_beli);
                        } else {
                            $('#harga_beli').removeClass('is-invalid');
                            $('.errorHargabeli').html('');
                        }

                        if (response.error.harga_jual) {
                            $('#harga_jual').addClass('is-invalid');
                            $('.errorHargajual').html(response.error.harga_jual);
                        } else {
                            $('#harga').removeClass('is-invalid');
                            $('.errorHargajual').html('');
                        }

                    } else {
                        Swal.fire({
                            title: "Berhasil!",
                            text: response.sukses,
                            icon: "success",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#modaledit').modal('hide');
                        listsparepart();
                    }
                }
            });
        })
    });
</script>