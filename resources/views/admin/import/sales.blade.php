@push('styles')
<style type="text/css">
    .collapse {
        display: none;
    }
    .collapse.in{
        display: block;
    }
</style>
@endpush

@extends('layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5><i class="fas fa-upload"></i> Sales Upload</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Sales Upload</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- /.row -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Upload File</h3>
                            <div class="card-tools">
                                <div class="float-right">
                                    
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->

                        <!-- /.card-body -->
                        <div class="card-body table-responsive">
                            <div class="container">
                                @csrf
                                <div class="row">
                                    <div class="col-6">
                                    <div class="col-12 form-group">
                                        <label for="">Station:</label>
                                        
                                        <select name="station_id" id="" class="select2" onchange="getPriceLevel(this.value)" style="width:100%;">
                                            @foreach($stations as $station)
                                            <option value="{{$station->id}}">{{$station->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 form-group">
                                        <label for="">Price Level</label>
                                        <select name="price_level_id" id="price_level_id" class="select2" style="width:100%">
                                        @foreach($price_levels as $price_level)
                                        <option value="{{$price_level->id}}">{{$price_level->level}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 form-group">
                                        <label for="">Please specify file to upload</label>
                                        <input type="file" name="sales_file" id="" class="form-control" required>
                                    </div>
                                    <div class="col-12 form-group">
                                    <button type="button" class="btn btn-sm btn-primary step1-upload">Upload</button>
                                    </div>
                                    
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- collapse contain -->
                    <!-- collapse contain end -->
                    <!-- /.card -->
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
@endsection
@php
    $fields = [
        'not_used' => 'Not Used',
        'name' => 'Name',
        'plu' => 'PLU',
        'unit_price' => 'Unit Price',
        'quantity' => 'Quantity',
        'total_revenue' => 'Total Revenue',
        'timestamp' => 'Timestamp'
    ]
@endphp
@include('templates.step-2-import-sales-via-file')
@include('templates.step-3-import-sales-via-file')
@include('templates.step-4-import-sales-via-file')
@include('templates.step-5-import-sales-via-file')
@push('styles')
<style type="text/css">
    .table_final_preview tbody tr.DNIrow td {
        background-color: #cbcbcb
    }
</style>
@endpush
@push('script')
<script type="text/javascript">
    var filedata;
    var maxArrayLength;
    var fields = {
        'not_used' : 'Not Used',
        'name' : 'Name',
        'plu' : 'PLU',
        'unit_price' : 'Unit Price',
        'quantity' : 'Quantity',
        'total_revenue' : 'Total Revenue',
        'timestamp' : 'Timestamp'
    }
    //for refresh preview
    var step2Html
    var delimiter
    var qualifier
    var sale_file
    var sale_file_name
    var station_id
    var price_level_id
    $('.select2').each(function(i,ele){
         $(ele).select2();
    });
    function getPriceLevel(station_id){
        $.ajax({
            url: `/ajax/${station_id}/get/price_level`,
            method: "get",
            
            success:function(result){
                option='';
                
                if(result.price_levels.length){
                    $(result.price_levels).each(function(index,value){
                        option+=`<option value="${value.id}">${value.level}</option>`;
                        
                    })
                    $("#price_level_id").html(option);
                }
            }
        })
    }
    $('.step1-upload').on('click', function(){
        price_level_id = $('[name="price_level_id"]').find('option:selected').val()
        if(price_level_id == undefined){
            alert("Please Choose Price Level")
            return
        }

        let validation_result = validation_file($('[name="sales_file"]').val())
        if(validation_result == false){
            alert("ERROR: INVALID FILE TYPE (csv or txt only)")
            return
        }

        step2Html = $.parseHTML($("#step-2").html());
        delimiter = $(step2Html).find("#delimiter").val()
        qualifier = $(step2Html).find("#qualifier").val()
        station_id = $('[name="station_id"]').val()
        price_level_id = $('[name="price_level_id"]').val()
        sale_file = $('[name="sales_file"]')[0].files
        sale_file_name =  $('[name="sales_file"]').val().split('\\').pop();
        ajax_parse_data()
    })
    $(document).on('change', '#delimiter', function(){
        $("#parseData").show()
        delimiter = $(this).val()
    })
    $(document).on('change', '#qualifier', function(){
        $("#parseData").show()
        qualifier = $(this).val()
    })
    $(document).on('click','#parseData', function(){
        ajax_parse_data()
    })
    $(document).on('click','#nextToDefineColumns1', function(){
        $('.spinner-overlay').show()
        let selectHtml = "<select class='form-control' style='width:110px'>"
        let step3Html = $.parseHTML($("#step-3").html());
        let theadHtml = "<tr>"
        $.each(fields, function(key, value){
            selectHtml += `<option value="${key}">${value}</option>`
        })
        selectHtml += "</select>"
        for( var i = 0; i < maxArrayLength; i++){
            theadHtml += `<th class="data_col_${i}">${selectHtml}</th>`
        }
        theadHtml += "</tr>"
        $(step3Html).find(".table_column_format_preview thead").html(theadHtml)

        $.each(filedata, function(index, arrValue){
            let tbodyHtml = "<tr>"
            for( var i = 0; i < maxArrayLength; i++){
                let data = arrValue[i] ? arrValue[i] : ''
                tbodyHtml += `<td class="data_col_${i}">${data}</td>`
            }
            tbodyHtml += "</tr>"
            $(step3Html).find(".table_column_format_preview tbody").append(tbodyHtml)
        })
        $('.container').html(step3Html)
        $('.container').find(".table_column_format_preview thead th:first-child option[value=name]").attr('selected','selected')
        $('.container').find(".table_column_format_preview thead th:nth-child(2) option[value=plu]").attr('selected','selected')
        $('.container').find(".table_column_format_preview thead th:nth-child(3) option[value=total_revenue]").attr('selected','selected')
        $('.container').find(".table_column_format_preview thead th:nth-child(4) option[value=quantity]").attr('selected','selected')
        $('.spinner-overlay').hide()
        $('.main-sidebar').css('height',Number( $('.content').height() + 150 ) + 'px')
    })
    $(document).on('click',"#nextToFinalPreview1", function(){
        $('.spinner-overlay').show()
        let final_data = [];
        let validation_result = validation_for_final_preview()
        if(validation_result == false){
            $('.spinner-overlay').hide()
            return
        }
        $('.table_column_format_preview > tbody  > tr').each(function(index, tr) {
            let data = {}
            let validationNullRow = false
            data.blacklist = false
            $(this).find('td').each(function(i, td_row){
                let class_name = $(this).attr("class")
                let field_name = $(`.table_column_format_preview thead th.${class_name} select option:selected`).val()
                if(field_name != 'not_used'){
                    if(field_name == 'unit_price' || field_name == 'quantity' || field_name == 'total_revenue'){
                        if(isNumber($(this).html())){
                            if(field_name == 'quantity' && $(this).html() == ''){
                                data.blacklist = true
                            }

                            if($(this).html() != '' && !isNaN($(this).html())){
                                data[field_name] = Number($(this).html()).toFixed(2) 
                            }else{
                                data[field_name] = ''
                            }

                        }else{
                            data.blacklist = true
                        }
                    }else{
                        data[field_name] = $(this).html()
                    }
                    if($(this).html() != ''){
                        validationNullRow = true
                    }
                }
            })
            if(validationNullRow){
                data = calcUnitPriceAndTotalRevenue(data)
                final_data.push(data)
            }
        })
        let step4Html = $.parseHTML($("#step-4").html());
        $.each(final_data, function(index, arrValue){
            let tbodyHtml;
            if(arrValue.blacklist == true){
                tbodyHtml += "<tr class='DNIrow'>"
                tbodyHtml += `<td class="do_not_import" style="text-align:center"><input type="checkbox" checked/></td>`
                tbodyHtml += `<td class="plu">${ arrValue.plu != undefined ? arrValue.plu : '' }</td>`
                tbodyHtml += `<td class="name">${ arrValue.name != undefined ? arrValue.name : '' }</td>`
                tbodyHtml += `<td class="quantity">${ (arrValue.quantity != undefined) && !isNaN(arrValue.quantity) ? arrValue.quantity : ''}</td>`
                tbodyHtml += `<td class="unit_price">${ (arrValue.unit_price != undefined) && !isNaN(arrValue.unit_price) ? arrValue.unit_price : '' }</td>`
                tbodyHtml += `<td class="total_revenue">${ (arrValue.total_revenue != undefined) && !isNaN(arrValue.total_revenue) ? arrValue.total_revenue : '' }</td>`
                tbodyHtml += "</tr>"
            }else{
                tbodyHtml += "<tr>"
                tbodyHtml += `<td class="import" style="text-align:center"><input type="checkbox"/></td>`
                tbodyHtml += `<td class="plu">${ arrValue.plu != undefined ? arrValue.plu : '' }</td>`
                tbodyHtml += `<td class="name">${ arrValue.name != undefined ? arrValue.name : '' }</td>`
                tbodyHtml += `<td class="quantity">${ (arrValue.quantity != undefined) && !isNaN(arrValue.quantity) ? arrValue.quantity : ''}</td>`
                tbodyHtml += `<td class="unit_price">${ (arrValue.unit_price != undefined) && !isNaN(arrValue.unit_price) ? arrValue.unit_price : '' }</td>`
                tbodyHtml += `<td class="total_revenue">${ (arrValue.total_revenue != undefined) && !isNaN(arrValue.total_revenue) ? arrValue.total_revenue : '' }</td>`
                tbodyHtml += "</tr>"
            }
            $(step4Html).find(".table_final_preview tbody").append(tbodyHtml)
            $('.container').html(step4Html)
            $('.spinner-overlay').hide()
            $('.main-sidebar').css('height',Number( $('.content').height() + 150 ) + 'px')
        })
    })
    $(document).on('change','.table_final_preview input[type="checkbox"]', function(){
        if($(this).is(":checked")) {
            $(this).parents('tr').addClass('DNIrow')
            $(this).parents('td').addClass('do_not_import')
            $(this).parents('td').removeClass('import')
        }else{
            $(this).parents('tr').removeClass('DNIrow')
            $(this).parents('td').removeClass('do_not_import')
            $(this).parents('td').addClass('import')
        }
    })
    $(document).on('click',"#finalImportData1", function(){
        $('.spinner-overlay').show()
        let recipe_data = []
        $(".table_final_preview tbody tr:not('.DNIrow')").each(function(key, row){
                let row_obj = {}
                row_obj.name = $(row).find('.name').html()
                row_obj.plu = $(row).find('.plu').html()
                row_obj.station_id = station_id
                row_obj.price_level_id = price_level_id
                row_obj.price = $(row).find('.unit_price').html()
                row_obj.qty = $(row).find('.quantity').html()
                row_obj.revenue = $(row).find('.total_revenue').html()
                recipe_data.push(row_obj)
        })
        
        $.ajax({
            url : "{{ route('recipe.importData') }}",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            method : "post",
            contentType: "application/json",
            dataType: "json",
            data : JSON.stringify({
                "recipe_data" : recipe_data
            }),
            success: function(response){
                if(response.status == true){
                    let step5 = $("#step-5").html()
                    step5 = step5.replace('${sale_file_name}', sale_file_name)
                    let step5Html = $.parseHTML(step5)
                    
                    $.each(response.data, function(index, val){
                        let tbodyHtml = "<tr>"
                        tbodyHtml += `<td>${val.plu ? val.plu : ''}</td>`
                        tbodyHtml += `<td>${val.name ? val.name : ''}</td>`
                        tbodyHtml += `<td>${val.qty ? val.qty : 0}</td>`
                        tbodyHtml += `<td>${val.revenue ? val.revenue : 0}</td>`
                        tbodyHtml += `<td>${val.price ? val.price : 0}</td>`
                        tbodyHtml += "</tr>"
                        $(step5Html).find(".tbl_sale_log tbody").append(tbodyHtml)
                    })
                    $('.container').html(step5Html)
                    $('.spinner-overlay').hide()
                    $('.main-sidebar').css('height',Number( $('.content').height() + 150 ) + 'px')
                } 
            },
            error:function(){
                $('.spinner-overlay').hide()
            }
        })
    })
    function validation_file($this){
        var fileExtension = ['csv', 'txt'];
        if ($.inArray($this.split('.').pop().toLowerCase(), fileExtension) == -1) {
            return false
        }
        return true
    }
    function validation_for_final_preview(){
        let header_title = []
        $('.table_column_format_preview > thead  > tr').each(function(index, tr) {
            $(this).find('th').each(function(i, td_row){
                header_title.push($(this).find('select option:selected').val())
            })
        })
        if((header_title.indexOf('name') == -1) && (header_title.indexOf('plu') == -1)){
            alert("Please specify the PLU or the NAME column")
            return false
        }
        if((header_title.indexOf('quantity') == -1)){
            alert("Please specify the QUANTITY column")
            return false
        }
        return true
    }
    function isNumber(value){
        if(typeof value === "string"){
            return !isNaN(value)
        } 
    }
    function calcUnitPriceAndTotalRevenue(data){
        if((data.unit_price == undefined) && data.total_revenue != undefined){
            data.unit_price = Number(Number(data.total_revenue) / Number(data.quantity)).toFixed(2)
        }
        if((data.unit_price != undefined) && data.total_revenue == undefined){
            data.total_revenue = (data.unit_price) * Number(data.quantity).toFixed(2)
        }
        return data
    }
    function ajax_parse_data(){
        var formData = new FormData();
        formData.append('_token', "{{ csrf_token() }}")
        if(sale_file.length > 0){
           formData.append("sale_file", sale_file[0])
        }else{
            alert("File Error")
        }
    
        formData.append("station_id", station_id)
        formData.append("price_level_id", price_level_id)
        formData.append("delimiter", delimiter)
        formData.append("qualifier", qualifier)

        $('.spinner-overlay').show()
        $.ajax({
            url : "{{ route('recipe.parseData') }}",
            type : 'POST',
            data : formData,
            processData: false,
            contentType: false,
            success : function(response) {
                filedata = response;
                filedata = filedata.map( arr => {
                    arr = arr.map( val => {
                        if(qualifier == 'single_quote'){
                            val = val.replace(/\'/g, "")
                        }else if(qualifier == 'double_quote'){
                            val = val.replace(/\"/g, "")
                        }
                        return val
                    })
                    return arr
                })
                $(step2Html).find("#table_wrapper tbody tr").remove()
                $.each(response, function(index,val){
                    if(index == 0){
                        maxArrayLength = val.length
                    }else{
                        maxArrayLength = (maxArrayLength < val.length) ? val.length : maxArrayLength
                    }
                })
                $.each(filedata, function(index, arrValue){
                    if(index == 0){
                        let theadHtml = "<tr>"
                        for( var i = 1; i <= maxArrayLength; i++){
                            theadHtml += `<th>column${i}</th>`
                        }
                        theadHtml += "</tr>"
                        $(step2Html).find("#table_wrapper thead").html(theadHtml)
                    }
                    let tbodyHtml = "<tr>"
                    for( var i = 0; i < maxArrayLength; i++){
                        let data = arrValue[i] ? arrValue[i] : ''
                        tbodyHtml += `<td>${data}</td>`
                    }
                    tbodyHtml += "</tr>"
                    $(step2Html).find("#table_wrapper tbody").append(tbodyHtml)
                })
                $('.container').html(step2Html)
                $('.spinner-overlay').hide()
                $('.main-sidebar').css('height',Number( $('.content').height() + 150 ) + 'px')
            },
            error: function(){
                $('.spinner-overlay').hide()
            }
        });
    }
    $(document).ready(function(){
        getPriceLevel($('[name="station_id"]').find('option:selected').val())
    })
</script>
@endpush
