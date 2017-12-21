jQuery(document).ready(function () {
    var $ = jQuery;

    // Remove Preivew and Save Draft box
    if ($('#minor-publishing').length)
        //$('#minor-publishing').remove();

    var add_button_elem = $('#trs_product_add_button');
    var all_product_input_elems = $('input[id^="trs-product-"], select[name^="trs-invoice-product-info[trs-product-"]');
    var invoice_output_table = $('.trs-invoice-output-table');
    var invoice_output_tbody_elem = $('#trs-invoice-output-tbody');
    var product_info_array_elem = $('#trs-invoice-product-info-arr');
    var products_array = {};
    var current_index = 0;

    var unit = trs_invoice_mb_obj.currency_unit;


    // Enabling Select 2
    var product_names_data = {
        'action': 'trs_invoice_get_product_name',
        'whatever': 'ss'    // We pass php values differently!
    };
    $('#trs-product-listing').select2({
        ajax: {
            delay: 250,
            url: trs_invoice_mb_obj.ajax_url,
            data: function (params){
                return {
                    q: params.term,
                    action: 'trs_invoice_get_product_name'
                };
            },
            dataType: 'json',
            method: 'POST',
            processResults: function (data, params) {

                return{
                    results: data
                }
            },
            cache: true
        },
        placeholder: 'Start typing product for search.',
        minimumInputLength: 3,
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        templateResult: formatRepo
     });

    function formatRepo (repo) {
        if (repo.loading) {
            return repo.text;
        }

        var markup = "<div class='select2-result-repository clearfix'>" +
            "<div class='select2-result-repository__avatar'><img src='" + repo.image_src + "' /></div>" +
            "<div class='select2-result-repository__meta' >" +
            "<div class='select2-result-repository__title'>Title: " + repo.title + "</div>";
        markup += "<div class='select2-result-repository__description'>ID: " + repo.id + "</div>";


        markup += "</div></div>";



        return markup;
    }



    if ($('#trs_product_add_button').length > 0) {
        add_button_elem.click(function (e) {
            e.preventDefault();

            var selected_id = $('#trs-product-listing').find('option:selected').val();

            // add border / remove border
            if (selected_id.length < 1){
                $('.trs_product_listing_wrapper').addClass('red-border');
                return;
            }else {
                $('.trs_product_listing_wrapper').removeClass('red-border');
            }


            if (isNaN($('#trs-product-price').val())){
                $('#trs-product-price').addClass('input-error').attr('title', 'Please add digit only');
                return "";
            }else {
                $('#trs-product-price').removeClass('input-error').attr('title', 'Please will be rounded.');
            }


            // add row in output
            var html = '<tr id='+selected_id+'>';


            all_product_input_elems.each(function (i, e) {

                //$(e).attr('name');
                //$(e).val();

                var value = $(e).val();


                // Skip iteration not needed
                if ('trs-product-price-unit' === $(e).attr('id'))
                    return;


                if ('trs-invoice-product-info[trs-product-listing]' === $(e).attr('name'))
                    value = $(e).find('option:selected').attr('data-label');


                if ('trs-product-price' === $(e).attr('id') ){
                    if (value.length < 1)
                        value = 0;

                    value = round(value,2);

                    if (Number.isInteger(value))
                        value = value + '.00';

                    var quantity = $('#trs-product-quantity').val();
                    if (quantity < 1)
                        quantity = 1;
                    var per_unit_item = value;
                    var price = value * quantity;

                    value = round(price,2);

                    if (Number.isInteger(value))
                        value = value + '.00';

                    //value = $('#trs-product-price-unit').find('option:selected').text() + " " + quantity + 'x' + per_unit_item + ' = ' + value;
                    value = unit + " " +  value;
                }


                if ('trs-product-quantity' === $(e).attr('id')){
                    if (value.length < 1)
                        value = 1;
                }

                //console.log($('#trs-product-price-unit').find('option:selected').text());


                html += '<td>';
                html += value;
                html += '</td>';


            });

            // add remove icon
            html += '<td>';
            html += '<i id="trs-invoice-remove-row" title="Remove Row" class="trs-invoice-remove-row fa fa-remove"></i>';
            html += '</td>';


            html += '</tr>';

            var prev_html = invoice_output_tbody_elem.html();
            html = prev_html + html;

            invoice_output_tbody_elem.html(html);
            invoice_output_table.show(250);

            // Reset Product Information
            reset_product_info_inputs();

            call_update();
        });

        invoice_output_tbody_elem.sortable({
            revert: true,
            update: function(event, ui) {

                // Update product array
                call_update();
            },
            start: function(event, ui) {
                //console.log('start: ' + ui.item.index())
                startingPosition = ui.item.index();
            }
        });
        invoice_output_tbody_elem.disableSelection();

        $(document).on('click', '#trs-invoice-remove-row', function (e){
            e.preventDefault();
            $(this).parents('tr').hide(1200, function(){
                $(this).remove();
                call_update();
            });

            /*@todo: update_product_info_array() after removing*/
        });
    }


    function call_update(){
        products_array = {};
        var elements = invoice_output_tbody_elem.children();
        if (elements.length < 1)
            flush_hidden_values();


        elements.each(function (element, e){
            do_products_arr_update( e, false );
        });
    }

    //Round to a decimal of your choosing:
    function round(num,dec)
    {
        num = Math.round(num+'e'+dec);
        return Number(num+'e-'+dec)
    }



    function flush_hidden_values(){

        // Update into HTML
        product_info_array_elem.val('');

    }

    function do_products_arr_update(e, display_log){
        var elem = $(e);

        var id = elem.attr('id');
        var name = elem.find('td:first-child').text();
        var specification = elem.find('td:nth-child(2)').text();
        var size = $(e).find('td:nth-child(3)').text();
        var stock = $(e).find('td:nth-child(4)').text();
        var printing = $(e).find('td:nth-child(5)').text();
        var finishing = $(e).find('td:nth-child(6)').text();
        var quantity = $(e).find('td:nth-child(7)').text();
        var price = $(e).find('td:nth-child(8)').text();

        products_array[current_index] = {
            id: id,
            name: name,
            specification: specification,
            size: size,
            stock: stock,
            printing: printing,
            finishing: finishing,
            quantity: quantity,
            price: price
        };
        if (display_log)
            console.log(products_array);

        // Update into HTML
        product_info_array_elem.val(JSON.stringify(products_array));
        current_index++;

    }




    function reset_product_info_inputs(){
        all_product_input_elems.each(function (i, e){
            // Select / Option
            if ('trs_product_listing' === $(e).attr('name')){
                $(e).prop('selectedIndex',0);
                return "";
            }


            // inputs
            $(e).val('');
        });
    }

    // reset after clicking on Add button

    // when user click on invoice_url_input then select all and copied. use clipboard.js
});
