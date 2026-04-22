<script>
    (function () {
        // Load realtime client only on pages that actually render auction bid/timer UI.
        const hasAuctionRealtimeUi = !!(
            document.querySelector('[class*="auction-timer-"]') ||
            document.querySelector('[id^="currentBidAmount"]') ||
            document.querySelector('[id^="amountInput"]') ||
            document.querySelector('#bid_for_product')
        );

        if (!hasAuctionRealtimeUi) {
            return;
        }

        const pusherScript = document.createElement('script');
        pusherScript.src = 'https://js.pusher.com/8.2.0/pusher.min.js';
        pusherScript.async = true;
        pusherScript.onload = function () {
            if (typeof Pusher === 'undefined') {
                return;
            }

            Pusher.logToConsole = false;

            var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
                cluster: '{{ env('PUSHER_APP_CLUSTER') }}'
            });

            var channel = pusher.subscribe('nexgen');
            channel.bind('bid_update', function(data) {
                updateElement(data);
            });
        };
        document.head.appendChild(pusherScript);
    })();

    function updateElement(data) {
        let product_id = data.product_id
        let end_time = data.end_time
        let end_time_unixtime = data.end_time_unixtime
        let currentBidAmount = data.current_bid_amount
        let nextBid = data.next_bid
        var displayClockValue = getFormattedTimeEndTime(end_time)

        let myAutobidamount =
            {{ isset($highest_bid) && isset($autobidRange) && $autobidRange > $highest_bid ? $autobidRange : 0 }}


        if (displayClockValue) {
            let timer = $('.auction-timer-' + product_id)
            $('#currentBidAmount' + product_id).text(currentBidAmount)
            $('#amountInput' + product_id).val(nextBid)


            let myBidEle = $('#mybid' + product_id).text();
            let myBidAmount = parseFloat(myBidEle.replace('$', ''))

            if (currentBidAmount <= myAutobidamount) {
                $('#mybid' + product_id).text(currentBidAmount)
            } else if (currentBidAmount > myBidAmount) {
                $('#my-bid-status').removeClass('fa-thumbs-up').addClass('fa-thumbs-down');
                $('#my-bid-status-' + product_id).removeClass('fa-thumbs-up text-success').addClass(
                    'fa-thumbs-down text-danger');
            }

            // timer.text(displayClockValue);
            timer.attr('data-date', end_time);
            timer.attr('data-endunixtime', end_time_unixtime);

            if (typeof intervalId !== 'undefined') {
                clearInterval(intervalId)

                // Keep realtime updates safe even when moment/moment-timezone
                // are unavailable on the current page.
                let newEndtime;
                if (typeof moment !== 'undefined' && moment.unix) {
                    const m = moment.unix(end_time_unixtime);
                    newEndtime = (m && typeof m.tz === 'function')
                        ? m.tz("{{ env('APP_TIMEZONE') }}")
                        : m;
                } else {
                    newEndtime = new Date(end_time_unixtime * 1000);
                }

                if (typeof updateTimer === 'function') {
                    updateTimer(0, newEndtime);
                    intervalId = setInterval(() => {
                        updateTimer(0, newEndtime);
                    }, 1000);
                }
            }

        } else {
            timer.text("Ended");
        }
    }

    function getFormattedTimeEndTime(newEndTime) {
        var endDate = new Date(newEndTime).getTime();
        var now = new Date().getTime();
        var distance = endDate - now
        if (distance <= 0) return false;
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        return days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
    }

</script>
