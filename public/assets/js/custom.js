// $(document).ready(function () {
//     $("#class_id").select2();
//     $("#category_id").select2();
//     $("#quality_id").select2();
//     $("#item_unit_id").select2();
//     $("#empty_weight_id").select2();
//     $("#countable_unit_id").select2();
//     $("#full_weight_id").select2();
//     $("#density_weight_id").select2();
//     $("#density_unit_id").select2();
// });

$(document).ready(function () {
    $(document).on("change", "select[name^='package_name[]']", function () {
        var id = $(this).data("id");
        var package_name = $(this).val();
        var item_unit_id = $("#item_unit_id").val();
        $("#size_per_case_" + id).html(item_unit_id + " per " + package_name);
        var package_size = $("#package_size_" + id).val();
        $("#summary_package_size_" + id).html(
            package_size + " " + item_unit_id + " / " + package_name
        );
    });

    $("#summary_class").html($("#class_id").find(":selected").text());

    $("#class_id").on("change", function () {
        $("#summary_class").html($("#class_id").find(":selected").text());
        var class_value = $("#class_id").find(":selected").text();
        if (class_value.includes("Beer") || class_value.includes("Coolers")) {
            document.getElementById("div4").style.display = "block";
            document.getElementById("div5").style.display = "block";
            document.getElementById("div6").style.display = "none";
            document.getElementById("div7").style.display = "none";
            document.getElementById("div8").style.display = "none";
            $("#summary_inventory_method").html("Count Only");
        } else if (class_value.includes("Miscellaneous")) {
            document.getElementById("div4").style.display = "block";
            document.getElementById("div7").style.display = "block";
            document.getElementById("div5").style.display = "block";
            document.getElementById("div6").style.display = "none";
            document.getElementById("div8").style.display = "none";
            $("#summary_inventory_method").html("Count Only");
        } else {
            document.getElementById("div4").style.display = "block";
            document.getElementById("div6").style.display = "block";
            document.getElementById("div5").style.display = "none";
            document.getElementById("div7").style.display = "none";
            document.getElementById("div8").style.display = "block";
            $("#summary_inventory_method").html("Count & Weight");
        }
    });

    $("#summary_category").html($("#category_id").find(":selected").text());

    $("#category_id").on("change", function () {
        $("#summary_category").html($("#category_id").find(":selected").text());
    });

    $("#summary_quality").html($("#quality_id").find(":selected").text());

    $("#quality_id").on("change", function () {
        $("#summary_quality").html($("#quality_id").find(":selected").text());
    });

    $("#summary_title").html($("#name").val());

    $("#name").keyup(function () {
        var name = $(this).val();
        $("#summary_title").html(name);
    });

    $("#summary_barcode").html($("#barcode").val());

    $("#barcode").keyup(function () {
        var barcode = $(this).val();
        $("#summary_barcode").html(barcode);
    });

    $("#summary_size").html(
        $("#countable_unit").val() +
            " " +
            $("#countable_unit_id").val() +
            " / " +
            $("#item_unit_id").val()
    );

    $("#countable_unit").keyup(function () {
        var countable_unit = $(this).val();
        var countable_unit_id = $("#countable_unit_id").val();
        var item_unit_id = $("#item_unit_id").val();
        $("#summary_size").html(
            countable_unit + " " + countable_unit_id + " / " + item_unit_id
        );
    });

    $("#countable_unit_id").on("change", function () {
        var countable_unit = $("#countable_unit").val();
        var countable_unit_id = $(this).val();
        var item_unit_id = $("#item_unit_id").val();
        $("#summary_size").html(
            countable_unit + " " + countable_unit_id + " / " + item_unit_id
        );
    });

    $("#summary_empty_weight").html(
        $("#empty_weight").val() + " " + $("#empty_weight_id").val()
    );

    $("#empty_weight").keyup(function () {
        var empty_weight = $(this).val();
        var empty_weight_id = $("#empty_weight_id").val();
        $("#summary_empty_weight").html(empty_weight + " " + empty_weight_id);
    });

    $("#empty_weight_id").on("change", function () {
        var empty_weight = $("#empty_weight").val();
        var empty_weight_id = $(this).val();
        $("#summary_empty_weight").html(empty_weight + " " + empty_weight_id);
    });

    $("#summary_density").html(
        $("#density").val() +
            " " +
            $("#density_weight_id").val() +
            " / " +
            $("#density_unit_id").val()
    );

    $(document).on("keyup", "[name^='package_size[]']", function () {
        var id = $(this).data("id");
        var package_name = $("#package_name_" + id).val();
        var item_unit_id = $("#item_unit_id").val();
        var package_size = $("#package_size_" + id).val();
        $("#summary_package_size_" + id).html(
            package_size + " " + item_unit_id + " / " + package_name
        );
    });

    $(document).on("keyup", "[name^='package_barcode[]']", function () {
        var id = $(this).data("id");
        var package_barcode = $("#package_barcode_" + id).val();
        $("#summary_package_barcode_" + id).html(package_barcode);
    });

    $("#full_weight_id").on("change", function () {
        $("#empty_weight_id").val(this.value).select2();
    });

    $("#empty_weight_id").on("change", function () {
        $("#full_weight_id").val(this.value).select2();
    });

    // Regardless of WHICH radio was clicked, is the
    //  showSelect radio active?
    if ($("#package").is(":checked")) {
        $("#div1").show();
        $("#addMore").show();
    } else {
        $("#div1").hide();
    }
});

function show1() {
    document.getElementById("div1").style.display = "none";
    document.getElementById("addMore").style.display = "none";
    document.getElementById("div2").style.display = "block";
    document.getElementById("div3").style.display = "none";
}

function show2() {
    document.getElementById("div1").style.display = "block";
    document.getElementById("addMore").style.display = "block";
    document.getElementById("div2").style.display = "none";
    document.getElementById("div3").style.display = "block";
}

function show3() {
    document.getElementById("div4").style.display = "block";
}

function show4() {
    document.getElementById("div4").style.display = "none";
}

function show5() {
    document.getElementById("div5").style.display = "block";
    document.getElementById("div6").style.display = "none";
    document.getElementById("div8").style.display = "none";
    $("#summary_inventory_method").html("Count Only");
}

function show6() {
    document.getElementById("div4").style.display = "block";
    document.getElementById("div5").style.display = "none";
    document.getElementById("div6").style.display = "block";
    document.getElementById("div8").style.display = "block";
    $("#summary_inventory_method").html("Count & Weight");
}
