
<button type="button" class="btn btn-success has-icon" onclick="showModal('')"><i class="material-icons mr-1">add</i>
    Tambah Bahan Keluar
</button>

<br>
<br>

<div class="row">
    <div class="col-md-8">
        <!-- Button date Tab -->
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-faded-success" id="mont1" onclick="loadData('01', this)">Jan</button>
            <button type="button" class="btn btn-faded-success" id="mont2" onclick="loadData('02', this)">Feb</button>
            <button type="button" class="btn btn-faded-success" id="mont3" onclick="loadData('03', this)">Mar</button>
            <button type="button" class="btn btn-faded-success" id="mont4" onclick="loadData('04', this)">Apr</button>
            <button type="button" class="btn btn-faded-success" id="mont5" onclick="loadData('05', this)">May</button>
            <button type="button" class="btn btn-faded-success" id="mont6" onclick="loadData('06', this)">Jun</button>
            <button type="button" class="btn btn-faded-success" id="mont7" onclick="loadData('07', this)">Jul</button>
            <button type="button" class="btn btn-faded-success" id="mont8" onclick="loadData('08', this)">Agu</button>
            <button type="button" class="btn btn-faded-success" id="mont9" onclick="loadData('09', this)">Sep</button>
            <button type="button" class="btn btn-faded-success" id="mont10" onclick="loadData('10', this)">Okt</button>
            <button type="button" class="btn btn-faded-success" id="mont11" onclick="loadData('11', this)">Nov</button>
            <button type="button" class="btn btn-faded-success" id="mont12" onclick="loadData('12', this)">Dec</button>
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
    <table id="myTable" class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Bahan</th>
            <th scope="col">Bahan Produksi</th>
            <th scope="col">Bahan Rusak</th>
            <th scope="col">Stok</th>
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
                    <input type="hidden" class="form-control" id="s_id" name="s_id">
                    <div class="form-group">
                        <label for="s_bahan">Bahan</label>
                        <select class="form-control" id="s_bahan" name="s_bahan" onchange="loadSupplier(this.value)"></select>
                    </div>
                    <div class="form-group">
                        <label for="s_jumlah_produksi">Jumlah Bahan Produksi</label>
                        <input type="text" class="form-control" id="s_jumlah_produksi" name="s_jumlah_produksi"
                               placeholder="Jumlah bahan produksi">
                    </div>
                    <div class="form-group">
                        <label for="s_jumlah_rusak">Jumlah Bahan Rusak</label>
                        <input type="text" class="form-control" id="s_jumlah_rusak" name="s_jumlah_rusak"
                               placeholder="Jumlah bahan rusak">
                    </div>
                    <div class="form-group">
                        <label for="s_tanggal">Tanggal Keluar</label>
                        <div class="input-group datepicker-wrap">
                            <input type="text" class="form-control flatpickr-input" id="s_tanggal" name="s_tanggal" placeholder="Tanggal Keluar" autocomplete="off" data-input="" readonly>
                            <div class="input-group-append">
                                <button class="btn btn-light btn-icon" type="button" title="Choose date" data-toggle><i class="material-icons">calendar_today</i></button>
                                <button class="btn btn-light btn-icon" type="button" title="Clear" data-clear><i class="material-icons">close</i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="modal-btn">Tambah Bahan Keluar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

    var jsonData = "";
    var jsonBahan = "";
    var table = $("#myTable").DataTable();

    function loadData(m="", f=null) {
        //clear table
        table.clear().draw();
        removeSelected();

        var y = $("#year").val();
        var url = 'model/bahan-keluar.php?load';

        if (m != "") {
            url = 'model/bahan-keluar.php?load&filter=' + m + '/' + y;
            $(f).addClass("active");
        } else {
            $("#montAll").addClass("active");
        }

        $.ajax({
            url: url,
            data: $(this).serialize(),
            dataType: "JSON",
            method: "POST",
            success: function (data) {
                console.log(data);
                if (data["error"] === 0) {
                    jsonData = data["data"];
                    for (let i = 0; i <jsonData.length; i++) {
                        table.row.add( [
                            i+1,
                            jsonData[i].bahan,
                            jsonData[i].bahan_produksi,
                            jsonData[i].bahan_rusak,
                            jsonData[i].stok,
                            $.format.date(jsonData[i].tanggal + " 00:00:00", "dd MMM yyyy"),
                            '<button type="button" class="btn btn-text-primary btn-icon rounded-circle" onclick="showModal(' + i + ')"><i class="material-icons">edit</i></button>\n' +
                            '<button type="button" class="btn btn-text-danger btn-icon rounded-circle" onclick="hapus(' + i + ')"><i class="material-icons">delete</i></button>'
                        ] ).draw( false );
                    }
                } else if (data["error"] === 2) {
                    $('#table-main').html('' +
                        '<tr>\n' +
                        '   <td scope="col" colspan="8"><center>' + data["msg"] + '</center></td>' +
                        '</tr>')
                }
            }
        });
    }

    function removeSelected() {
        $('#mont1').removeClass("active");
        $('#mont2').removeClass("active");
        $('#mont3').removeClass("active");
        $('#mont4').removeClass("active");
        $('#mont5').removeClass("active");
        $('#mont6').removeClass("active");
        $('#mont7').removeClass("active");
        $('#mont8').removeClass("active");
        $('#mont9').removeClass("active");
        $('#mont10').removeClass("active");
        $('#mont11').removeClass("active");
        $('#mont12').removeClass("active");
    }

    function loadBahanBaku() {
        $.ajax({
            url: "model/bahan-baku.php?load",
            data: $(this).serialize(),
            dataType: "JSON",
            method: "POST",
            beforeSend: function () {
                $('#s_bahan').html('<option value="">Loading....</option>');
            },
            success: function (data) {
                if (data["error"] === 0) {
                    jsonBahan = data["data"];
                    let a = "";
                    for (let i = 0; i <jsonBahan.length; i++) {
                        a += '<option value="' + jsonBahan[i].id + '">' + jsonBahan[i].nama + '</option>';
                    }
                    $("#s_bahan").html(a);
                    loadSupplier(jsonBahan[0].id);
                } else if (data["error"] === 2) {
                    $('#s_bahan').html('<option value="">Gagal meload bahan</option>');
                }
            }
        });
    }

    function showModal(index) {
        $("#modal-btn").prop("disabled", false);
        loadBahanBaku()

        if (index === "") {
            $(".modal-title").html("Tambah Bahan Keluar");
            $("#modal-btn").html("Tambah");
            $("#modal-form").trigger("reset");
        } else {
            $(".modal-title").html("Ubah Bahan Keluar");
            $("#modal-btn").html("Ubah");
            $("#s_id").val(jsonData[index].id);
            $("#s_bahan").val(jsonData[index].id_bahan);
            $("#s_bahan_produksi").val(jsonData[index].bahan_produksi);
            $("#s_bahan_rusak").val(jsonData[index].bahan_rusak);
            $("#s_tanggal").val(jsonData[index].tanggal);
        }
        $("#myModal").modal("show");
    }

    $("#modal-form").on("submit", function (e) {
        e.preventDefault();
        $.ajax({
            url: "model/bahan-keluar.php?add-update",
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
            url: "model/bahan-keluar.php?hapus",
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