<section id="section6">
    <div class="row">
        <div class="col-sm-3">
            <select class="form-control" name="filter_bahan" id="filter_bahan">
                <option value="1">Pilih Bahan</option>
            </select>
        </div>
        <div class="col-sm-2">
            <select class="form-control" name="filter_tahun" id="filter_tahun">
                <option value="2018">2018</option>
                <option value="2019" selected>2019</option>
                <option value="2020">2020</option>
            </select>
        </div>
    </div>
</section>

<br>

<div class="row gutters-sm">

    <!-- Monthly Kuisioner -->
    <div class="col-xl-12 mb-3">
        <div class="card h-100">
            <div class="card-header py-1">
                <i class="material-icons mr-2">show_chart</i>
                <h6>Monitoring</h6>
                <button type="button" data-action="fullscreen"
                        class="btn btn-sm btn-text-secondary btn-icon rounded-circle shadow-none ml-auto">
                    <i class="material-icons">fullscreen</i>
                </button>
            </div>
            <div class="card-body" style="height: 350px">
                <div class="chartjs-size-monitor">
                    <div class="chartjs-size-monitor-expand">
                        <div class=""></div>
                    </div>
                    <div class="chartjs-size-monitor-shrink">
                        <div class=""></div>
                    </div>
                </div>
                <canvas id="bar-chart-multi" style="display: block; width: 660px; height: 318px;" width="660"
                        height="318" class="chartjs-render-monitor"></canvas>
            </div>
        </div>
    </div>
    <!-- /Monthly Kuisioner -->

</div>

<script>

    function loadChart() {
        var idBahan = $("#filter_bahan").val()
        var tahun = $("#filter_tahun").val()
        $.ajax({
            url: "model/monitoring.php?load_chart",
            data: {"bahan": idBahan, "tahun": tahun},
            dataType: "JSON",
            method: "POST",
            beforeSend: function () {
            },
            success: function (data) {
                load_chart(data.data)
            }
        });
    }

    loadChart()

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
                    $("#filter_bahan").html(a);
                    loadSupplier(jsonBahan[0].id);
                } else if (data["error"] === 2) {
                    $('#filter_bahan').html('<option value="">Gagal meload bahan</option>');
                }
            }
        });
    }

    loadBahanBaku()

    $("#filter_bahan").on("change", function (e) {
        loadChart()
    })

    $("#filter_tahun").on("change", function (e) {
        loadChart()
    })

    function daysInMonth () {
        var today = new Date();
        var dateSize = new Date(today.getFullYear(), (today.getMonth()+1), 0).getDate();
        var dayArray = [];
        for (let i = 1; i <= dateSize; i++) {
            dayArray.push(i+" "+months[today.getMonth()])
        }
        return dayArray
    }

    function load_chart(ch) {
        var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Agu','Sep','Ock','Nov','Des']
        data1 = ch.estimasi
        data2 = ch.produksi

        // Global configuration
        Chart.defaults.global.elements.rectangle.borderWidth = 1 // bar border width
        Chart.defaults.global.elements.line.borderWidth = 1 // label border width
        Chart.defaults.global.maintainAspectRatio = false // disable the maintain aspect ratio in options then it uses the available height

        /***************** MULTI AXIS *****************/
        new Chart('bar-chart-multi', {
            type: 'bar',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Jumlah Estimasi',
                        backgroundColor: Chart.helpers.color(cyan).alpha(0.5).rgbString(),
                        borderColor: cyan,
                        data: data1
                    },
                    {
                        label: 'Jumlah Produksi',
                        backgroundColor: Chart.helpers.color(yellow).alpha(0.5).rgbString(),
                        borderColor: yellow,
                        data: data2
                    }
                ]
            },
            options: {
                tooltips: {
                    mode: 'index',
                    intersect: false
                    // Interactions configuration: https://www.chartjs.org/docs/latest/general/interactions/
                }
            }
        })
    }

</script>