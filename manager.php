<!-- Button Tab -->
<div class="btn-group" role="group">
    <button type="button" id="tab-bahan-masuk" class="btn btn-light">Bahan Masuk</button>
    <button type="button" id="tab-bahan-keluar" class="btn btn-light">Bahan Keluar</button>
</div>

<br>
<hr>

<!-- Content -->
<div id="staff-content"></div>
<!-- / Content -->
<script>
    //INIT PAGE
    menuOpen($(this), "bahan-masuk");

    // MENU STAFF
    $('#tab-bahan-masuk').click(function () {
        menuOpen($(this), "bahan-masuk");
    });
    $('#tab-bahan-keluar').click(function () {
        menuOpen($(this), "bahan-keluar");
    });

    function resetMenuStaff() {
        $('#tab-bahan-keluar').removeClass("active");
        $('#tab-bahan-masuk').removeClass("active");
    }

</script>