/* dom is ready to serve */
$(document).ready(function(){
    /* crousel setting for nbfcs */
    $(".banks-crousel").owlCarousel({
        items: 2,
        loop: false,
        autoplay: true,
        navBy: 1,
        dots:false,
        autoplayTimeout: 4500,
        autoplayHoverPause: true,
        smartSpeed: 1500,
        responsive: {
            0: { items: 1 },
            600: { items: 2 },
            1000: { items: 2 }
        }
    });

    /* crousel settings for testimonials */
    $(".testimonials-carousel").owlCarousel({
        items: 3, // Number of items
        loop: true,
        autoplay: true,
        navBy: 1,
        dots: false,
        autoplayTimeout: 4500,
        autoplayHoverPause: true,
        smartSpeed: 1500,
        responsive: {
            0: { items: 1 },
            767: { items: 1 },
            768: { items: 2 },
            991: { items: 3 },
            1000: { items: 3 }
        }
    });

    /* when click on edit button in modal tha perform */
    $(".edit-phoneNumber").on('click', function(){
        $('#checkmodal').html('Apply Now');
        $('#checkmodal').attr('disabled', false);
        $('#exampleModal').modal('hide');
        let mobileField = document.getElementById('mobile');
        if(mobileField.value == '' || mobileField.value == null){
            if(sessionStorage.getItem('mobile')){
                mobileField.value = sessionStorage.getItem('mobile');
            }
        }
    });

    /* send otp scripts starts */
    $('.save-form-1').submit(function (event) {
        var status = document.activeElement.innerHTML;
        event.preventDefault();
        if (status) {
            $('.ajax-error').html('');
            var data = new FormData(this);
            /* ajax starts */
            $.ajax({
                url: $(this).attr("action"),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                data: data,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('#checkmodal').html('<span class="spinner-border spinner-border-sm"></span> Process Now');
                    $('#checkmodal').attr('disabled', true);
                },
                /* success function handle */
                success: function (result) {
                    $(this).attr("disabled", false);
                    /* if result getting success */
                    if (result.type === 'SUCCESS') {
                        if("redirectUrl" in result){
                            if (result.redirectUrl != '') {
                                var redirect = `${result.redirectUrl}`;
                                toastr.success(`User account already verified. Redirecting to your application in <span id="countdown">3</span> sec.`, '', {
                                    timeOut: 0,
                                    extendedTimeOut: 0
                                });

                                function updateCountdown(count) {
                                    document.getElementById('countdown').textContent = count;
                                }
                                let count = 3;
                                /* countdown starts */
                                let countdownInterval = setInterval(() => {
                                    count--;
                                    updateCountdown(count);
                                    if (count === 0) {
                                        clearInterval(countdownInterval);
                                        // Redirect logic here
                                        window.location.href = redirect;
                                    }
                                }, 1000);
                                /* copuntdown ends */
                            } else {
                                alert('something in else');
                            }
                        } else {
                            $("#mobileNumber").text(result.data);
                            $("#exampleModal").modal('hide');
                            $("#exampleModal").modal({
                                backdrop: 'static',
                                keyboard: false
                            });
                            // Trigger the modal to show
                            $("#exampleModal").modal('show');

                            // Start the countdown when the modal is fully shown
                            $('#exampleModal').on('shown.bs.modal', function () {
                                startCountdown(15);
                            });
                        }
                    } else {
                        toastr.error(result.message);
                        $('#usererrormsg').html(result.message);
                        $('#checkmodal').html('Apply Now');
                        $('#checkmodal').attr('disabled', false);
                    }
                },
                /* error function handle */
                error: function (error) {
                    $(this).attr("disabled", false);
                    let errors = error.responseJSON.errors, errorsHtml = '';
                    $.each(errors, function (key, value) {
                        errorsHtml = '<strong>' + value[0] + '</strong>';
                        $('.' + key).html(errorsHtml);
                    });
                    $('#checkmodal').html('Process Now');
                    $('#checkmodal').attr('disabled', false);
                }
            });
            /* ajax ends */
        }
    });
    /* send otp scripts ends */

    /* verify otp scripts starts */
    $('.save-form-2').submit(function (event) {
        $("#invalidOtp").text('');
        var status = document.activeElement.innerHTML;
        event.preventDefault();
        if (status) {
            $('.ajax-error').html('');
            var data = new FormData(this);
            /* ajax starts */
            $.ajax({
                url: $(this).attr("action"),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                data: data,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#otpBtn').html('<span class="spinner-border spinner-border-sm"></span> Validating...');
                    $('#otpBtn').attr('disabled', true);
                },
                /* success function handle */
                success: function (result) {
                    $(this).attr("disabled", false);
                    /* success result */
                    if (result.type === 'SUCCESS') {
                        if (result.redirectUrl != '') {
                            window.location.href = `${result.redirectUrl}`;
                        } else {
                            var redirect = `{{ route('self.apply.loan.details') }}`;
                            toastr.success(`User account already verified. Redirecting to your application in <span id="countdown">3</span> sec`, '', {
                                timeOut: 0,
                                extendedTimeOut: 0
                            });

                            function updateCountdown(count) {
                                document.getElementById('countdown').textContent = count;
                            }
                            let count = 3;
                            /* countdown starts */
                            let countdownInterval = setInterval(() => {
                                count--;
                                updateCountdown(count);
                                if (count === 0) {
                                    clearInterval(countdownInterval);
                                    // Redirect logic here
                                    window.location.href = redirect;
                                }
                            }, 1000);
                            /* copuntdown ends */
                        }
                    } else {
                        /* failed response */
                        $("#invalidOtp").text(result.message);
                        $('#otpBtn').html('Verify &amp; Proceed');
                        $('#otpBtn').attr('disabled', false);
                    }
                },
                /* error function handle */
                error: function (error) {
                    $(this).attr("disabled", false);
                    let errors = error.responseJSON.errors, errorsHtml = '';
                    $.each(errors, function (key, value) {
                        errorsHtml = '<strong>' + value[0] + '</strong>';
                        $('.' + key).html(errorsHtml);
                    });
                    $('#otpBtn').html('Verify &amp; Proceed');
                    $('#otpBtn').attr('disabled', false);
                }
            })
            /* ajax ends */
        }
    });
    /* verify otp scripts ends */

    /* open modal when redirect from another page */
    var openModal = sessionStorage.getItem('openModal');
    if (openModal === 'true') {
        // Open the modal
        $("#mobileNumber").text(sessionStorage.getItem('mobile'));
        $("#exampleModal").modal('hide');
        $("#exampleModal").modal({
            backdrop: 'static',
            keyboard: false
        });

        // Trigger the modal to show
        $("#exampleModal").modal('show');

        // Start the countdown when the modal is fully shown
        $('#exampleModal').on('shown.bs.modal', function () {
            startCountdown(15);
        });

        sessionStorage.removeItem('openModal');
    }

    /* manage countdown of resend otp */
    function startCountdown(duration){
        var timeRemaining = duration;
        var countdownElement = document.getElementById('timer');
        $('.resend-otp-div a').addClass('disabled');
        //resendOtpButton.textContent = 'Resend OTP'; // Ensure the text is correct

        timer = setInterval(function() {
            timeRemaining--;

            // Format the time as (00:xx)
            var formattedTime = '(00:' + (timeRemaining < 10 ? '0' : '') + timeRemaining + ')';
            countdownElement.textContent = formattedTime;

            if (timeRemaining <= 0) {
                clearInterval(timer);
                $('.resend-otp-div a').removeClass('disabled');
                countdownElement.textContent = ''; // Clear the countdown text
            }
        }, 1000);
    }

    /* resend otp function */
    $("#resendOtp").on('click', function() {
        let mobile = $("#mobileNumber").text();
        let user_type = $("input[name='user_type']:checked").val();
        let allow_sms = $("input[name='allow_sms']:checked").val();
        let accept_tnc = $("input[name='accept_tnc']:checked").val();
        let acc_type = $("input[name='acc_type']").val();
        var formData = new FormData();
        formData.append('mobile', mobile);
        formData.append('user_type', user_type);
        formData.append('allow_sms', allow_sms);
        formData.append('accept_tnc', accept_tnc);
        formData.append('acc_type', acc_type);
        /* ajax start */
        $.ajax({
            url: sendOtpUrl,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (result) {
                if (result.type === 'SUCCESS') {
                    toastr.success(result.message)
                    startCountdown(15);
                } else {
                    $('.resend-otp-div').html(`<p class="s-12 text-danger">${result.message}</p>`)
                }
            }
        })
        /* ajax end */
    });

    /* accept only numbers */
    $('.numeric-input').on('keydown', function(event) {
        if (!(event.key === 'Backspace' || event.key === 'Delete' || (event.key >= '0' && event.key <= '9'))) {
            event.preventDefault();
        }
    });
});
