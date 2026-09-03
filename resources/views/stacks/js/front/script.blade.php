@stack('scripts')
<script>
    $(document).ready(function(){
        $('.save-form-1').submit(function (event) {
            var status = document.activeElement.innerHTML;
            event.preventDefault();
            if (status) {
                $('.ajax-error').html('');
                const base = $(this).attr("action");
                var data = new FormData(this);
                /*var entries = [];
                data.forEach(function(value, key) {
                    entries.push(key + ": " + value);
                });
                alert(entries.join(", "));*/
                $.ajax({
                    url: $(this).attr("action"),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    data: data,
                    processData: false,
                    contentType: false,
                    beforeSend: function(){
                        $('#checkmodal').html('<span class="spinner-border spinner-border-sm"></span>');
                        $('#checkmodal').attr('disabled', true);
                    },
                    success: function (result) {
                        $(this).attr("disabled", false);
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
                                }
                            } else {
                                sessionStorage.setItem('openModal', 'true');
                                sessionStorage.setItem('mobile', result.data);
                                /*sessionStorage.setItem('user_type', result.data);*/
                                sessionStorage.setItem('redUrl', base);
                                const newAction = base.replace('/send-otp','');
                                window.location.href = `${newAction}`;
                            }
                        } else {
                            toastr.error(result.message);
                            $('#checkmodal').html('Submit');
                            $('#checkmodal').attr('disabled', false);
                        }
                    },
                    error: function (error) {
                        $(this).attr("disabled", false);
                        let errors = error.responseJSON.errors, errorsHtml = '';
                        $.each(errors, function (key, value) {
                            errorsHtml = '<strong>' + value[0] + '</strong>';
                            $('.' + key).html(errorsHtml);
                        });
                        $('#checkmodal').html('Submit');
                        $('#checkmodal').attr('disabled', false);
                    }
                });
            }
        });
    })
</script>
