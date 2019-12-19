<button type="button" class="btn btn-success has-icon" onclick="showModal('')"><i class="material-icons mr-1">add</i>
    Tambah Supplier
</button>
<br>
<br>

<div class="table-responsive">
    <table id="myTable" class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nama</th>
            <th scope="col">No. Telp</th>
            <th scope="col">Alamat</th>
            <th scope="col">Aksi</th>
        </tr>
        </thead>
        <tbody id="table-body"></tbody>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="modal-btn">Tambah Bahan</button>
                </div>
            </form>


        </div>
    </div>
</div>

<!--Modal Supplier Bahan-->
<div class="modal fade" id="modalSupplierBahan" tabindex="-1" role="dialog" aria-labelledby="basicModalLabel"
     aria-hidden="true">
    <div class="modal-dialog   modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title-bahan">Supplier Bahan</h5>
                <button class="btn btn-icon btn-sm btn-text-secondary rounded-circle" type="button"
                        data-dismiss="modal">
                    <i class="material-icons">close</i>
                </button>
            </div>
            <div class="modal-body">
                <table id="tableBahan" class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nama Bahan</th>
                        <th scope="col">Harga</th>
                        <th scope="col">BHarga</th>
                        <th scope="col">BWaktu</th>
                        <th scope="col">BRetur</th>
                        <th scope="col">Aksi</th>
                    </tr>
                    </thead>
                    <tbody id="table-body"></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="modalBahan('')">Tambah Bahan</button>
                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!--Modal Supplier Bahan Update Add-->
<div class="modal fade" id="modalBahan" tabindex="-1" role="dialog" aria-labelledby="basicModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-bahan-title">Supplier Bahan</h5>
                <button class="btn btn-icon btn-sm btn-text-secondary rounded-circle" type="button"
                        data-dismiss="modal">
                    <i class="material-icons">close</i>
                </button>
            </div>
            <form id="bahan-form">

                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" id="modalSupplierId" name="modalSupplierId">
                        <input type="hidden" id="id-supplier-bahan" name="id-supplier-bahan">
                        <label for="b_harga">Pilih Bahan</label>
                        <select id="bahan-select" class="form-control" id="bahan-id" name="bahan-id"></select>
                    </div>

                    <div class="form-group">
                        <label for="b_harga">Harga</label>
                        <input type="text" class="form-control" placeholder="Harga" id="bahan-harga" name="bahan-harga">

                    </div>

                    <div class="form-group">
                        <label for="b_harga">Bobot Harga</label>
                        <input type="text" class="form-control" id="b_harga" name="b_harga"
                               placeholder="0">
                    </div>

                    <div class="form-group">
                        <label for="b_waktu">Bobot Waktu</label>
                        <input type="text" class="form-control" id="b_waktu" name="b_waktu"
                               placeholder="0">
                    </div>

                    <div class="form-group">
                        <label for="b_retur">Bobot Retur</label>
                        <input type="text" class="form-control" id="b_retur" name="b_retur"
                               placeholder="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="bahan-btn">Tambah Bahan</button>
                </div>
            </form>

        </div>
    </div>
</div>


<!-- Main Scripts -->

