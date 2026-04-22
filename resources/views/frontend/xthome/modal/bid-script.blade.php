<script>
    let timers = {};

    async function checkMinBidAmount(productId, min_bid_amount) {
        try {
            let url = "{{ route('get_highest_bid', ':id') }}";
            let getUrl = url.replace(":id", productId)
            var amountInput = document.getElementById("amountInput" + productId);

            var minBidAmount = parseFloat(min_bid_amount);


            var userRole = "{{ auth()->user()->user_type ?? '' }}"; // Assuming the role is passed from Laravel

            // Example of checking the role
            if (userRole === 'staff' || userRole === 'admin') {
                AIZ.plugins.notify('warning', "You are not authorized to bid on this product.");
                            event.preventDefault(); // Prevent form submission
                            return;
            }



            // Check if the input field is empty
            if (amountInput.value.trim() === "") {
                AIZ.plugins.notify('warning', "Please enter a bid amount.");
                event.preventDefault(); // Prevent form submission
                return;
            }

            // Check if the bid amount is less than or equal to the minimum bid amount
            if (parseFloat(amountInput.value) <= minBidAmount) {
                AIZ.plugins.notify('warning', "Place a bid greater than this amount $" + minBidAmount + ".");
                event.preventDefault(); // Prevent form submission
                return;
            }

            // Show processing text and hide "Place Maximum Bid" text
            $('#placeBidText' + productId).hide();
            $('#processingText' + productId).show();

            let has_high_bid = false;
            $.ajax({
                url: getUrl,
                method: "GET",
                async: false,
                success: function(lastBidAmount) {
                    lastBidAmount = parseFloat(lastBidAmount)
                    if (lastBidAmount >= parseFloat(amountInput.value)) {
                        AIZ.plugins.notify('warning',
                            `Someone has bid ${lastBidAmount}. Place a bid greater ${lastBidAmount+1}`
                            );
                        $('#currentBidAmount' + productId).html('$' + parseFloat(lastBidAmount));
                        event.preventDefault(); // Prevent form submission
                        has_high_bid = true
                    }
                }
            });
            if (has_high_bid) {
                $('#placeBidText' + productId).show();
                $('#processingText' + productId).hide();
                return
            }

            amountInput.setAttribute("readonly", "true");

            let seconds = 80;
            timers[productId] = setInterval(function() {
                if (seconds > 0) {
                    $('#bidbutton' + productId).html(
                        '<button type="button" class="theme-btn-card btn-sm w-100" onclick="ConfirmBid(' +
                        productId + ',' + min_bid_amount + ',' + timers[productId] +
                        ')"><span id="placeBidText' + productId + '">Confirm Bid $' + parseFloat(
                            amountInput.value) + ' (' + seconds-- +
                        's)</span>' +
                        '<span id="processingText' + productId + '" style="display: none;">' +
                        '<span class="spinner-border" role="status" aria-hidden="true"></span>Processing...' +
                        '</span></button>');
                } else {
                    clearInterval(timers[productId]);
                    $('#bidbutton' + productId).html(
                        `<button type="button" class="theme-btn-card btn-sm w-100" onclick="checkMinBidAmount('${productId}',' ${min_bid_amount}')">
                                  <span id="placeBidText${productId}">Place Maximum Bid</span>
                                  <span id="processingText${productId}" style="display: none;">
                                      <span class="spinner-border" role="status" aria-hidden="true"></span>
                                      Processing...
                                  </span>
                          </button>`
                    );
                }
            }, 1000);

            await simulateAsyncOperation();

        } catch (error) {
            amountInput.removeAttribute("readonly");
            showWarning(error.message);
        } finally {
            // Show "Place Maximum Bid" text and hide processing text after processing is complete
            $('#placeBidText' + productId).show();
            $('#processingText' + productId).hide();
        }
    }
    async function ConfirmBid(productId, min_bid_amount, timer) {
        try {
            clearInterval(timer);
            $('#placeBidText' + productId).hide();
            $('#processingText' + productId).show();
            var formData = $('.bid-form[data-product-id="' + productId + '"]').serialize();
            // Send form data via AJAX
            $.ajax({

                type: 'POST',
                url: $('.bid-form[data-product-id="' + productId + '"]').attr('action'),
                data: formData,
                success: function(response) {
                    if(response.refresh_required == true){
                            window.location.reload();
                    }

                    // Handle successful response
                    if (response.status == true) {

                        var amountInput = document.getElementById("amountInput" + productId).value;
                        $('#amountInput' + productId).val(response.next_bid !== undefined ? response.next_bid : '');
                        $('#currentBid' + productId).show();
                        // $('#my-bid-status').removeClass('fa-thumbs-down').addClass('fa-thumbs-up');
                        if(parseFloat(response.current_bid) == parseFloat(response.my_bid)){
                            $('#my-bid-status').removeClass('fa-thumbs-down').addClass('fa-thumbs-up');
                            $('#my-bid-status-'+productId).removeClass('fa-thumbs-down text-danger').addClass('fa-thumbs-up text-success');
                        }
                        $('#bidbutton' + productId).html('');
                        $('#currentBidAmount' + productId).html('$' + parseFloat(response.current_bid));
                        $('#mybid' + productId).html('$' + parseFloat(response.my_bid));
                        $('#bidbutton' + productId).html(
                            `<button type="button" class="theme-btn-card btn-sm w-100" onclick="checkMinBidAmount('${productId}',' ${min_bid_amount}')">
                                  <span id="placeBidText${productId}" style="display: none;">Place Maximum Bid</span>
                                  <span id="processingText${productId}" >
                                      <span class="spinner-border" role="status" aria-hidden="true"></span>
                                      Processing...
                                  </span>
                          </button>`
                        );

                        AIZ.plugins.notify('success', response.msg);



                    } else {
                        $('#amountInput' + productId).val('');
                        $('#currentBid' + productId).show();
                        $('#bidbutton' + productId).html('');
                        $('#bidbutton' + productId).html(
                            `<button type="button" class="theme-btn-card btn-sm w-100" onclick="checkMinBidAmount('${productId}',' ${min_bid_amount}')">
                                  <span id="placeBidText${productId}" style="display: none;">Place Maximum Bid</span>
                                  <span id="processingText${productId}" >
                                      <span class="spinner-border" role="status" aria-hidden="true"></span>
                                      Processing...
                                  </span>
                          </button>`
                        );
                        AIZ.plugins.notify('warning', response.msg);
                    }
                    $('#placeBidText' + productId).show();
                    $('#processingText' + productId).hide();
                },
                error: function(xhr, status, error) {
                    $('#bidbutton' + productId).html(
                        `<button type="button" class="theme-btn-card btn-sm w-100" onclick="checkMinBidAmount('${productId}',' ${min_bid_amount}')">
                                  <span id="placeBidText${productId}" style="display: none;">Place Maximum Bid</span>
                                  <span id="processingText${productId}" >
                                      <span class="spinner-border" role="status" aria-hidden="true"></span>
                                      Processing...
                                  </span>
                          </button>`

                    );
                    // Handle error
                    $('#placeBidText' + productId).show();
                    $('#processingText' + productId).hide();
                    console.error(xhr.responseText);
                }
            });
            await simulateAsyncOperation();

        } catch (error) {
            showWarning(error.message);
        } finally {
            document.getElementById("amountInput" + productId).removeAttribute("readonly");
            // Show "Place Maximum Bid" text and hide processing text after processing is complete
            // $('#placeBidText' + productId).show();
            // $('#processingText' + productId).hide();
        }
    }

    async function simulateAsyncOperation() {
        return new Promise(resolve => setTimeout(resolve, 2000));
    }

    function bid_modal() {
        @if (Auth::check() && (Auth::user()->user_type == 'customer' || Auth::user()->user_type == 'seller'))
            $('#bid_for_product').modal('show');
        @else
            $('#login_modal').modal('show');
        @endif
    }
</script>
