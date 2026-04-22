<div class="">
    @if ($errors->any())
        <div class="col-md-12">
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    <form id="currencyFormUpdate" action="{{ route('your_currency.update') }}" method="POST" >
    @csrf
    <input type="hidden" name="id" value="{{ $currency->id }}">
    <div class="modal-header">
    	<h5 class="modal-title h6">{{translate('Update Currency')}}</h5>
    	<button type="button" class="close" data-dismiss="modal">
    	</button>
    </div>
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-sm-2 col-from-label" for="name">{{translate('Name')}}<span class="text-danger">*</span></label>
            <div class="col-sm-10">
                <input type="text" placeholder="{{translate('Name')}}" id="name" name="name" value="{{ $currency->name }}" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-from-label" for="symbol">{{translate('Symbol')}}<span class="text-danger">*</span></label>
            <div class="col-sm-10">
                <input type="text" placeholder="{{translate('Symbol')}}" id="symbol" name="symbol" value="{{ $currency->symbol }}" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-from-label" for="code">{{translate('Code')}}<span class="text-danger">*</span></label>
            <div class="col-sm-10">
                <input type="text" placeholder="{{translate('Code')}}" id="code" name="code" value="{{ $currency->code }}" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-from-label" for="exchange_rate">{{translate('Exchange Rate')}}<span class="text-danger">*</span></label>
            <div class="col-sm-10">
                <input type="number" lang="en" step="0.01" min="0" placeholder="{{translate('Exchange Rate')}}" id="exchange_rate" name="exchange_rate" value="{{ $currency->exchange_rate }}" class="form-control">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
        <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>
    </div>
</form>
</div>
<script>
    $(document).ready(function () { 

        $.validator.addMethod('customWhitespaceValidation', function (value, element) {
        return this.optional(element) || /\S/.test(value);
        }, 'Whitespaces are not allowed.');


        $("#currencyFormUpdate").validate({
        rules: {
            name: {
            required: true,
            customWhitespaceValidation: true
            },
            symbol: {
            required: true,
            customWhitespaceValidation: true
            },
            code: {
            required: true,
            customWhitespaceValidation: true
            },
            exchange_rate: {
            required: true,
            customWhitespaceValidation: true
            },
        },
        messages: {
            name: {
            required: "Please enter name",
            customWhitespaceValidation: "Please enter name",
            },
            symbol: {
            required: "Please enter symbol",
            customWhitespaceValidation: "Please enter symbol",
            },
            code: {
            required: "Please enter code",
            customWhitespaceValidation: "Please enter code",
            },
            exchange_rate: {
            required: "Please enter Exchange Rate",
            customWhitespaceValidation: "Please enter Exchange Rate",
            },
        },
        tooltip_options: {
            name: {
            placement: 'top',
            html: true
            },
            symbol: {
            placement: 'top',
            html: true
            },
            code: {
            placement: 'top',
            html: true
            },
            exchange_rate: {
            placement: 'top',
            html: true
            }
        }
        });



        });
</script>