<script>

    var jsonData = "";
    var jsonBahan = "";
    var jsonSupplierBahan = "";
    var dt = $("#myTable").DataTable();
    var tbahan = $("#tableBahan").DataTable({
        "pageLength": 5,
        "lengthMenu": [[5, 20, 50, -1], [5, 20, 50, "All"]]
    });

    function loadData() {
        //clear table
        dt.clear().draw();

        $.ajax({
            url: "model/supplier.php?load",
            data: $(this).serialize(),
            dataType: "JSON",
            method: "POST",
            success: function (data) {
                console.log(data);
                if (data["error"] === 0) {
                    jsonData = data["data"];
                    for (let i = 0; i < jsonData.length; i++) {
                        dt.row.add([
                            i + 1,
                            jsonData[i].nama,
                            jsonData[i].telp,
                            jsonData[i].alamat,
                            '<button type="button" class="btn btn-text-success btn-icon rounded-circle" onclick="showDetail(' + i + ')"><i class="material-icons">remove_red_eye</i></button>\n' +
                            '<button type="button" class="btn btn-text-primary btn-icon rounded-circle" onclick="showModal(' + i + ')"><i class="material-icons">edit</i></button>\n' +
                            '<button type="button" class="btn btn-text-danger btn-icon rounded-circle" onclick="hapus(' + i + ')"><i class="material-icons">delete</i></button>'
                        ]).draw(false);
                    }
                } else if (data["error"] === 2) {
                    $('#table-body').html('' +
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
                    for (let i = 0; i < jsonBahan.length; i++) {
                        a += '<option value="' + jsonBahan[i].id + '">' + jsonBahan[i].nama + '</option>';
                    }
                    $("#s_bahan").html(a);
                    $("#bahan-select").html(a);
                } else if (data["error"] === 2) {
                    $('#s_bahan').html('<option value="">Gagal meload bahan</option>');
                    $('#bahan-select').html('<option value="">Gagal meload bahan</option>');
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
        }
        $("#myModal").modal("show");
    }

    function showDetail(index) {
        $(".modal-title-bahan").html(jsonData[index].nama);
        $("#modalSupplierId").val(jsonData[index].id);
        $("#modalSupplierBahan").modal("show");
        //clear table
        tbahan.clear().draw();

        loadSupplierBahan(jsonData[index].id);
    }

    function loadSupplierBahan(id_supplier) {
        tbahan.clear().draw();
        $.ajax({
            url: "model/supplier.php?load-bahan",
            data: {"id_supplier": id_supplier},
            dataType: "JSON",
            method: "POST",
            success: function (data) {
                console.log(data);
                if (data["error"] === 0) {
                    jsonSupplierBahan = data["data"];
                    for (let i = 0; i < jsonSupplierBahan.length; i++) {
                        tbahan.row.add([
                            i + 1,
                            jsonSupplierBahan[i].bahan,
                            jsonSupplierBahan[i].harga,
                            jsonSupplierBahan[i].bobot_harga,
                            jsonSupplierBahan[i].bobot_waktu,
                            jsonSupplierBahan[i].bobot_retur,
                            '<button type="button" class="btn btn-text-primary btn-icon rounded-circle" onclick="modalBahan(' + i + ')"><i class="material-icons">edit</i></button>\n' +
                            '<button type="button" class="btn btn-text-danger btn-icon rounded-circle" onclick="hapusBahan(' + i + ')"><i class="material-icons">delete</i></button>'
                        ]).draw(false);
                    }
                } else if (data["error"] === 2) {
                    $('#tableBahan').html('' +
                        '<tr>\n' +
                        '   <td scope="col" colspan="4"><center>' + data["msg"] + '</center></td>' +
                        '</tr>')
                }
            }
        });
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

    $("#bahan-form").on("submit", function (e) {
        e.preventDefault();

        $.ajax({
            url: "model/supplier.php?bahan-add-update",
            data: $(this).serialize(),
            dataType: "JSON",
            method: "POST",
            beforeSend: function () {
                $("#bahan-btn").prop("disabled", true);
            },
            success: function (data) {
                if (data["error"] === 0) {
                    $("#myModal").modal("hide");
                    loadSupplierBahan($("#modalSupplierId").val());
                    $("#modalBahan").modal('hide');
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

    function modalBahan(index) {
        $("#bahan-btn").prop("disabled", false);

        if (index === "") {
            $(".modal-bahan-title").html("Tambah Bahan");
            $("#bahan-btn").html("Tambah");
            $("#bahan-form").trigger("reset");
        } else {
            $(".modal-title").html("Ubah Bahan");
            $("#bahan-btn").html("Ubah");
            $("#id-supplier-bahan").val(jsonSupplierBahan[index].id);
            $("#bahan-harga").val(jsonSupplierBahan[index].harga);
            $("#bahan-select").val(jsonSupplierBahan[index].id_bahan);
            $("#b_harga").val(jsonSupplierBahan[index].bobot_harga);
            $("#b_waktu").val(jsonSupplierBahan[index].bobot_waktu);
            $("#b_retur").val(jsonSupplierBahan[index].bobot_retur);
        }
        $("#modalBahan").modal('show');
    }

    function hapus(index) {
        $.ajax({
            url: "model/supplier.php?hapus",
            data: {"id": jsonData[index].id},
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

    function hapusBahan(index) {
        $.ajax({
            url: "model/supplier.php?hapus-bahan",
            data: {"id": jsonSupplierBahan[index].id},
            dataType: "JSON",
            method: "POST",
            success: function (data) {
                showNotif(data);
                if (data["error"] === 0) {
                    loadSupplierBahan($("#modalSupplierId").val())
                }
            }
        });
    }

    App.checkAll()
</script>