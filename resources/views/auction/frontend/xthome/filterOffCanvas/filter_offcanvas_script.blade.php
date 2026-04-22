
function filter() {
    $('#search-form').submit();
}

function clearAllFilter() {
    $("#search-form").find("input, textarea, select").each(function() {
            $(this).val(null);
        });
    $("input[name=min_price]").val(0)
    $("input[name=max_price]").val(9999999)
    filter()
}

function clear_filter_by_container(container){
    $(container).find("input, textarea, select").each(function() {
        if ($(this).is(":checkbox")) {
            $(this).prop("checked", false);
        } else {
            $(this).val(null);
        }
    });
    filter()
}

function sort(value) {
    $('input[name=sort_by]').val(value)
    $('input[name=keywords]').attr('disabled', true); 
    $('input[name=min_price]').attr('disabled', true); 
    $('input[name=max_price]').attr('disabled', true);
    $('select[name="filterattributes[]"]').prop('disabled', true);
    filter()
}

function searchKeywords() {
    let searchkeys = $('input[name=searchkeyword]').val()
    $('input[name=keywords]').val(searchkeys)
    $('input[name=min_price]').attr('disabled', true); 
    $('input[name=max_price]').attr('disabled', true);
    $('select[name="filterattributes[]"]').prop('disabled', true);
    $('input[name=sort_by]').prop('disabled', true);
    filter()
}

function rangefilter(arg) {
    arg["max"]?$('input[name=max_price]').val(arg["max"]):""
    arg["min"]?$('input[name=min_price]').val(arg["min"]):""
    $('select[name="filterattributes[]"]').prop('disabled', true);
    $('input[name=keywords]').attr('disabled', true); 
    $('input[name=min_price]').attr('disabled', true); 
    $('input[name=max_price]').attr('disabled', true);
    filter();
}

function rangefilterOnchange(arg,element){

    $('select[name="filterattributes[]"]').prop('disabled', true);
    $('input[name=keywords]').attr('disabled', true);

    let currentMin = $('#fromInput')
    let currentMax = $('#toInput')
    {{-- console.debug(parseFloat($(element).val()),parseFloat( currentMax.val()),parseFloat($(element).val()) >= parseFloat(currentMax.val())) --}}
    if(arg["min"]){
        if (parseFloat($(element).val()) >= parseFloat(currentMax.val())) {
            $(element).val(parseFloat(currentMax.val()) - 1000)
            currentMin.val(parseFloat(currentMax.val()) - 1000 )
        }
        else{
            currentMin.val(arg["min"])
        }
    }
    if(arg["max"]){
        if (parseFloat($(element).val()) <= parseFloat(currentMin.val())) {
            $(element).val(parseFloat(currentMin.val()) + 1000)
            currentMax.val(parseFloat(currentMin.val()) + 1000 )
        }
        else{
            currentMax.val(arg["max"])
        }
    }
}

function defineRange(selectElement) {
    var selectedOption = selectElement.options[selectElement.selectedIndex];
    var max = selectedOption.value;
    var min = selectedOption.getAttribute('data-min');
    $('input[name=max_price]').val(max)
    $('input[name=min_price]').val(min)
    $('select[name="filterattributes[]"]').prop('disabled', true);
    $('input[name=keywords]').attr('disabled', true); 


    filter();
}


