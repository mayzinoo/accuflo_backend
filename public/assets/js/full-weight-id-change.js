$(document).ready(function () {
    $("input").keyup(function () {
        var item_unit_id = $("#item_unit_id").val();
        var countable_unit = $("#countable_unit").val();
        var empty_weight = $("#empty_weight").val();
        var full_weight = $("#full_weight").val();

        $("#full_weight_id").on("change", function () {
            $("#empty_weight_id").val(this.value).select2();
            if (
                this.value === "g" &&
                $("#countable_unit_id").val() === "ml" &&
                $("#empty_weight_id").val() === "g"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    parseInt(diff_weight) / parseInt(countable_unit);
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "kg" &&
                $("#countable_unit_id").val() === "ml" &&
                $("#empty_weight_id").val() === "kg"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    (parseInt(diff_weight) / parseInt(countable_unit)) * 1000;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "dry oz" &&
                $("#countable_unit_id").val() === "ml" &&
                $("#empty_weight_id").val() === "dry oz"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    (parseInt(diff_weight) / parseInt(countable_unit)) *
                    28.3495;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "lb" &&
                $("#countable_unit_id").val() === "ml" &&
                $("#empty_weight_id").val() === "lb"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    (parseInt(diff_weight) / parseInt(countable_unit)) *
                    453.592;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "mg" &&
                $("#countable_unit_id").val() === "ml" &&
                $("#empty_weight_id").val() === "mg"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    parseInt(diff_weight) / parseInt(countable_unit) / 1000;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "g" &&
                $("#countable_unit_id").val() === "L" &&
                $("#empty_weight_id").val() === "g"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    parseInt(diff_weight) / parseInt(countable_unit) / 1000;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "kg" &&
                $("#countable_unit_id").val() === "L" &&
                $("#empty_weight_id").val() === "kg"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    parseInt(diff_weight) / parseInt(countable_unit);
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "dry oz" &&
                $("#countable_unit_id").val() === "L" &&
                $("#empty_weight_id").val() === "dry oz"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    ((parseInt(diff_weight) / parseInt(countable_unit)) *
                        28.3495) /
                    1000;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "lb" &&
                $("#countable_unit_id").val() === "L" &&
                $("#empty_weight_id").val() === "lb"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    (parseInt(diff_weight) / parseInt(countable_unit)) *
                    0.453592;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "mg" &&
                $("#countable_unit_id").val() === "L" &&
                $("#empty_weight_id").val() === "mg"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    parseInt(diff_weight) / parseInt(countable_unit) / 1000000;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "g" &&
                $("#countable_unit_id").val() === "oz" &&
                $("#empty_weight_id").val() === "g"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    (parseInt(diff_weight) / parseInt(countable_unit)) *
                    0.0338140566667;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "kg" &&
                $("#countable_unit_id").val() === "oz" &&
                $("#empty_weight_id").val() === "kg"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    (parseInt(diff_weight) / parseInt(countable_unit)) *
                    0.0338140566667 *
                    1000;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "dry oz" &&
                $("#countable_unit_id").val() === "oz" &&
                $("#empty_weight_id").val() === "dry oz"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    (parseInt(diff_weight) / parseInt(countable_unit)) *
                    0.95861166667;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "lb" &&
                $("#countable_unit_id").val() === "oz" &&
                $("#empty_weight_id").val() === "lb"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    (parseInt(diff_weight) / parseInt(countable_unit)) *
                    15.3377855173;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "mg" &&
                $("#countable_unit_id").val() === "oz" &&
                $("#empty_weight_id").val() === "mg"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    ((parseInt(diff_weight) / parseInt(countable_unit)) *
                        0.0338140566667) /
                    1000;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "g" &&
                $("#countable_unit_id").val() === "cL" &&
                $("#empty_weight_id").val() === "g"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    parseInt(diff_weight) / parseInt(countable_unit) / 10;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "kg" &&
                $("#countable_unit_id").val() === "cL" &&
                $("#empty_weight_id").val() === "kg"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    (parseInt(diff_weight) / parseInt(countable_unit)) * 100;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "dry oz" &&
                $("#countable_unit_id").val() === "cL" &&
                $("#empty_weight_id").val() === "dry oz"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    (parseInt(diff_weight) / parseInt(countable_unit)) *
                    2.83495;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "lb" &&
                $("#countable_unit_id").val() === "cL" &&
                $("#empty_weight_id").val() === "lb"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    (parseInt(diff_weight) / parseInt(countable_unit)) *
                    45.3592;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "mg" &&
                $("#countable_unit_id").val() === "cL" &&
                $("#empty_weight_id").val() === "mg"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    parseInt(diff_weight) / parseInt(countable_unit) / 10000;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "g" &&
                $("#countable_unit_id").val() === "100-mL" &&
                $("#empty_weight_id").val() === "g"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    parseInt(diff_weight) / parseInt(countable_unit) / 100;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "kg" &&
                $("#countable_unit_id").val() === "100-mL" &&
                $("#empty_weight_id").val() === "kg"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    (parseInt(diff_weight) / parseInt(countable_unit)) * 10;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "dry oz" &&
                $("#countable_unit_id").val() === "100-mL" &&
                $("#empty_weight_id").val() === "dry oz"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    ((parseInt(diff_weight) / parseInt(countable_unit)) *
                        2.83495) /
                    10;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "lb" &&
                $("#countable_unit_id").val() === "100-mL" &&
                $("#empty_weight_id").val() === "lb"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    (parseInt(diff_weight) / parseInt(countable_unit)) *
                    4.53592;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "mg" &&
                $("#countable_unit_id").val() === "100-mL" &&
                $("#empty_weight_id").val() === "mg"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    parseInt(diff_weight) / parseInt(countable_unit) / 100000;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "g" &&
                $("#countable_unit_id").val() === "hL" &&
                $("#empty_weight_id").val() === "g"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    parseInt(diff_weight) / parseInt(countable_unit) / 100000;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "kg" &&
                $("#countable_unit_id").val() === "hL" &&
                $("#empty_weight_id").val() === "kg"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    parseInt(diff_weight) / parseInt(countable_unit) / 100;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "dry oz" &&
                $("#countable_unit_id").val() === "hL" &&
                $("#empty_weight_id").val() === "dry oz"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    ((parseInt(diff_weight) / parseInt(countable_unit)) *
                        2.83495) /
                    10000;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "lb" &&
                $("#countable_unit_id").val() === "hL" &&
                $("#empty_weight_id").val() === "lb"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    ((parseInt(diff_weight) / parseInt(countable_unit)) *
                        45.359237) /
                    10000;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "mg" &&
                $("#countable_unit_id").val() === "hL" &&
                $("#empty_weight_id").val() === "mg"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    parseInt(diff_weight) /
                    parseInt(countable_unit) /
                    100000000;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "g" &&
                $("#countable_unit_id").val() === "30-mL" &&
                $("#empty_weight_id").val() === "g"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    parseInt(diff_weight) / parseInt(countable_unit) / 30;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "kg" &&
                $("#countable_unit_id").val() === "30-mL" &&
                $("#empty_weight_id").val() === "kg"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    (parseInt(diff_weight) / parseInt(countable_unit) / 30) *
                    1000;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "dry oz" &&
                $("#countable_unit_id").val() === "30-mL" &&
                $("#empty_weight_id").val() === "dry oz"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    (((parseInt(diff_weight) / parseInt(countable_unit)) *
                        2.83495) /
                        30) *
                    10;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "lb" &&
                $("#countable_unit_id").val() === "30-mL" &&
                $("#empty_weight_id").val() === "lb"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    (((parseInt(diff_weight) / parseInt(countable_unit)) *
                        45.3592) /
                        30) *
                    10;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "mg" &&
                $("#countable_unit_id").val() === "30-mL" &&
                $("#empty_weight_id").val() === "mg"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    parseInt(diff_weight) /
                    parseInt(countable_unit) /
                    30 /
                    1000;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "g" &&
                $("#countable_unit_id").val() === "25-mL" &&
                $("#empty_weight_id").val() === "g"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    parseInt(diff_weight) / parseInt(countable_unit) / 25;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "kg" &&
                $("#countable_unit_id").val() === "25-mL" &&
                $("#empty_weight_id").val() === "kg"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    (parseInt(diff_weight) / parseInt(countable_unit) / 25) *
                    1000;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "dry oz" &&
                $("#countable_unit_id").val() === "25-mL" &&
                $("#empty_weight_id").val() === "dry oz"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    (((parseInt(diff_weight) / parseInt(countable_unit)) *
                        2.83495) /
                        25) *
                    10;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "lb" &&
                $("#countable_unit_id").val() === "25-mL" &&
                $("#empty_weight_id").val() === "lb"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    (((parseInt(diff_weight) / parseInt(countable_unit)) *
                        45.3592) /
                        25) *
                    10;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "mg" &&
                $("#countable_unit_id").val() === "25-mL" &&
                $("#empty_weight_id").val() === "mg"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    parseInt(diff_weight) /
                    parseInt(countable_unit) /
                    25 /
                    1000;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "g" &&
                $("#countable_unit_id").val() === "45-mL" &&
                $("#empty_weight_id").val() === "g"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    parseInt(diff_weight) / parseInt(countable_unit) / 45;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "kg" &&
                $("#countable_unit_id").val() === "45-mL" &&
                $("#empty_weight_id").val() === "kg"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    (parseInt(diff_weight) / parseInt(countable_unit) / 45) *
                    1000;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "dry oz" &&
                $("#countable_unit_id").val() === "45-mL" &&
                $("#empty_weight_id").val() === "dry oz"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    (((parseInt(diff_weight) / parseInt(countable_unit)) *
                        2.83495) /
                        45) *
                    10;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "lb" &&
                $("#countable_unit_id").val() === "45-mL" &&
                $("#empty_weight_id").val() === "lb"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    (((parseInt(diff_weight) / parseInt(countable_unit)) *
                        45.3592) /
                        45) *
                    10;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            } else if (
                this.value === "mg" &&
                $("#countable_unit_id").val() === "45-mL" &&
                $("#empty_weight_id").val() === "mg"
            ) {
                var diff_weight =
                    parseInt(full_weight) - parseInt(empty_weight);
                var total_weight =
                    parseInt(diff_weight) /
                    parseInt(countable_unit) /
                    45 /
                    1000;
                $("#density").val(total_weight.toFixed(6));
                $("#summary_density").html(
                    $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                );
            }
        });

    });
});