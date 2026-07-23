<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa icon-invoice"></i> <?=$this->lang->line('generate_invoices_title')?></h3>
                <ol class="breadcrumb">
                    <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li><a href="<?=base_url("invoice/index")?>"><?=$this->lang->line('menu_invoice')?></a></li>
                    <li class="active"><?=$this->lang->line('generate_invoices')?></li>
                </ol>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3">
                        <form role="form" method="post" id="generateInvoiceForm">
                            
                            <div class="form-group">
                                <label for="classesID">
                                    <?=$this->lang->line("invoice_classesID")?> <span class="text-red">*</span>
                                </label>
                                <?php
                                    $classesArray = array('0' => $this->lang->line("invoice_select_classes"));
                                    if(customCompute($classes)) {
                                        foreach ($classes as $classa) {
                                            $classesArray[$classa->classesID] = $classa->classes;
                                        }
                                    }
                                    echo form_dropdown("classesID", $classesArray, set_value("classesID"), "id='classesID' class='form-control select2'");
                                ?>
                            </div>

                            <div class="form-group">
                                <label for="referenceFeetypeID">
                                    <?=$this->lang->line("reference_feetype")?> <span class="text-red">*</span>
                                </label>
                                <select name="referenceFeetypeID" id="referenceFeetypeID" class="form-control select2">
                                    <option value="0"><?=$this->lang->line("select_reference_feetype")?></option>
                                </select>
                                <small class="text-muted">Shows fee types that exist in invoices for the selected class</small>
                            </div>

                            <div class="form-group">
                                <label for="targetFeetypeID">
                                    <?=$this->lang->line("target_feetype")?> <span class="text-red">*</span>
                                </label>
                                <?php
                                    $feetypeArray = array('0' => $this->lang->line("select_target_feetype"));
                                    if(customCompute($feetypes)) {
                                        foreach ($feetypes as $feetype) {
                                            $feetypeArray[$feetype->feetypesID] = $feetype->feetypes;
                                        }
                                    }
                                    echo form_dropdown("targetFeetypeID", $feetypeArray, set_value("targetFeetypeID"), "id='targetFeetypeID' class='form-control select2'");
                                ?>
                            </div>

                            <div class="form-group">
                                <label for="dueDate">
                                    <?=$this->lang->line("due_date")?> <span class="text-red">*</span>
                                </label>
                                <input type="text" class="form-control" id="dueDate" name="dueDate" value="<?=set_value('dueDate')?>" placeholder="DD-MM-YYYY">
                            </div>

                            <div class="form-group">
                                <button type="button" id="generateBtn" class="btn btn-success btn-block">
                                    <i class="fa fa-cogs"></i> <?=$this->lang->line("generate")?>
                                </button>
                            </div>

                        </form>

                        <div id="resultMessage" class="alert" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();
        
        $('#dueDate').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy'
        });

        $('#classesID').change(function() {
            var classesID = $(this).val();
            
            if(classesID === '0') {
                $('#referenceFeetypeID').html('<option value="0"><?=$this->lang->line("select_reference_feetype")?></option>');
            } else {
                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('invoice/getReferenceFeetypes')?>",
                    data: {'classesID': classesID},
                    dataType: "html",
                    success: function(data) {
                        $('#referenceFeetypeID').html(data);
                    }
                });
            }
        });

        $('#generateBtn').click(function() {
            var btn = $(this);
            var classesID = $('#classesID').val();
            var referenceFeetypeID = $('#referenceFeetypeID').val();
            var targetFeetypeID = $('#targetFeetypeID').val();
            var dueDate = $('#dueDate').val();

            var errors = [];
            if(classesID === '0') {
                errors.push('<?=$this->lang->line("class_required")?>');
            }
            if(referenceFeetypeID === '0') {
                errors.push('<?=$this->lang->line("reference_feetype_required")?>');
            }
            if(targetFeetypeID === '0') {
                errors.push('<?=$this->lang->line("target_feetype_required")?>');
            }
            if(dueDate === '') {
                errors.push('<?=$this->lang->line("due_date_required")?>');
            }
            if(referenceFeetypeID === targetFeetypeID && referenceFeetypeID !== '0') {
                errors.push('<?=$this->lang->line("same_feetype_error")?>');
            }

            if(errors.length > 0) {
                $('#resultMessage').removeClass('alert-success').addClass('alert-danger').html(errors.join('<br>')).show();
                return;
            }

            btn.attr('disabled', 'disabled').html('<i class="fa fa-spinner fa-spin"></i> Processing...');
            $('#resultMessage').hide();

            $.ajax({
                type: 'POST',
                url: "<?=base_url('invoice/generateInvoices')?>",
                data: {
                    'classesID': classesID,
                    'referenceFeetypeID': referenceFeetypeID,
                    'targetFeetypeID': targetFeetypeID,
                    'dueDate': dueDate
                },
                dataType: "json",
                success: function(response) {
                    btn.removeAttr('disabled').html('<i class="fa fa-cogs"></i> <?=$this->lang->line("generate")?>');
                    
                    if(response.status) {
                        $('#resultMessage').removeClass('alert-danger').addClass('alert-success').html(
                            '<strong><?=$this->lang->line("generate_success")?></strong><br>' + response.message
                        ).show();
                    } else {
                        $('#resultMessage').removeClass('alert-success').addClass('alert-danger').html(response.message).show();
                    }
                },
                error: function() {
                    btn.removeAttr('disabled').html('<i class="fa fa-cogs"></i> <?=$this->lang->line("generate")?>');
                    $('#resultMessage').removeClass('alert-success').addClass('alert-danger').html('<?=$this->lang->line("generate_error")?>').show();
                }
            });
        });
    });
</script>
