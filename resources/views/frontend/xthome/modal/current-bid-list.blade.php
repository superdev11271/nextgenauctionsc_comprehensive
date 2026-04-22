<!-- Bid List Modal -->
<div class="modal fade" id="bid_list_product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ translate('Bidding Details') }} <small
                        id="min_bid_amount"></small> </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

            </div>

            <div class="modal-body">
                <div class="table-responsive">
                    <table class="shopping-cart table">
                        <thead>
                            <tr>
                                <th style="min-width:20px;">#</th>
                                <th style="min-width:220px;">{{ translate('Customer Details') }}</th>
                                <th style="min-width:200px;">{{ translate('Bid Time') }}</th>
                                <th style="min-width:120px;">{{ translate('Bid Price') }}</th>
                                <th style="min-width:80px;" class="text-center">{{ translate('Bid Qty') }}</th>
                                <th style="min-width:100px;" class="text-center">{{ translate('Win Qty') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($bid_list[0]))
                                @foreach ($bid_list as $key => $data)
                                    @if ($data != null)
                                        <tr class="cart-item">
                                            <td>
                                                {{ $key + 1 }}
                                            </td>
                                            <td>
                                                {{ $data->user?->name }} ( {{ $data->user?->user_code }})
                                            </td>
                                            <td>
                                                {{ $data->updated_at->format('Y-m-d, H:i') }}
                                            </td>
                                            <td>
                                                {{ $data->amount }}
                                            </td>
                                            <td class="text-center">
                                                1
                                            </td>
                                            <td class="text-center">
                                                @if ($key == 0)
                                                    1
                                                @else
                                                    0
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                <tr class="cart-item">
                                    <td colspan="6">

                                        <div class="alert alert-danger text-center">
                                            {{ translate('Data not found') }}
                                        </div>

                                    </td>

                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
