
<button type="button" class="btn btn-success has-icon" onclick="showModal('')"><i class="material-icons mr-1">add</i>
    Tambah Pengadaan
</button>

<br>
<br>

<div class="row">
    <div class="col-md-8">
        <!-- Button date Tab -->
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-faded-success active">Jan</button>
            <button type="button" class="btn btn-faded-success">Feb</button>
            <button type="button" class="btn btn-faded-success">Mar</button>
            <button type="button" class="btn btn-faded-success">Apr</button>
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
            <th scope="col">Nama Bahan</th>
            <th scope="col">Nama Supplier</th>
            <th scope="col">Tanggal Pengadaan</th>
            <th scope="col">Jumlah</th>
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
                    <input type="hidden" class="form-control" id="s_id" name="s_id">
                    <div class="form-group">
                        <label for="s_supplier">Nama Supplier</label>
                        <select class="form-control" id="s_supplier" name="s_supplier" onchange="loadBahan(this.value)"></select>
                    </div>
                    <div class="form-group">
                        <label for="s_supplier_bahan">Nama Bahan</label>
                        <select class="form-control" id="s_supplier_bahan" name="s_supplier_bahan"></select>
                    </div>
                    <div class="form-group">
                        <label for="s_tanggal_pengadaan">Tanggal Pengadaan</label>
                        <div class="input-group datepicker-wrap">
                            <input type="text" class="form-control flatpickr-input" id="s_tanggal_pengadaan" name="s_tanggal_pengadaan" placeholder="Tanggal Pengadaan" autocomplete="off" data-input="" readonly>
                            <div class="input-group-append">
                                <button class="btn btn-light btn-icon" type="button" title="Choose date" data-toggle><i class="material-icons">calendar_today</i></button>
                                <button class="btn btn-light btn-icon" type="button" title="Clear" data-clear><i class="material-icons">close</i></button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="s_jumlah">Jumlah Bahan</label>
                        <input type="text" class="form-control" id="s_jumlah" name="s_jumlah"
                               placeholder="Jumlah bahan">
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

    function loadData() {
        $.ajax({
            url: "model/pengadaan.php?load",
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
                            '            <td scope="col">' + jsonData[i].bahan + '</td>\n' +
                            '            <td scope="col">' + jsonData[i].supplier + '</td>\n' +
                            '            <td scope="col">' + jsonData[i].tanggal_pengadaan + '</td>\n' +
                            '            <td scope="col">' + jsonData[i].jumlah + '</td>\n' +
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
                        '   <td scope="col" colspan="8"><center>' + data["msg"] + '</center></td>' +
                        '</tr>')
                }
            }
        });
    }

    loadData();

    function loadBahan(v) {
        $.ajax({
            url: "model/bahan-baku.php?loadBySupplier",
            data: {"id_supplier" : v},
            dataType: "JSON",
            method: "POST",
            beforeSend: function () {
                $('#s_supplier_bahan').html('<option value="">Loading....</option>');
            },
            success: function (data) {
                if (data["error"] === 0) {
                    d = data["data"];
                    let a = "";
                    for (let i = 0; i < d.length; i++) {
                        a += '<option value="' + d[i].id + '">' + d[i].nama + '</option>';
                    }
                    $("#s_supplier_bahan").html(a);
                } else if (data["error"] === 2) {
                    $('#s_supplier_bahan').html('<option value="">Gagal meload bahan</option>');
                }
            }
        });
    }

    function loadSupplier() {
        $.ajax({
            url: "model/supplier.php?load",
            data: $(this).serialize(),
            dataType: "JSON",
            method: "POST",
            beforeSend: function () {
                $('#s_supplier').html('<option value="">Loading....</option>');
            },
            success: function (data) {
                if (data["error"] === 0) {
                    s = data["data"];
                    let a = "";
                    for (let i = 0; i <s.length; i++) {
                        a += '<option value="' + s[i].id + '">' + s[i].nama + '</option>';
                    }
                    $("#s_supplier").html(a);

                    loadBahan(s[0].id)
                } else if (data["error"] === 2) {
                    $('#s_supplier').html('<option value="">Gagal meload Supplier</option>');
                }
            }
        });
    }

    function showModal(index) {
        $("#modal-btn").prop("disabled", false);
        $("#s_supplier_bahan").html("");
        $("#s_supplier").html("");
        loadSupplier();

        if (index === "") {
            $(".modal-title").html("Tambah Supplier");
            $("#modal-btn").html("Tambah");
            $("#modal-form").trigger("reset");
        } else {
            $(".modal-title").html("Ubah Supplier");
            $("#modal-btn").html("Ubah");
            $("#s_id").val(jsonData[index].id);
            $("#s_tanggal_pengadaan").val(jsonData[index].tanggal_pengadaan);
            $("#s_jumlah").val(jsonData[index].jumlah);
        }
        $("#myModal").modal("show");
    }

    $("#modal-form").on("submit", function (e) {
        e.preventDefault();
        $.ajax({
            url: "model/pengadaan.php?add-update",
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
            url: "model/pengadaan.php?hapus",
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
    
    function supplierSelected(v) {
        loadBahan(v.value);
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