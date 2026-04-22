<script>
    function showValidationError(target, errors) {
        $(target).addClass("d-none");
        let error_content = `<ul>`;
        for (let key in errors) {
            let error = errors[key];
            if (error && error[0]) error_content += `<li>${error[0]}</li>`;
        }
        error_content += `</ul>`;
        console.log(error_content);
        $(target).removeClass("d-none").html(error_content);
    }

    jQuery(document).ready(function($) {
        $(document).on("click", "#login-btn, #login-btn1", function() {
            var data = new FormData($('#login-form')[0]);
            $("#login-form-errors").addClass("d-none");
            var button = $(this);
            var loader = $('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            button.prop('disabled', true).append(loader);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('login')}}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    button.prop('disabled', false).find('.spinner-border').remove();
                    if (response) {
                        if (response.error) {
                            AIZ.plugins.notify('danger', response.error);
                        } else if (response.success) {
                            AIZ.plugins.notify('success', response.success);
                            location.reload();
                        }
                    }
                },
                error: function(response) {
                    button.prop('disabled', false).find('.spinner-border').remove();
                    if (response && response.responseJSON && response.responseJSON.errors) {
                        let errors = response.responseJSON.errors;
                        let error_content = `<ul>`;
                        for (let key in errors) {
                            let error = errors[key];
                            if (error && error[0]) error_content += `<li>${error[0]}</li>`;
                        }
                        error_content += `</ul>`;
                        console.log(error_content);
                        $("#login-form-errors").removeClass("d-none").html(error_content);
                    }

                    const countdownElement = document.getElementById('countdown');
                    countdownElement.parentNode.parentNode.parentNode.style.display = 'block';
                    if (countdownElement) {
                        let seconds = parseInt(countdownElement.textContent);

                        const countdownInterval = setInterval(() => {
                            seconds -= 1;
                            countdownElement.textContent = seconds;

                            if (seconds <= 0) {
                                clearInterval(countdownInterval);
                                countdownElement.parentNode.parentNode.parentNode.style.display = 'none';
                            }
                        }, 1000);
                    }
                }
            });
        });

        function objectAlert(obj) {
            let result = '';
            for (let key in obj) {
                if (obj.hasOwnProperty(key)) {
                result += `${key}: ${obj[key]}\n`;
                }
            }
    alert(result);
}
        $(document).on("click", "#forgot-password-btn", function() {
            $("#forgot-password-btn").attr("disabled", "true");
            $("#forgot-password-errors").addClass("d-none");
            var data = new FormData($('#forgot-password-form')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('password.email')}}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    $("#forgot-password-btn").removeAttr("disabled");
                    if (response) {
                        if (response.error) {
                            AIZ.plugins.notify('danger', response.error);
                        } else if (response.success && response.email) {
                            $("#resetPassModalBtn").click();
                            $("#reset-password-email").val(response.email)
                        }
                    }
                },
                error: function(response) {
                    $("#forgot-password-btn").removeAttr("disabled");
                    if (response && response.responseJSON && response.responseJSON.errors) {
                        let errors = response.responseJSON.errors;
                        showValidationError("#forgot-password-errors", errors);
                    }
                }
            });
        });

        $(document).on("click", "#reset-password-btn", function() {
            $("#reset-password-errors").addClass("d-none");
            var data = new FormData($('#reset-password-form')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': AIZ.data.csrf
                },
                url: "{{route('password.update')}}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response) {
                        if (response.error) {
                            AIZ.plugins.notify('danger', response.error);
                        } else if (response.success) {
                            AIZ.plugins.notify('success', response.success);
                            window.location.reload();
                        }
                    }
                },
                error: function(response) {
                    if (response && response.responseJSON && response.responseJSON.errors) {
                        let errors = response.responseJSON.errors;
                        showValidationError("#reset-password-errors", errors);
                    }
                }
            });
        });

        $(document).on("click", "#register-form-btn", function() {
            $("#register-form-btn").attr("disabled", "true");
            $("#register-form-errors").addClass("d-none");
            var data = new FormData($('#register-form')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('register')}}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log(response);
                    $("#register-form-btn").removeAttr("disabled");
                    if (response) {
                        if (response.error) {
                            AIZ.plugins.notify('danger', response.error);
                        } else if (response.success) {
                            AIZ.plugins.notify('success', response.success);
                            document.getElementById("login-tab").click();
                        }
                    }
                },
                error: function(response) {
                    $("#register-form-btn").removeAttr("disabled");
                    if (response && response.responseJSON && response.responseJSON.errors) {
                        let errors = response.responseJSON.errors;
                        showValidationError("#register-form-errors", errors);
                    }
                }
            });
        });

    });
</script>

<script>

    function updateCountdownForElement(element) {
        var now = moment().tz("{{env('APP_TIMEZONE')}}");
        var startDate = element.dataset.startunixtime ? moment.unix(element.dataset.startunixtime).tz("{{env('APP_TIMEZONE')}}") : null;
        var endDate = element.dataset.endunixtime ? moment.unix(element.dataset.endunixtime).tz("{{env('APP_TIMEZONE')}}") : null;
        if (startDate && startDate > now) {
            // Auction is upcoming
            var distanceToStart = startDate - now;
            var days = Math.floor(distanceToStart / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distanceToStart % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distanceToStart % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distanceToStart % (1000 * 60)) / 1000);

            element.innerHTML = "Auction will start  <br> "+ days + "d " + hours + "h " + minutes + "m " + seconds + "s";
        } else if (endDate && endDate > now) {
            // Auction is ongoing
            var distanceToEnd = endDate - now;
            var days = Math.floor(distanceToEnd / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distanceToEnd % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distanceToEnd % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distanceToEnd % (1000 * 60)) / 1000);

            element.innerHTML = "Time Left  <br> "+ days + "d " + hours + "h " + minutes + "m " + seconds + "s";
        } else {
            // Auction ended
            element.innerHTML = "Auction Ended";
        }
    }

    // Function to update all countdowns
    function updateAllCountdowns() {
        var countdowns = document.querySelectorAll('.auction-timer');
        countdowns.forEach(function(element) {
            updateCountdownForElement(element);
        });
    }

    // Initial update and set interval to update every second
    updateAllCountdowns();
    setInterval(updateAllCountdowns, 1000);

</script>


<script>
    function showFloatingButtons() {
        document.querySelector('.floating-buttons-section').classList.toggle('show');
    }

    if (document.cookie.indexOf('buttonClicked=') === -1) {
        // If the cookie does not exist or its value is not 'auction' or 'marketplace',
        // execute the code inside setTimeout after 1 seconds
        setTimeout(function() {
            $('#landing_model').addClass("show")
        }, 1000);
    }

    function closeModal() {
        $('#landing_model').removeClass("show");
        setCookieAndRedirect("marketplace", "{{ route('marketplace') }}");
    }

    function setCookieAndRedirect(buttonValue, route) {
        var expirationDate = new Date();
        expirationDate.setMonth(expirationDate.getMonth() + 1);
        document.cookie = "buttonClicked=" + buttonValue + "; expires=" + expirationDate.toUTCString();
        window.location.href = route;
    }

    $('#auctionButton').click(function() {
        setCookieAndRedirect("auction", "{{ route('auction_products.all') }}");
    });

    $('#marketplaceButton').click(function() {
        setCookieAndRedirect("marketplace", "{{ route('marketplace') }}");
    });
</script>
