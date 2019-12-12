<button type="button" class="btn btn-success has-icon" onclick="showModal('')"><i class="material-icons mr-1">add</i>
    Tambah Bahan Baku
</button>
<br>
<br>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nama</th>
            <th scope="col">Tanggal</th>
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
                        <label for="nama-bahan">Bahan Baku</label>
                        <input type="text" class="form-control" id="nama-bahan" name="nama-bahan"
                               placeholder="Masukkan nama bahan">
                        <input type="hidden" class="form-control" id="id-bahan" name="id-bahan">
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

    function loadBahanBaku() {
        $.ajax({
            url: "model/bahan-baku.php?load",
            data: $(this).serialize(),
            dataType: "JSON",
            method: "POST",
            success: function (data) {
                if (data["error"] === 0) {
                    jsonData = data["data"];
                    let a = "";
                    for (let i = 0; i <jsonData.length; i++) {
                        a += '<tr>\n' +
                            '            <td scope="col">' + (i+1) + '</td>\n' +
                            '            <td scope="col">' + jsonData[i].nama + '</td>\n' +
                            '            <td scope="col">' + jsonData[i].date_add + '</td>\n' +
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
                        '   <td scope="col" colspan="4"><center>' + data["msg"] + '</center></td>' +
                        '</tr>')
                }
            }
        });
    }

    loadBahanBaku();

    function showModal(index) {
        $("#modal-btn").prop("disabled", false);

        if (index === "") {
            $(".modal-title").html("Tambah Bahan Baku");
            $("#modal-btn").html("Tambah");
            $("#nama-bahan").val("");
            $("#id-bahan").val("");
        } else {
            $(".modal-title").html("Ubah Bahan Baku");
            $("#modal-btn").html("Ubah");
            $("#nama-bahan").val(jsonData[index].nama);
            $("#id-bahan").val(jsonData[index].id);
        }
        $("#myModal").modal("show");
    }

    $("#modal-form").on("submit", function (e) {
        e.preventDefault();
        $.ajax({
            url: "model/bahan-baku.php?add-update",
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
                    loadBahanBaku()
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
            url: "model/bahan-baku.php?hapus",
            data: {"id" : jsonData[index].id},
            dataType: "JSON",
            method: "POST",
            success: function (data) {
                showNotif(data);
                if (data["error"] === 0) {
                    loadBahanBaku()
                }
            }
        });
    }

    function hideLoading() {
        loadingModal.modal('hide')
    }

</script>