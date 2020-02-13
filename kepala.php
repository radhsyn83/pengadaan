<!-- Button Tab -->
<div class="btn-group" role="group">
    <button type="button" id="tab-peramalan" class="btn btn-light active">Perencanaan</button>
    <button type="button" id="tab-pengadaan" class="btn btn-light">Pengadaan</button>
    <button type="button" id="tab-monitoring" class="btn btn-light">Monitoring</button>
</div>

<br>
<hr>

<!-- Content -->
<div id="staff-content"></div>
<!-- / Content -->
<script>
    //INIT PAGE
    menuOpen($(this), "peramalan");

    // MENU STAFF
    $('#tab-peramalan').click(function () {
        menuOpen($(this), "peramalan");
    });
    $('#tab-monitoring').click(function () {
        menuOpen($(this), "monitoring");
    });
    $('#tab-pengadaan').click(function () {
        menuOpen($(this), "pengadaan");
    });

    function resetMenuStaff() {
        $('#tab-peramalan').removeClass("active");
        $('#tab-pengadaan').removeClass("active");
    }

</script>