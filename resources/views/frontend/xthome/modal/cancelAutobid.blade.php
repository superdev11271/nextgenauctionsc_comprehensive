<div class="modal fade" tabindex="-1" id="cancel_autobid" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('Cancel Autobid') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="form-default" role="form" action="{{ route('auction_product_bids.cancel', $productId) }}" method="POST">
                    @csrf
                    <div class="form-floating mb-3 mt-0">
                        <p>Are you sure you want to cancel autobid ?</p>
                    </div>
                    <div class="col">
                        <div class="form-group d-flex justify-content-end gap-3">
                            <button type="button" class="theme-btn-one" data-bs-dismiss="modal">{{ translate('No') }}</button>
                            <button type="submit" class="theme-btn-two">{{ translate('Yes') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
