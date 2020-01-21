<!-- Button Tab -->
<div class="btn-group" role="group">
    <button type="button" id="tab-bahan-baku" class="btn btn-light active">Bahan Baku</button>
    <button type="button" id="tab-supplier" class="btn btn-light">Supplier</button>
</div>

<br>
<hr>

<!-- Content -->
<div id="staff-content"></div>
<!-- / Content -->
<script>
    //INIT PAGE
    menuOpen($(this), "bahan-baku");

    // MENU STAFF
    $('#tab-bahan-baku').click(function () {
        menuOpen($(this), "bahan-baku");
    });
    $('#tab-supplier').click(function () {
        menuOpen($(this), "supplier");
    });

    function resetMenuStaff() {
        $('#tab-bahan-baku').removeClass("active");
        $('#tab-supplier').removeClass("active");
    }

</script>