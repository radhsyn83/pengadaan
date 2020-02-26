<button type="button" class="btn btn-success has-icon" onclick="showModal('')"><i class="material-icons mr-1">add</i>
    Tambah Pengadaan
</button>

<br>
<br>

<div class="row">
    <div class="col-md-7">
        <!-- Button date Tab -->
        <div class="btn-group" role="group">
            <div class="btn-group btn-group-sm" role="group">
<!--                <button type="button" class="btn btn-faded-success active" id="montAll" onclick="loadData('', this)">-->
<!--                    All-->
<!--                </button>-->
                <button type="button" class="btn btn-faded-success" id="mont1" onclick="loadData('01', this)">Jan
                </button>
                <button type="button" class="btn btn-faded-success" id="mont2" onclick="loadData('02', this)">Feb
                </button>
                <button type="button" class="btn btn-faded-success" id="mont3" onclick="loadData('03', this)">Mar
                </button>
                <button type="button" class="btn btn-faded-success" id="mont4" onclick="loadData('04', this)">Apr
                </button>
                <button type="button" class="btn btn-faded-success" id="mont5" onclick="loadData('05', this)">May
                </button>
                <button type="button" class="btn btn-faded-success" id="mont6" onclick="loadData('06', this)">Jun
                </button>
                <button type="button" class="btn btn-faded-success" id="mont7" onclick="loadData('07', this)">Jul
                </button>
                <button type="button" class="btn btn-faded-success" id="mont8" onclick="loadData('08', this)">Agu
                </button>
                <button type="button" class="btn btn-faded-success" id="mont9" onclick="loadData('09', this)">Sep
                </button>
                <button type="button" class="btn btn-faded-success" id="mont10" onclick="loadData('10', this)">Okt
                </button>
                <button type="button" class="btn btn-faded-success" id="mont11" onclick="loadData('11', this)">Nov
                </button>
                <button type="button" class="btn btn-faded-success" id="mont12" onclick="loadData('12', this)">Dec
                </button>
            </div>
        </div>

    </div>

    <div class="col-md-2">
        <div class="form-group">
            <select class="form-control" id="year" name="year">
                <option value="2018">2018</option>
                <option value="2019" selected>2019</option>
            </select>
        </div>
    </div>
    <br>

    <div class="table-responsive">
        <table id="myTable" class="table table-striped">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nama Bahan</th>
                <th scope="col">Nama Supplier</th>
                <th scope="col">Tanggal Pengadaan</th>
                <th scope="col">Jumlah</th>
                <th scope="col">Total Harga</th>
