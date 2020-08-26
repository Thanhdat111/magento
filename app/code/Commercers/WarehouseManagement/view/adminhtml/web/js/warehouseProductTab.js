require(['jquery', 'jquery/ui', 'Magento_Ui/js/modal/modal'], function ($, ui, modal) {
    'use strict';
    $('.add').click(function () {
        var html = '<tr><td><select  name="warehouse[]" class="admin__control-select select-warehouse" data-id="' + window.count + '" >';
        html = html.concat('<option value="">--Please Select--</option>');
        for (var i = 0; i < window.selectWarehouse.length; i++) {
            html = html.concat('<option value=' + window.selectWarehouse[i].warehouse_id + '>' + window.selectWarehouse[i].warehouse_name + '</option>');
        }
        html = html.concat('</select></td>');
        html = html.concat('<td><select name="area[]" class="admin__control-select area-' + window.count + '" ><option value="">--Please Select--</option></select></td> <td><input name="rackRow[]" class="admin__control-text"></td> <td><input name="rackLevel[]" class="admin__control-text"></td><td><input name="quantity[]" class="admin__control-text"></td><td><input name="priority[]" value="0" class="admin__control-text"></td><td class="remove"><button type="button">Remove</button></td></tr>');
        $('.block:last').before(html);
        window.count++;
    });
    var rowId = new Array();

    $('.optionBox').on('click', '.remove', function () {
        var row = $(this).data("id");
        rowId.push(row);

        var rowDelete = JSON.stringify(rowId);
        $('#row-delete').val(rowDelete);
        $(this).parent().remove();
    });

    $('.optionBox').on('change', '.select-warehouse', function () {
        var valueOption = $(this).val();
        var rowField = $(this).data("id");
        $.ajax(
            window.warehouseUrl.get_select_area,
            {
                type: "POST",
                dataType: "json",
                data: {valueOption: valueOption},
                showLoader: true,
                success: function (response) {
                    $('.area-' + rowField).html(response.block);
                }
            });
    });
    var options = {
        type: 'slide',
        responsive: true,
        innerScroll: true,
        title: 'Warehouse Management',
        modalClass: 'custom-modal',
        buttons: [{
            text: $.mage.__('Close'),
            class: '',
            click: function () {
                this.closeModal();
            }
        },
            {
                text: $.mage.__('Save'),
                class: 'action-default primary',
                click: function () {
                    var dataJson = $("#form-warehouse").serialize();
                    $.ajax(
                        window.warehouseUrl.get_save_product_linking,
                        {
                            type: "POST",
                            dataType: "json",
                            data: dataJson,
                            showLoader: true,
                            success: function (response) {
                                alert(response.message);
                                $('input[name="product[quantity_and_stock_status][qty]"]').val(response.qty);
                            }
                        });
                }
            }

        ]
    };
    var popup = modal(options, $('#slide-wahouse'));
    $("#open-slide-warehouse").on('click', function () {
        $("#slide-wahouse").modal("openModal");
    });
});