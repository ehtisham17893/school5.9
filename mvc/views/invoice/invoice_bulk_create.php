<style>
.select2-noscroll-class .select2-results {
    max-height: none !important;
    overflow-y: visible !important;
}
.payment-grid-scroll {
    max-height: calc(100vh - 280px);
    overflow: auto;
    -webkit-overflow-scrolling: touch;
}
#bulkCreateGridTable {
    border-collapse: separate;
    border-spacing: 0;
}
#bulkCreateGridTable thead th {
    position: sticky;
    top: 0;
    z-index: 5;
    background: #f9f9f9 !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    vertical-align: middle;
}
#bulkCreateGridTable th.col-sno { width: 50px; min-width: 50px; max-width: 50px; white-space: nowrap; }
#bulkCreateGridTable th.col-name,
#bulkCreateGridTable td:nth-child(2) { min-width: 180px; width: 180px; max-width: 220px; }
#bulkCreateGridTable th.col-action { width: 70px; min-width: 70px; max-width: 70px; text-align: center; }
#bulkCreateGridTable td.col-action { text-align: center; vertical-align: middle; }
#bulkCreateGridTable th.feetype-col,
#bulkCreateGridTable td.amount-cell { min-width: 150px; }
#bulkCreateGridTable .remove-student-btn {
    padding: 4px 8px;
}
#bulkCreateGridTable .sticky-col-sno {
    position: sticky;
    left: 0;
    z-index: 2;
    background: #fff;
    background-clip: padding-box;
}
#bulkCreateGridTable .sticky-col-name {
    position: sticky;
    left: 50px;
    z-index: 2;
    background: #fff;
    background-clip: padding-box;
    box-shadow: 4px 0 8px -4px rgba(0,0,0,0.18);
}
#bulkCreateGridTable thead th.sticky-col-sno {
    z-index: 12;
    background: #f4f4f4 !important;
}
#bulkCreateGridTable thead th.sticky-col-name {
    z-index: 11;
    background: #f4f4f4 !important;
}
#bulkCreateGridTable tbody tr:nth-child(even) .sticky-col-sno,
#bulkCreateGridTable tbody tr:nth-child(even) .sticky-col-name {
    background: #f9f9f9;
}
#bulkCreateGridTable tbody tr:nth-child(odd) .sticky-col-sno,
#bulkCreateGridTable tbody tr:nth-child(odd) .sticky-col-name {
    background: #fff;
}
#bulkCreateGridTable tbody tr {
    transition: background-color 0.22s ease, box-shadow 0.22s ease, transform 0.18s ease;
}
#bulkCreateGridTable tbody tr:hover {
    background-color: #e8f4fc !important;
    box-shadow: inset 4px 0 0 #3c8dbc, 0 1px 6px rgba(0,0,0,0.06);
    transform: translateY(-1px);
}
#bulkCreateGridTable tbody tr:hover td:not(.sticky-col-sno):not(.sticky-col-name) {
    background-color: transparent !important;
}
#bulkCreateGridTable tbody tr:hover .sticky-col-sno,
#bulkCreateGridTable tbody tr:hover .sticky-col-name {
    background-color: #e8f4fc !important;
}
.payment-grid-footer {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    margin-top: 16px;
    padding-top: 8px;
    border-top: 1px solid #f0f0f0;
}
#saveBulkCreateBtn {
    min-width: 110px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    transition: box-shadow 0.2s ease, transform 0.15s ease;
}
#saveBulkCreateBtn:hover:not(:disabled) {
    box-shadow: 0 3px 8px rgba(0,0,0,0.12);
    transform: translateY(-1px);
}
.feetype-fill-row {
    margin-bottom: 8px;
}
.feetype-fill-row .btn {
    margin-left: 6px;
}
</style>
<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa icon-invoice"></i> <?=$this->lang->line('invoice_bulk_create')?></h3>
                <ol class="breadcrumb">
                    <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li><a href="<?=base_url("invoice/index")?>"><?=$this->lang->line('menu_invoice')?></a></li>
                    <li class="active"><?=$this->lang->line('invoice_bulk_create')?></li>
                </ol>
            </div>
            <div class="box-body">
                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label><?=$this->lang->line("invoice_classesID")?> <span class="text-red">*</span></label>
                            <?php
                                $classesArray = ['0' => $this->lang->line("invoice_select_classes")];
                                if(customCompute($classes)) {
                                    foreach ($classes as $c) {
                                        $classesArray[$c->classesID] = $c->classes;
                                    }
                                }
                                echo form_dropdown("classesID", $classesArray, '', "id='classesID' class='form-control select2'");
                            ?>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label><?=$this->lang->line("invoice_section")?></label>
                            <select name="sectionID" id="sectionID" class="form-control select2">
                                <option value="0"><?=$this->lang->line("invoice_select_section")?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label><?=$this->lang->line("invoice_feetype")?> <span class="text-red">*</span></label>
                            <?php
                                $feetypeArray = [];
                                if(customCompute($feetypes)) {
                                    foreach ($feetypes as $ft) {
                                        $feetypeArray[$ft->feetypesID] = $ft->feetypes;
                                    }
                                }
                                echo form_dropdown("feetypeIDs[]", $feetypeArray, '', "id='feetypeIDs' class='form-control select2' multiple='multiple'");
                            ?>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label><?=$this->lang->line("invoice_date")?> <span class="text-red">*</span></label>
                            <input type="text" class="form-control" id="invoiceDate" name="date" value="<?=date('d-m-Y')?>">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="button" id="loadGridBtn" class="btn btn-primary btn-block">
                                <i class="fa fa-refresh"></i> <?=$this->lang->line('invoice_load_grid')?>
                            </button>
                        </div>
                    </div>
                </div>

                <div id="gridContainer" style="display: none;">
                    <div class="table-responsive payment-grid-scroll">
                        <table class="table table-bordered table-striped" id="bulkCreateGridTable">
                            <thead id="gridHeader">
                            </thead>
                            <tbody id="gridBody">
                            </tbody>
                        </table>
                    </div>
                    <div class="payment-grid-footer">
                        <button type="button" id="saveBulkCreateBtn" class="btn btn-success">
                            <i class="fa fa-save"></i> <?=$this->lang->line('invoice_save')?>
                        </button>
                    </div>
                </div>

                <div id="noDataMessage" class="alert alert-info" style="display: none;">
                    <?=$this->lang->line('invoice_bulk_create_hint')?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#classesID').select2({ dropdownCssClass: 'select2-noscroll-class' });
    $('#sectionID').select2();
    $('#feetypeIDs').select2({ placeholder: '<?=$this->lang->line("invoice_select_feetype")?>' });
    $('#invoiceDate').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        startDate: '<?=$schoolyearsessionobj->startingdate?>',
        endDate: '<?=$schoolyearsessionobj->endingdate?>'
    });

    var gridData = null;

    function formatMoney(num) {
        var n = parseFloat(num);
        if (isNaN(n)) return '0.00';
        return n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function parseMoney(str) {
        if (str == null || str === '') return NaN;
        return parseFloat(String(str).replace(/,/g, '').replace(/[^\d.-]/g, '').trim());
    }

    $('#classesID').change(function() {
        var classesID = $(this).val();
        if(classesID === '0') {
            if ($('#sectionID').data('select2')) {
                $('#sectionID').select2('destroy');
            }
            $('#sectionID').html('<option value="0"><?=$this->lang->line("invoice_select_section")?></option>');
            $('#sectionID').select2();
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('invoice/getInvoicePaymentSections')?>",
                data: {'classesID': classesID},
                dataType: "html",
                success: function(data) {
                    if ($('#sectionID').data('select2')) {
                        $('#sectionID').select2('destroy');
                    }
                    $('#sectionID').html(data);
                    $('#sectionID').select2();
                }
            });
        }
        $('#gridContainer').hide();
    });

    $('#loadGridBtn').click(function() {
        var classesID = $('#classesID').val();
        var sectionID = $('#sectionID').val();
        var feetypeIDs = $('#feetypeIDs').val() || [];
        var date = $('#invoiceDate').val();

        if(classesID === '0') {
            $('#noDataMessage').removeClass('alert-danger').addClass('alert-info').show().html('<?=$this->lang->line("class_required")?>');
            $('#gridContainer').hide();
            return;
        }
        if(!feetypeIDs.length) {
            $('#noDataMessage').removeClass('alert-danger').addClass('alert-info').show().html('<?=$this->lang->line("invoice_feetype_required")?>');
            $('#gridContainer').hide();
            return;
        }
        if(!date) {
            $('#noDataMessage').removeClass('alert-danger').addClass('alert-info').show().html('<?=$this->lang->line("invoice_date_required")?>');
            $('#gridContainer').hide();
            return;
        }

        $('#noDataMessage').hide();
        $('#loadGridBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

        $.ajax({
            type: 'POST',
            url: "<?=base_url('invoice/getBulkCreateGrid')?>",
            data: {
                classesID: classesID,
                sectionID: sectionID,
                feetypeIDs: JSON.stringify(feetypeIDs)
            },
            dataType: "json",
            success: function(response) {
                $('#loadGridBtn').prop('disabled', false).html('<i class="fa fa-refresh"></i> <?=$this->lang->line('invoice_load_grid')?>');

                if (response.error) {
                    $('#noDataMessage').removeClass('alert-info').addClass('alert-danger').show().html(response.error);
                    $('#gridContainer').hide();
                    return;
                }
                if (response.status && response.grid && response.grid.length > 0) {
                    gridData = response;
                    renderGrid(response);
                    $('#noDataMessage').hide();
                    $('#gridContainer').show();
                } else {
                    $('#noDataMessage').removeClass('alert-danger').addClass('alert-info').show().html('<?=$this->lang->line('invoice_no_students')?>');
                    $('#gridContainer').hide();
                }
            },
            error: function(xhr) {
                $('#loadGridBtn').prop('disabled', false).html('<i class="fa fa-refresh"></i> <?=$this->lang->line('invoice_load_grid')?>');
                var msg = '<?=$this->lang->line('invoice_load_error')?>';
                if (xhr.status) {
                    msg += ' (HTTP ' + xhr.status + ')';
                }
                $('#noDataMessage').removeClass('alert-info').addClass('alert-danger').show().html(msg);
                $('#gridContainer').hide();
            }
        });
    });

    function renumberRows() {
        $('#gridBody tr').each(function(idx) {
            $(this).find('td.sticky-col-sno').text(idx + 1);
        });
    }

    function renderGrid(data) {
        var header = '<tr><th class="col-sno sticky-col-sno">S.#</th><th class="col-name sticky-col-name"><?=$this->lang->line('invoice_student')?></th>';
        data.feetypes.forEach(function(ft) {
            header += '<th class="feetype-col">' + ft.feetypes;
            header += '<div class="feetype-fill-row">';
            header += '<input type="text" class="form-control input-sm fill-all-input" data-feetype-id="' + ft.feetypeID + '" placeholder="<?=$this->lang->line('invoice_amount')?>" style="width:90px;display:inline-block;">';
            header += '<button type="button" class="btn btn-xs btn-default fill-all-btn" data-feetype-id="' + ft.feetypeID + '" title="<?=$this->lang->line('invoice_fill_all')?>"><i class="fa fa-arrow-down"></i></button>';
            header += '</div></th>';
        });
        header += '<th class="col-action"><?=$this->lang->line('action')?></th>';
        header += '</tr>';
        $('#gridHeader').html(header);

        var body = '';
        data.grid.forEach(function(row, idx) {
            body += '<tr data-student-id="' + row.studentID + '">';
            body += '<td class="sticky-col-sno">' + (idx + 1) + '</td>';
            body += '<td class="sticky-col-name"><strong>' + row.name + '</strong><br><small class="text-muted">' + row.roll;
            if (row.section) {
                body += ' / ' + row.section;
            }
            body += '</small></td>';

            data.feetypes.forEach(function(ft) {
                var cell = row.cells[ft.feetypeID] || {};
                if (cell.exists) {
                    body += '<td class="text-center text-muted"><strong>' + formatMoney(cell.amount) + '</strong><br><small><?=$this->lang->line('invoice_already_exists')?></small></td>';
                } else {
                    body += '<td class="amount-cell">';
                    body += '<input type="text" class="form-control amount-input" data-student-id="' + row.studentID + '" data-feetype-id="' + ft.feetypeID + '" data-student="' + row.name + '" data-feetype="' + ft.feetypes + '" placeholder="' + formatMoney(0) + '">';
                    body += '</td>';
                }
            });
            body += '<td class="col-action">';
            body += '<button type="button" class="btn btn-danger btn-xs remove-student-btn" title="<?=$this->lang->line('invoice_remove_student')?>"><i class="fa fa-trash"></i></button>';
            body += '</td>';
            body += '</tr>';
        });
        $('#gridBody').html(body);

        $('.amount-input').on('input', function() {
            var raw = $(this).val().replace(/,/g, '').replace(/[^0-9.]/g, '');
            var parts = raw.split('.');
            if (parts.length > 2) raw = parts[0] + '.' + parts.slice(1).join('');
            $(this).val(raw);
        }).on('blur', function() {
            var num = parseMoney($(this).val());
            if (!isNaN(num) && num > 0) {
                $(this).val(formatMoney(num));
            } else {
                $(this).val('');
            }
        }).on('focus', function() {
            var num = parseMoney($(this).val());
            if (!isNaN(num) && num > 0) {
                $(this).val(String(num));
            } else {
                $(this).val('');
            }
        });

        $('.fill-all-btn').off('click').on('click', function() {
            var ftID = $(this).data('feetype-id');
            var val = parseMoney($('.fill-all-input[data-feetype-id="' + ftID + '"]').val());
            if (isNaN(val) || val <= 0) {
                alert('<?=$this->lang->line('invoice_enter_create_amount')?>');
                return;
            }
            $('.amount-input[data-feetype-id="' + ftID + '"]').each(function() {
                $(this).val(formatMoney(val));
            });
        });

        $('.remove-student-btn').off('click').on('click', function() {
            var $row = $(this).closest('tr');
            var studentName = $row.find('.sticky-col-name strong').text();
            if (!confirm('<?=$this->lang->line('invoice_remove_student_confirm')?>'.replace('%s', studentName))) {
                return;
            }
            var studentID = parseInt($row.data('student-id'), 10);
            $row.remove();
            if (gridData && gridData.grid) {
                gridData.grid = gridData.grid.filter(function(r) {
                    return parseInt(r.studentID, 10) !== studentID;
                });
            }
            renumberRows();
            if ($('#gridBody tr').length === 0) {
                $('#gridContainer').hide();
                $('#noDataMessage').removeClass('alert-danger').addClass('alert-info').show().html('<?=$this->lang->line('invoice_no_students')?>');
            }
        });
    }

    $('#saveBulkCreateBtn').click(function() {
        if (!gridData) return;

        var classesID = $('#classesID').val();
        var date = $('#invoiceDate').val();
        if (!date) {
            alert('<?=$this->lang->line('invoice_date_required')?>');
            return;
        }

        var items = [];
        $('.amount-input').each(function() {
            var val = parseMoney($(this).val());
            if (!isNaN(val) && val > 0) {
                items.push({
                    studentID: $(this).data('student-id'),
                    feetypeID: $(this).data('feetype-id'),
                    amount: val
                });
            }
        });

        if (items.length === 0) {
            alert('<?=$this->lang->line('invoice_enter_create_amount')?>');
            return;
        }

        $('#saveBulkCreateBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> <?=$this->lang->line('invoice_saving')?>');

        $.ajax({
            type: 'POST',
            url: "<?=base_url('invoice/saveBulkCreate')?>",
            data: {
                classesID: classesID,
                date: date,
                items: JSON.stringify(items)
            },
            dataType: "json",
            success: function(response) {
                $('#saveBulkCreateBtn').prop('disabled', false).html('<i class="fa fa-save"></i> <?=$this->lang->line('invoice_save')?>');
                if (response.status) {
                    alert(response.message);
                    $('#loadGridBtn').click();
                } else {
                    alert(response.message || '<?=$this->lang->line('generate_error')?>');
                }
            },
            error: function() {
                $('#saveBulkCreateBtn').prop('disabled', false).html('<i class="fa fa-save"></i> <?=$this->lang->line('invoice_save')?>');
                alert('<?=$this->lang->line('generate_error')?>');
            }
        });
    });
});
</script>
