<div class="modal fade" id="chat_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
    <div class="modal-content position-relative">
        <div class="modal-header">
            <h5 class="modal-title">{{ translate('Any query about this product') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        </div>
        <form class="" action="{{ route('conversations.store') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="product_id" value="{{ $detailedProduct->id }}">
            <div class="modal-body gry-bg px-3 pt-3">
                <div class="form-floating mb-4">
                    <input type="text" class="form-control" name="title"
                        value="{{ $detailedProduct->name }}" placeholder="{{ translate('Product Name') }}"
                        required>
                    <label for="Firstname">{{ translate('Product Name') }}</label>
                </div>

                <div class="form-group">
                    <textarea class="form-control" rows="8" name="message" required
                        placeholder="{{ translate('Your Question') }}">{{ route('product', $detailedProduct->slug) }}</textarea>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="theme-btn-two"
                    data-bs-dismiss="modal">{{ translate('Cancel') }}</button>
                <button type="submit" class="theme-btn-one">{{ translate('Send') }}</button>
            </div>
        </form>
    </div>
</div>
</div>
