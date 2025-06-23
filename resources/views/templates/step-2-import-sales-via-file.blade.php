<template id="step-2">
    <div class="form-group row">
        <label for="delimiter">File Delimiter:</label>
        <div class="col-md-4">
            <select class="form-control" id="delimiter">
                <option value="comma">Comma(,)</option>
                <option value="pipe">Pipe(|)</option>
                <option value="semicolon">Semicolon(;)</option>
                <option value="single_space">Single Space</option>
                <option value="tab">Tab</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="delimiter">File Qualifier:</label>
        <div class="col-md-4">
            <select class="form-control" id="qualifier">
                <option value="none">None()</option>
                <option value="single_quote">Single Quote(')</option>
                <option value="double_quote">Double Quote("")</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <button class="btn btn-info btn-sm" id="parseData" style="display:none">Refresh Preview</button>
    </div>
    <div class="form-group row">
        <button class="btn btn-success btn-sm" id="nextToDefineColumns1">Next : Define Data Columns</button>
    </div>
    <div class="from-group row">
        <h5>Sample Preview</h5>
        <div id="table_wrapper" class="col-md-12">
            <table class="table_preview table table-bordered" style="font-size:15px">
                <thead>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</template>