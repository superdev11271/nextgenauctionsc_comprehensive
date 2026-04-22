@php
    $autobid_intervals = App\Models\AutobidInterval::all();
@endphp
<div class="modal fade" id="autobid_info" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-600">{{ translate('Information') }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            </div>
            <div class="modal-body">

                <div class="container">
                    <h4 id="section1" class="border-bottom pb-2">Setting Up Automatic Bidding</h4>

                    <p>To enable automatic bidding on an auction listing, follow these steps:</p>

                    <ul class="list-group mb-3">
                        <li class="">1. Slide the slide toggle to Autobid</li>
                        <li class="">2. Enter Maximum Bid - Specify the highest amount you are willing to pay for the item.</li>
                        <li class="">3. Place Bid - Select the "Place Bid" option to confirm your entry.</li>
                        <li class="">4. Sit back and allow our system to bid on your behalf until it reaches your maximum bid.</li>
                        <li class="">5. If you are outbid, we will notify you so you can decide whether to increase your maximum limit.</li>
                    </ul>
                    <h5 class="">Important Considerations:</h5>
                    <p>When determining your maximum bid, remember to factor in additional costs such as the buyer's premium, taxes, and shipping costs.</p>
                </div>

                <div class="card-body light-dark-bg px-4 p-2 table-responsive">
                    <table class="shopping-cart table table-responsive-md  text-nowrap">
                        <thead class="text-gray fs-12">
                            <tr>
                                <th width="40%">Min</th>
                                <th width="40%">Max</th>
                                <th width="40%">Autobid amount</th>
                            </tr>
                        </thead>
                        <tbody class="fs-14">
                            @foreach ($autobid_intervals as $interval)
                                <tr class="cart-item">
                                    <td>{{ $interval->min_bid }}</td>
                                    <td>{{ $interval->max_bid }}</td>
                                    <td>{{ $interval->increment }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
