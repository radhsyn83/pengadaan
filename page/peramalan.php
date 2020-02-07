<div class="row">
    <div class="col-md-6">
        <!-- Button date Tab -->
        <div class="btn-group btn-group-sm" role="group">
            <button type="button" class="btn btn-faded-success" id="mont1" onclick="loadData('1', this)">Jan
            </button>
            <button type="button" class="btn btn-faded-success" id="mont2" onclick="loadData('2', this)">Feb
            </button>
            <button type="button" class="btn btn-faded-success" id="mont3" onclick="loadData('3', this)">Mar
            </button>
            <button type="button" class="btn btn-faded-success" id="mont4" onclick="loadData('4', this)">Apr
            </button>
            <button type="button" class="btn btn-faded-success" id="mont5" onclick="loadData('5', this)">May
            </button>
            <button type="button" class="btn btn-faded-success" id="mont6" onclick="loadData('6', this)">Jun
            </button>
            <button type="button" class="btn btn-faded-success" id="mont7" onclick="loadData('7', this)">Jul
            </button>
            <button type="button" class="btn btn-faded-success" id="mont8" onclick="loadData('8', this)">Agu
            </button>
            <button type="button" class="btn btn-faded-success" id="mont9" onclick="loadData('9', this)">Sep
            </button>
            <button type="button" class="btn btn-faded-success" id="mont10" onclick="loadData('10', this)">Okt
            </button>
            <button type="button" class="btn btn-faded-success" id="mont11" onclick="loadData('11', this)">Nov
            </button>
            <button type="button" class="btn btn-faded-success" id="mont12" onclick="loadData('12', this)">Dec
            </button>
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
    <div class="col-md-2">
        <div class="form-group">
            <button id="generate_btn" type="button" class="btn btn-primary">Generate</button>
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
            <th scope="col">Estimasi</th>
            <th scope="col">Stok</th>
            <th scope="col">Jumlah</th>
            <th scope="col">Total Harga</th>
            <th scope="col">Pengadaan</th>
        </tr>
        </thead>
        <tbody id="table-main"></tbody>
    </table>
</div>


<!--Modal Supplier Bahan-->
<div class="modal fade" id="modalSupplier" tabindex="-1" role="dialog" aria-labelledby="basicModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
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
                        <th scope="col">Rangking</th>
                        <th scope="col">Nama Supplier</th>
                        <th scope="col">Harga</th>
                    </tr>
                    </thead>
                    <tbody id="table-main-supplier"></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>

    var jsonData = "";
    var selectedMonth = "";
    $('#generate_btn').hide();

    function loadData(m="", f=null) {
        removeSelected();
        $(f).addClass("active");
        selectedMonth = m;
        var y = $("#year").val();

        $.ajax({
            url: 'model/peramalan.php?load&m=' + m + '&y=' + y,
            data: $(this).serialize(),
            dataType: "JSON",
            method: "POST",
            success: function (data) {
                if (data["error"] === 0) {
                    jsonData = data["data"];

                    $('#generate_btn').hide();

                    let a = "";
                    for (let i = 0; i <jsonData.length; i++) {
                        a += '<tr>\n' +
                            '    <td scope="col">' + (i+1) + '</td>\n' +
                            '    <td scope="col">' + jsonData[i].bahan + '</td>\n' +
                            '    <td scope="col">' + jsonData[i].supplier + '</td>\n' +
                            '    <td scope="col">' + Math.ceil(jsonData[i].estimasi) + '</td>\n' +
                            '    <td scope="col">' + jsonData[i].stok + '</td>\n' +
                            '    <td scope="col">' + jsonData[i].jumlah + '</td>\n' +
                            '    <td scope="col">' + jsonData[i].total_harga + '</td>\n' +
                            '    <td scope="col">' + jsonData[i].pengadaan + '</td>\n' +
                            ' </tr>'
                    }
                    $("#table-main").html(a);
                } else if (data["error"] === 2) {
                    $('#generate_btn').show();
                    $('#table-main').html('' +
                        '<tr>\n' +
                        '   <td scope="col" colspan="8"><center>' + data["msg"] + '</center></td>' +
                        '</tr>')
                }
            }
        });
    }

    function loadDataSupplier(index) {
        $("#modalSupplier").modal("show");

        $.ajax({
            url: "model/peramalan.php?loadSupplier",
            data: {"id_peramalan" : jsonData[index].id},
            dataType: "JSON",
            method: "POST",
            success: function (ds) {
                if (ds["error"] === 0) {
                    let b = "";
                    let d = ds["data"];
                    console.log(d);
                    for (let i = 0; i <d.length; i++) {
                        b += '<tr>\n' +
                            '    <td scope="col">' + (i+1) + '</td>\n' +
                            '    <td scope="col">' + d[i].supplier + '</td>\n' +
                            '    <td scope="col">' + d[i].harga + '</td>\n' +
                            ' </tr>';

                    }
                    $("#table-main-supplier").html(b);
                } else if (ds["error"] === 2) {
                    $('#table-main-supplier').html('' +
                        '<tr>\n' +
                        '   <td scope="col" colspan="5"><center>' + data["msg"] + '</center></td>' +
                        '</tr>')
                }
            }
        });
    }

    $('#generate_btn').on('click', function (e) {

        let dialog = bootbox.dialog({
            message: `<div class="d-flex align-items-center">
                      <div class="spinner-border spinner-border-sm mr-2"></div> Mohon menunggu...
                    </div>`
        });

        var y = $("#year").val();

        $.ajax({
            url: "model/peramalan.php?generate&tahun=" + y + "&bulan=" + selectedMonth,
            data: $(this).serialize(),
            dataType: "JSON",
            method: "POST",
            success: function (data) {
                dialog.modal("hide");
                loadData(selectedMonth)
            }
        });
    });

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

</script>