<!--                <th scope="col">Aksi</th>-->
            </tr>
            </thead>
            <tbody id="table-main"></tbody>
        </table>
    </div>


    <!--Modal-->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="basicModalLabel"
         aria-hidden="true">
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
                            <label for="nama_bahan">Nama Bahan</label>
                            <select class="form-control" id="nama_bahan" name="nama_bahan" onchange="getPeramalan()" ></select>
                        </div>
                        <div class="form-group">
                            <label for="s_tanggal_pengadaan">Tanggal Pengadaan</label>
                            <div class="input-group datepicker-wrap">
                                <input type="text" class="form-control flatpickr-input" id="s_tanggal_pengadaan"
                                       name="s_tanggal_pengadaan" placeholder="Tanggal Pengadaan" autocomplete="off"
                                       data-input="" onchange="getPeramalan()" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-light btn-icon" type="button" title="Choose date"
                                            data-toggle><i class="material-icons">calendar_today</i></button>
                                    <button class="btn btn-light btn-icon" type="button" title="Clear" data-clear><i
                                                class="material-icons">close</i></button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="s_supplier">Nama Supplier</label>
                            <select class="form-control" id="s_supplier_bahan" name="s_supplier_bahan"></select>
                        </div>
                        <div class="form-group">
                            <label for="s_jumlah">Jumlah</label>
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
        var table = $("#myTable").DataTable();
        var selectedIdBahan = "";

        function loadData(m="", f=null) {
            //clear table
            table.clear().draw();
            removeSelected();

            var y = $("#year").val();
            var url = 'model/pengadaan.php?load';

            if (m != "") {
                url = 'model/pengadaan.php?load&filter=' + m + '/' + y;
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
                    if (data["error"] === 0) {
                        jsonData = data["data"];
                        let a = "";
                        for (let i = 0; i < jsonData.length; i++) {
                            table.row.add( [
                                i+1,
                                jsonData[i].bahan,
                                jsonData[i].supplier,
                                $.format.date(jsonData[i].tanggal_pengadaan + " 00:00:00", "dd MMM yyyy"),
                                jsonData[i].jumlah,
                                jsonData[i].harga
                                // 
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

        // loadData();

        function removeSelected() {
            $('#montAll').removeClass("active");
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
                    $('#nama_bahan').html('<option value="">Loading....</option>');
                },
                success: function (data) {
                    if (data["error"] === 0) {
                        jsonBahan = data["data"];
                        let a = "";
                        for (let i = 0; i <jsonBahan.length; i++) {
                            a += '<option value="' + jsonBahan[i].id + '">' + jsonBahan[i].nama + '</option>';
                        }
                        $("#nama_bahan").html(a);
                    } else if (data["error"] === 2) {
                        $('#nama_bahan').html('<option value="">Gagal meload bahan</option>');
                    }
                }
            });
        }

        loadBahanBaku()

        // function getPeramalan() {
        //
        //     var bahan = $("#s_supplier_bahan").val();
        //     var tgl = $("#s_tanggal_pengadaan").val();
        //
        //     if (bahan !== "" && tgl !== "") {
        //         $.ajax({
        //             url: "model/peramalan.php?loadBahanSisa",
        //             data: {"tanggal": tgl, "bahan": bahan},
        //             dataType: "JSON",
        //             method: "POST",
        //             beforeSend: function () {
        //             },
        //             success: function (data) {
        //                 if (data["error"] === 0) {
        //                     $("#s_jumlah").val(Math.floor(data["data"]));
        //                 } else if (data["error"] === 2) {
        //                     $('#s_supplier_bahan').html('<option value="">Gagal meload bahan</option>');
        //                 }
        //             }
        //         });
        //     }
        //
        // }

        function getPeramalan() {

            var bahan = $("#nama_bahan").val();
            var tgl = $("#s_tanggal_pengadaan").val();

            if (bahan !== "" && tgl !== "") {
                $.ajax({
                    url: "model/pengadaan.php?loadPeramalan",
                    data: {"tanggal": tgl, "bahan": bahan},
                    dataType: "JSON",
                    method: "POST",
                    beforeSend: function () {
                        $('#s_supplier_bahan').html('<option value="">Loading....</option>');
                        $('#jumlah').html('Loading....');
                    },
                    success: function (data) {
                        if (data["error"] === 0) {
                            jsonSupp = data["supplier"]
                            let a = "";
                            for (let i = 0; i <jsonSupp.length; i++) {
                                a += '<option value="' + jsonSupp[i].id_supplier_bahan + '">' + jsonSupp[i].nama_supplier + '</option>';
                            }
                            $("#s_supplier_bahan").html(a);


                            $("#s_jumlah").val(data["jumlah"]);
                        } else if (data["error"] === 2) {
                            $('#s_supplier_bahan').html('<option value="">Gagal meload bahan</option>');
                        }
                    }
                });
            }

        }

        function loadBahan(v) {
            $.ajax({
                url: "model/bahan-baku.php?loadBySupplier",
                data: {"id_supplier": v},
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
                            a += '<option value="' + d[i].id + '#' + d[i].id_bahan + '">' + d[i].nama + '</option>';
                        }
                        $("#s_supplier_bahan").html(a);
                        getPeramalan()
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
                    $('#nama_bahan').html('<option value="">Loading....</option>');
                },
                success: function (data) {
                    if (data["error"] === 0) {
                        s = data["data"];
                        let a = "";
                        for (let i = 0; i < s.length; i++) {
                            a += '<option value="' + s[i].id + '">' + s[i].nama + '</option>';
                        }
                        $("#nama_bahan").html(a);
                    } else if (data["error"] === 2) {
                        $('#nama_bahan').html('<option value="">Gagal meload Supplier</option>');
                    }
                }
            });
        }

        function showModal(index) {
            $("#modal-btn").prop("disabled", false);
            $("#s_supplier_bahan").html("");
            $("#s_supplier").html("");
            // loadSupplier();

            if (index === "") {
                $(".modal-title").html("Tambah Pengadaan");
                $("#modal-btn").html("Tambah");
                $("#modal-form").trigger("reset");
            } else {
                $(".modal-title").html("Ubah Pengadaan");
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
            $('.clockpicker').clockpicker({autoclose: true})

        })


    </script>