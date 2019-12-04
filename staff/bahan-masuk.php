<div class="row">
    <div class="col-md-3">
        <button type="button" class="btn btn-success has-icon" onclick="showModal('')"><i class="material-icons mr-1">add</i>
            Tambah Bahan Masuk
        </button>
    </div>
    <div class="col-md-6">
        <!-- Button date Tab -->
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-faded-success active">Apr</button>
            <button type="button" class="btn btn-faded-success">May</button>
            <button type="button" class="btn btn-faded-success">Jun</button>
            <button type="button" class="btn btn-faded-success">Jul</button>
            <button type="button" class="btn btn-faded-success">Agu</button>
            <button type="button" class="btn btn-faded-success">Sep</button>
            <button type="button" class="btn btn-faded-success">Okt</button>
            <button type="button" class="btn btn-faded-success">Nov</button>
            <button type="button" class="btn btn-faded-success">Dec</button>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <select class="form-control" id="year" name="year">
                <option value="2018" selected>2018</option>
                <option value="2019">2019</option>
            </select>
        </div>
    </div>
</div>
<br>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Bahan</th>
            <th scope="col">Supplier</th>
            <th scope="col">Tanggal Datang</th>
            <th scope="col">Jumlah Masuk</th>
            <th scope="col">Jumlah Retur</th>
            <th scope="col">Kebutuhan</th>
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
                    <input type="hidden" class="form-control" id="s_id" name="s_id">
                    <div class="form-group">
                        <label for="s_bahan">Bahan</label>
                        <select class="form-control" id="s_bahan" name="s_bahan"></select>
                    </div>
                    <div class="form-group">
                        <label for="s_supplier">Supplier</label>
                        <select class="form-control" id="s_supplier" name="s_supplier"></select>
                    </div>
                    <div class="form-group">
                        <label for="s_supplier">Tanggal Masuk</label>
                        <div class="input-group datepicker-wrap">
                            <input type="text" class="form-control flatpickr-input" id="s_tanggal_masuk" name="s_tanggal_masuk" placeholder="Tanggal Masuk" autocomplete="off" data-input="" readonly>
                            <div class="input-group-append">
                                <button class="btn btn-light btn-icon" type="button" title="Choose date" data-toggle><i class="material-icons">calendar_today</i></button>
                                <button class="btn btn-light btn-icon" type="button" title="Clear" data-clear><i class="material-icons">close</i></button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="s_jumlah_masuk">Jumlah Bahan Masuk</label>
                        <input type="text" class="form-control" id="s_jumlah_masuk" name="s_jumlah_masuk"
                               placeholder="Jumlah bahan masuk">
                    </div>
                    <div class="form-group">
                        <label for="s_jumlah_retur">Jumlah Bahan Retur</label>
                        <input type="text" class="form-control" id="s_jumlah_retur" name="s_jumlah_retur"
                               placeholder="Jumlah bahan retur">
                    </div>
                    <div class="form-group">
                        <label for="s_kebutuhan">Kebutuhan Barang</label>
                        <input type="text" class="form-control" id="s_kebutuhan" name="s_kebutuhan"
                               placeholder="Kebutuhan barang">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="modal-btn">Tambah Bahan Masuk</button>
                </div>
            </form>


        </div>
    </div>
</div>

<script>

    var jsonData = "";
    var jsonBahan = "";
    var jsonSupplier = "";

    function loadData() {
        $.ajax({
            url: "model/bahan-masuk.php?load",
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
                            '            <td scope="col">' + jsonData[i].bahan + '</td>\n' +
                            '            <td scope="col">' + jsonData[i].supplier + '</td>\n' +
                            '            <td scope="col">' + jsonData[i].tanggal_masuk + '</td>\n' +
                            '            <td scope="col">' + jsonData[i].jumlah_masuk + '</td>\n' +
                            '            <td scope="col">' + jsonData[i].jumlah_retur + '</td>\n' +
                            '            <td scope="col">' + jsonData[i].kebutuhan + '</td>\n' +
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
                        '   <td scope="col" colspan="8"><center>' + data["msg"] + '</center></td>' +
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

    function loadSupplier() {
        $.ajax({
            url: "model/supplier.php?load",
            data: $(this).serialize(),
            dataType: "JSON",
            method: "POST",
            success: function (data) {
                if (data["error"] === 0) {
                    jsonSupplier = data["data"];
                    let a = "";
                    for (let i = 0; i <jsonSupplier.length; i++) {
                        a += '<option value="' + jsonSupplier[i].id + '">' + jsonSupplier[i].nama + '</option>';
                    }
                    $("#s_supplier").html(a);
                } else if (data["error"] === 2) {
                    $('#s_supplier').html('<option value="">Gagal meload Supplier</option>');
                }
            }
        });
    }

    loadSupplier();

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
            $("#s_bahan select").val(jsonBahan[index].nama);
            $("#s_supplier select").val(jsonSupplier[index].nama);
            $("#s_tanggal_masuk").val(jsonData[index].tanggal_masuk);
            $("#s_jumlah_masuk").val(jsonData[index].jumlah_masuk);
            $("#s_jumlah_retur").val(jsonData[index].jumlah_retur);
            $("#s_kebutuhan").val(jsonData[index].kebutuhan);
        }
        $("#myModal").modal("show");
    }

    $("#modal-form").on("submit", function (e) {
        e.preventDefault();
        $.ajax({
            url: "model/bahan-masuk.php?add-update",
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
            url: "model/bahan-masuk.php?hapus",
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

<script>
    $(() => {

        // Inline
        flatpickr('.datepicker-inline', {
            inline: true,
        })

        // Basic
        flatpickr('.datepicker')

        // Datetime
        flatpickr('.datetimepicker', {
            enableTime: true
        })

        // Allow Input
        flatpickr('.datepicker-input', {
            allowInput: true
        })

        // External elements
        flatpickr('.datepicker-wrap', {
            allowInput: true,
            clickOpens: false,
            wrap: true,
        })

        // Date Range
        flatpickr('.daterangepicker', {
            mode: 'range'
        })
        flatpickr('.daterangepicker-wrap', {
            allowInput: true,
            clickOpens: false,
            wrap: true,
            mode: 'range'
        })

        // Multiple Dates
        flatpickr('.datepicker-multiple', {
            mode: 'multiple'
        })
        flatpickr('.datepicker-multiple-wrap', {
            allowInput: true,
            clickOpens: false,
            wrap: true,
            mode: 'multiple'
        })

        // Month Picker
        flatpickr('.monthpicker', {
            plugins: [
                new monthSelectPlugin({
                    shorthand: true,
                    dateFormat: 'Y-m',
                    altFormat: 'Y-m',
                })
            ]
        })
        flatpickr('.monthpicker-wrap', {
            allowInput: true,
            clickOpens: false,
            wrap: true,
            plugins: [
                new monthSelectPlugin({
                    shorthand: true,
                    dateFormat: 'Y-m',
                    altFormat: 'Y-m',
                })
            ]
        })

        // Time Picker
        flatpickr('.timepicker', {
            enableTime: true,
            noCalendar: true,
            dateFormat: 'H:i',
            minuteIncrement: 1,
        })
        flatpickr('.timepicker-wrap', {
            allowInput: true,
            enableTime: true,
            noCalendar: true,
            dateFormat: 'H:i',
            minuteIncrement: 1,
            clickOpens: false,
            wrap: true,
        })

        // Clock Picker
        $('.clockpicker').clockpicker({ autoclose: true })

    })
</script>