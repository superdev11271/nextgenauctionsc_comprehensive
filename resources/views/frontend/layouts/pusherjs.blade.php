<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}'
    });

    var channel = pusher.subscribe('nexgen');
    channel.bind('bid_update', function(data) {
        updateElement(data)
    });

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
                let newEndtime = moment.unix(end_time_unixtime).tz("{{ env('APP_TIMEZONE') }}")
                updateTimer(0, newEndtime);
                intervalId = setInterval(() => {
                    updateTimer(0, newEndtime);
                }, 1000);
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
