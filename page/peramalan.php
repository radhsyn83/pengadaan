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
            <th scope="col">Bahan</th>
            <th scope="col">Supplier</th>
            <th scope="col">Jumlah Bahan</th>
            <th scope="col">Harga</th>
        </tr>
        </thead>
        <tbody id="table-main"></tbody>
    </table>
</div>

<script>

    var jsonData = "";

    function loadData() {
        $.ajax({
            url: "model/peramalan.php?load",
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
                            '            <td scope="col">' + jsonData[i].jumlah + '</td>\n' +
                            '            <td scope="col">' + jsonData[i].harga + '</td>\n' +
                            '        </tr>'
                    }
                    $("#table-main").html(a);
                } else if (data["error"] === 2) {
                    $('#table-main').html('' +
                        '<tr>\n' +
                        '   <td scope="col" colspan="5"><center>' + data["msg"] + '</center></td>' +
                        '</tr>')
                }
            }
        });
    }

    loadData();

</script>