<button type="button" class="btn btn-success has-icon" onclick="showModal('')"><i class="material-icons mr-1">add</i>
    Tambah Supplier
</button>
<br>
<br>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nama</th>
            <th scope="col">No. Telp</th>
            <th scope="col">Alamat</th>
            <th scope="col">Nama Bahan</th>
            <th scope="col">Harga</th>
            <th scope="col">Aksi</th>
        </tr>
        </thead>
        <tbody id="table-main"></tbody>
    </table>
</div>


<!--Modal-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="basicModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="basicModalLabel">Modal title</h5>
                <button class="btn btn-icon btn-sm btn-text-secondary rounded-circle" type="button"
                        data-dismiss="modal">
                    <i class="material-icons">close</i>
                </button>
            </div>
            <form id="modal-form">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="s_nama">Nama Supplier</label>
                        <input type="text" class="form-control" id="s_nama" name="s_nama"
                               placeholder="Masukkan nama">
                        <input type="hidden" class="form-control" id="s_id" name="s_id">
                    </div>
                    <div class="form-group">
                        <label for="s_telp">No Telepon</label>
                        <input type="text" class="form-control" id="s_telp" name="s_telp"
                               placeholder="Masukkan nomor telepon">
                    </div>
                    <div class="form-group">
                        <label for="s_telp">Alamat</label>
                        <textarea class="form-control" id="s_alamat" name="s_alamat"
                                  placeholder="Masukkan alamat"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="s_telp">Bahan</label>
                        <select class="form-control" id="s_bahan" name="s_bahan"></select>
                    </div>
                    <div class="form-group">
                        <label for="s_harga">Harga</label>
                        <input type="text" class="form-control" id="s_harga" name="s_harga"
                               placeholder="Masukkan harga bahan">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="modal-btn">Tambah Bahan</button>
                </div>
            </form>


        </div>
    </div>
</div>

<script>

    var jsonData = "";
    var jsonBahan = "";

    function loadData() {
        $.ajax({
            url: "model/supplier.php?load",
            data: $(this).serialize(),
            dataType: "JSON",
            method: "POST",
            success: function (data) {
                console.log(data);
                if (data["error"] === 0) {
                    jsonData = data["data"];
                    let a = "";
                    for (let i = 0; i <jsonData.length; i++) {
                        a += '<tr>\n' +
                            '            <td scope="col">' + (i+1) + '</td>\n' +
                            '            <td scope="col">' + jsonData[i].nama + '</td>\n' +
                            '            <td scope="col">' + jsonData[i].telp + '</td>\n' +
                            '            <td scope="col">' + jsonData[i].alamat + '</td>\n' +
                            '            <td scope="col">' + jsonData[i].bahan + '</td>\n' +
                            '            <td scope="col">' + jsonData[i].harga + '</td>\n' +
                            '            <td scope="col">' +
                            '<div class="btn-group" role="group">\n' +
                            '                <button type="button" class="btn btn-text-primary btn-icon rounded-circle" onclick="showModal(' + i + ')"><i class="material-icons">edit</i></button>\n' +
                            '                <button type="button" class="btn btn-text-danger btn-icon rounded-circle" onclick="hapus(' + i + ')"><i class="material-icons">delete</i></button>\n' +
                            '              </div></td>\n' +
                            '        </tr>'
                    }
                    $("#table-main").html(a);
                } else if (data["error"] === 2) {
                    $('#table-main').html('' +
                        '<tr>\n' +
                        '   <td scope="col" colspan="7"><center>' + data["msg"] + '</center></td>' +
                        '</tr>')
                }
            }
        });
    }

    loadData();

    function loadBahanBaku() {
        $.ajax({
            url: "model/bahan-baku.php?load",
            data: $(this).serialize(),
            dataType: "JSON",
            method: "POST",
            success: function (data) {
                if (data["error"] === 0) {
                    jsonBahan = data["data"];
                    let a = "";
                    for (let i = 0; i <jsonBahan.length; i++) {
                        a += '<option value="' + jsonBahan[i].id + '">' + jsonBahan[i].nama + '</option>';
                    }
                    $("#s_bahan").html(a);
                } else if (data["error"] === 2) {
                    $('#s_bahan').html('<option value="">Gagal meload bahan</option>');
                }
            }
        });
    }

    loadBahanBaku();

    function showModal(index) {
        $("#modal-btn").prop("disabled", false);

        if (index === "") {
            $(".modal-title").html("Tambah Supplier");
            $("#modal-btn").html("Tambah");
            $("#modal-form").trigger("reset");
        } else {
            $(".modal-title").html("Ubah Supplier");
            $("#modal-btn").html("Ubah");
            $("#s_id").val(jsonData[index].id);
            $("#s_nama").val(jsonData[index].nama);
            $("#s_telp").val(jsonData[index].telp);
            $("#s_alamat").val(jsonData[index].alamat);
            $("#s_harga").val(jsonData[index].harga);
            $("#s_bahan select").val(jsonBahan[index].bahan);
        }
        $("#myModal").modal("show");
    }

    $("#modal-form").on("submit", function (e) {
        e.preventDefault();
        $.ajax({
            url: "model/supplier.php?add-update",
            data: $(this).serialize(),
            dataType: "JSON",
            method: "POST",
            beforeSend: function () {
                $("#modal-btn").prop("disabled", true);
            },
            success: function (data) {
                showNotif(data);
                if (data["error"] === 0) {
                    $("#myModal").modal("hide");
                    loadData()
                }
            }
        });
    });

    function showNotif(d) {
        if (d["error"] === 0) {
            new Noty({
                type: 'success',
                text: '<h5>Berhasil!!</h5>' + d["msg"] + '',
                timeout: 2000
            }).show()
        } else {
            new Noty({
                type: 'error',
                text: '<h5>Gagal</h5>' + d["msg"] + '',
                timeout: 2000
            }).show();
        }
    }

    function hapus(index) {
        $.ajax({
            url: "model/supplier.php?hapus",
            data: {"id" : jsonData[index].id},
            dataType: "JSON",
            method: "POST",
            success: function (data) {
                showNotif(data);
                if (data["error"] === 0) {
                    loadData()
                }
            }
        });
    }

</script>