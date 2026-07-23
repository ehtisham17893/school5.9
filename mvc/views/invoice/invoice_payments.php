<style>
/* Class dropdown: show all options without inner scroll (Select2 v3) */
.select2-noscroll-class .select2-results {
    max-height: none !important;
    overflow-y: visible !important;
}
.payment-grid-scroll {
    max-height: calc(100vh - 280px);
    overflow: auto;
    -webkit-overflow-scrolling: touch;
}
#paymentGridTable {
    border-collapse: separate;
    border-spacing: 0;
}
/* Sticky header: fee columns + labels stay visible when scrolling down */
#paymentGridTable thead th {
    position: sticky;
    top: 0;
    z-index: 5;
    background: #f9f9f9 !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    vertical-align: middle;
}
#paymentGridTable thead th.feetype-col {
    z-index: 5;
}
#paymentGridTable th.col-sno { width: 50px; min-width: 50px; max-width: 50px; white-space: nowrap; }
#paymentGridTable th.col-name,
#paymentGridTable td:nth-child(2) { min-width: 180px; width: 180px; max-width: 220px; }
#paymentGridTable th.feetype-col,
#paymentGridTable td.payment-cell,
#paymentGridTable td:nth-child(n+3) { min-width: 150px; }
/* Freeze S.# + Student while scrolling horizontally */
#paymentGridTable .sticky-col-sno {
    position: sticky;
    left: 0;
    z-index: 2;
    background: #fff;
    background-clip: padding-box;
}
#paymentGridTable .sticky-col-name {
    position: sticky;
    left: 50px;
    z-index: 2;
    background: #fff;
    background-clip: padding-box;
    box-shadow: 4px 0 8px -4px rgba(0,0,0,0.18);
}
/* Top-left corner: above fee headers when scrolling both ways */
#paymentGridTable thead th.sticky-col-sno {
    z-index: 12;
    background: #f4f4f4 !important;
}
#paymentGridTable thead th.sticky-col-name {
    z-index: 11;
    background: #f4f4f4 !important;
}
#paymentGridTable tbody tr:nth-child(even) .sticky-col-sno,
#paymentGridTable tbody tr:nth-child(even) .sticky-col-name {
    background: #f9f9f9;
}
#paymentGridTable tbody tr:nth-child(odd) .sticky-col-sno,
#paymentGridTable tbody tr:nth-child(odd) .sticky-col-name {
    background: #fff;
}
#paymentGridTable tbody tr {
    transition: background-color 0.22s ease, box-shadow 0.22s ease, transform 0.18s ease;
}
#paymentGridTable tbody tr:hover {
    background-color: #e8f4fc !important;
    box-shadow: inset 4px 0 0 #3c8dbc, 0 1px 6px rgba(0,0,0,0.06);
    transform: translateY(-1px);
}
#paymentGridTable tbody tr:hover td:not(.sticky-col-sno):not(.sticky-col-name) {
    background-color: transparent !important;
}
#paymentGridTable tbody tr:hover .sticky-col-sno,
#paymentGridTable tbody tr:hover .sticky-col-name {
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
#savePaymentBtn {
    min-width: 110px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    transition: box-shadow 0.2s ease, transform 0.15s ease;
}
#savePaymentBtn:hover:not(:disabled) {
    box-shadow: 0 3px 8px rgba(0,0,0,0.12);
    transform: translateY(-1px);
}
</style>
<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa icon-invoice"></i> <?=$this->lang->line('invoice_payment')?> - <?=$this->lang->line('invoice_bulk_payment')?></h3>
                <ol class="breadcrumb">
                    <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li><a href="<?=base_url("invoice/index")?>"><?=$this->lang->line('menu_invoice')?></a></li>
                    <li class="active"><?=$this->lang->line('invoice_payment')?></li>
                </ol>
            </div>
            <div class="box-body">
                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label><?=$this->lang->line("invoice_classesID")?></label>
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
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label><?=$this->lang->line("invoice_section")?></label>
                            <select name="sectionID" id="sectionID" class="form-control select2">
                                <option value="0"><?=$this->lang->line("invoice_select_section")?></option>
                            </select>
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
                        <table class="table table-bordered table-striped" id="paymentGridTable">
                            <thead id="gridHeader">
                            </thead>
                            <tbody id="gridBody">
                            </tbody>
                        </table>
                    </div>
                    <div class="payment-grid-footer">
                        <button type="button" id="savePaymentBtn" class="btn btn-success">
                            <i class="fa fa-save"></i> <?=$this->lang->line('invoice_save')?>
                        </button>
                    </div>
                </div>

                <div id="noDataMessage" class="alert alert-info" style="display: none;">
                    <?=$this->lang->line('invoice_select_class_section')?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#classesID').select2({ dropdownCssClass: 'select2-noscroll-class' });
    $('#sectionID').select2();
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

        if(classesID === '0') {
            $('#noDataMessage').show().html('<?=$this->lang->line("class_required")?>');
            $('#gridContainer').hide();
            return;
        }

        $('#noDataMessage').hide();
        $('#loadGridBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

        $.ajax({
            type: 'POST',
            url: "<?=base_url('invoice/getInvoicePaymentGrid')?>",
            data: {classesID: classesID, sectionID: sectionID},
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
                    response.feetypes = response.feetypes || [];
                    renderGrid(response);
                    $('#noDataMessage').hide();
                    $('#gridContainer').show();
                } else {
                    $('#noDataMessage').removeClass('alert-danger').addClass('alert-info').show().html('<?=$this->lang->line('no_reference_data')?>');
                    $('#gridContainer').hide();
                }
            },
            error: function(xhr, status, err) {
                $('#loadGridBtn').prop('disabled', false).html('<i class="fa fa-refresh"></i> <?=$this->lang->line('invoice_load_grid')?>');
                var msg = '<?=$this->lang->line('invoice_load_error')?>';
                if (xhr.responseText && xhr.responseText.length < 300 && xhr.responseText.indexOf('<') === -1) {
                    try { var r = JSON.parse(xhr.responseText); if (r.error) msg = r.error; } catch(e) {}
                } else if (xhr.status) {
                    msg += ' (HTTP ' + xhr.status + ')';
                }
                $('#noDataMessage').removeClass('alert-info').addClass('alert-danger').show().html(msg);
                $('#gridContainer').hide();
            }
        });
    });

    function renderGrid(data) {
        var header = '<tr><th class="col-sno sticky-col-sno">S.#</th><th class="col-name sticky-col-name"><?=$this->lang->line('invoice_student')?></th>';
        data.feetypes.forEach(function(ft) {
            header += '<th class="feetype-col" style="min-width: 100px;">' + ft.feetypes + '</th>';
        });
        header += '</tr>';
        $('#gridHeader').html(header);

        var body = '';
        data.grid.forEach(function(row, idx) {
            body += '<tr>';
            body += '<td class="sticky-col-sno">' + (idx + 1) + '</td>';
            body += '<td class="sticky-col-name"><strong>' + row.name + '</strong><br><small class="text-muted">' + row.roll + '</small></td>';

            data.feetypes.forEach(function(ft) {
                var cell = row.cells[ft.feetypeID];
                var amount = cell && cell.amount ? parseFloat(cell.amount) : 0;
                var due = cell && cell.due ? parseFloat(cell.due) : 0;
                var paidTotal = cell && cell.paidTotal != null ? parseFloat(cell.paidTotal) : 0;
                var invoiceID = cell && cell.invoiceID ? cell.invoiceID : '';
                var maininvoiceID = cell && cell.maininvoiceID ? cell.maininvoiceID : '';

                if (cell && cell.invoiceID && due > 0) {
                    var amtLabel = '<?=$this->lang->line('invoice_amount')?>';
                    var dueLabel = '<?=$this->lang->line('invoice_totaldue')?>';
                    var tooltip = amtLabel + ': ' + formatMoney(amount) + ', ' + dueLabel + ': ' + formatMoney(due);
                    body += '<td class="payment-cell">';
                    body += '<input type="text" class="form-control payment-input" data-invoice-id="' + invoiceID + '" data-maininvoice-id="' + maininvoiceID + '" data-amount="' + amount + '" data-due="' + due + '" data-student="' + row.name + '" data-feetype="' + ft.feetypes + '" placeholder="' + formatMoney(0) + '" title="' + tooltip + '">';
                    body += '</td>';
                } else if (cell && cell.invoiceID && due === 0 && paidTotal > 0) {
                    body += '<td class="text-center text-success"><strong>' + formatMoney(paidTotal) + '</strong><br><small><?=$this->lang->line('invoice_fully_paid')?></small></td>';
                } else {
                    body += '<td class="text-center">-</td>';
                }
            });
            body += '</tr>';
        });
        $('#gridBody').html(body);

        $('.payment-input').on('input', function() {
            var raw = $(this).val().replace(/,/g, '').replace(/[^0-9.]/g, '');
            var parts = raw.split('.');
            if (parts.length > 2) raw = parts[0] + '.' + parts.slice(1).join('');
            var due = parseFloat($(this).data('due'));
            var num = parseFloat(raw);
            if (raw !== '' && !isNaN(num) && num > due) {
                $(this).val(formatMoney(due));
            } else {
                $(this).val(raw);
            }
        }).on('blur', function() {
            var raw = $(this).val().replace(/,/g, '').replace(/[^0-9.]/g, '');
            var num = parseFloat(raw);
            var due = parseFloat($(this).data('due'));
            if (!isNaN(num) && num > 0) {
                if (num > due) num = due;
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
    }

    $('#savePaymentBtn').click(function() {
        if (!gridData) return;

        var payments = [];
        $('.payment-input').each(function() {
            var val = parseMoney($(this).val());
            if (!isNaN(val) && val > 0) {
                payments.push({
                    invoiceID: $(this).data('invoice-id'),
                    maininvoiceID: $(this).data('maininvoice-id'),
                    amount: val
                });
            }
        });

        if (payments.length === 0) {
            alert('<?=$this->lang->line('invoice_enter_payment_amount')?>');
            return;
        }

        $('#savePaymentBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> <?=$this->lang->line('invoice_saving')?>');

        $.ajax({
            type: 'POST',
            url: "<?=base_url('invoice/saveBulkPayment')?>",
            data: {
                payment_method: 'Cash',
                payments: JSON.stringify(payments)
            },
            dataType: "json",
            success: function(response) {
                $('#savePaymentBtn').prop('disabled', false).html('<i class="fa fa-save"></i> <?=$this->lang->line('invoice_save')?>');
                if (response.status) {
                    alert(response.message);
                    $('#loadGridBtn').click();
                } else {
                    alert(response.message || '<?=$this->lang->line('generate_error')?>');
                }
            },
            error: function() {
                $('#savePaymentBtn').prop('disabled', false).html('<i class="fa fa-save"></i> <?=$this->lang->line('invoice_save')?>');
                alert('<?=$this->lang->line('generate_error')?>');
            }
        });
    });
});
</script>
