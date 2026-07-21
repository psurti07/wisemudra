<div class="modal fade" id="cipherPayQr" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <style>
                body {
                    background-color: #f8f9fa;
                }
                .card {
                    border: none;
                    border-radius: 10px;
                    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
                }
                .card-header {
                    background-image: linear-gradient(254.18deg, rgb(20 74 121) 0.51%, rgb(1 27 35) 70.03%);
                    color: #fff;
                    border-radius: 10px 10px 0 0;
                    padding: 1.5rem;
                    display:flex;
                    align-items:center;
                }
                .header-logo img {
                    max-width: 70px; /* Adjust logo size */
                    margin-right: 15px;
                }
                .header-content h5, .header-content p {
                    margin: 0;
                }
                .qr-section {
                    text-align: center;
                    margin: 20px 0;
                }
                .powered-upi {
                    font-size: 12px;
                    color: gray;
                    text-align: center;
                    margin-top: 10px;
                }
                #countdown {
                    font-size: 1.2rem;
                    font-weight: bold;
                    text-align: center;
                    margin-top: 0px;
                    color: red !important;
                }
            </style>
            <div class="modal-header">
                <div class="header-logo">
                    <img src="https://cipherpay.in/assets/images/favicon.ico" alt="Cipherpay">
                </div>
                <!-- Content on the right -->
                <div class="header-content">
                    <h5 class="mb-0">{{ env('COMPANY_NAME') }}</h5>
                    <p class="mb-0">#{{ $response['data']['txnid'] }}</p>
                    <p class="mb-0 fw-bold">&#8377;{{ $response['data']['amount'] }}</p>    
                </div>
            </div>
            <div class="modal-body">
                <div class="row p-3">
                    <div class="text-center">
                        <h6 class="mb-3">UPI/QR CODE</h6>
                        <p>Scan the QR using any UPI app on your phone.</p>
                        <!-- UPI App Icons -->
                        <div class="upi-icons mb-3">
                            <img src="{{ asset('front/images/upiapp.png') }}" alt="BHIM">
                        </div>
                        <!-- QR Code -->
                        <div class="qr-section" id="qr-section">
                            <div id="qr-code">{!! QrCode::size(200)->generate($response['qr']) !!}</div>
                        </div>
                        <div id="countdown">
                            <span id="minutes"></span>:<span id="seconds"></span>
                        </div>
                        <!-- Reload Button -->
                        <!--<button id="reload-btn" style="display: none;">Reload QR</button>-->
                        <!-- Powered by UPI -->
                        <div class="powered-upi">
                            <p>Powered by <strong>{{ env('APP_NAME') }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    /*$(document).ready(function () {
        // Set initial countdown time (e.g., 20 minutes = 1200 seconds)
        let countdownTime = {{ $response['data']['expiry'] }} * 60; // 20 minutes in seconds
        
        // Countdown function that updates every second
        function updateCountdown() {
            const minutes = Math.floor(countdownTime / 60);
            const seconds = countdownTime % 60;
    
            // Format the time to always display two digits
            $("#minutes").text(minutes < 10 ? "0" + minutes : minutes);
            $("#seconds").text(seconds < 10 ? "0" + seconds : seconds);
    
            if (countdownTime <= 0) {
                $("#countdown").text("Time's up!");
                clearInterval(countdownInterval); // Stop the countdown if time's up
                // Redirect to the specified URL
                window.location.href = "https://wisemudra.com/";
                return;
            }
    
            countdownTime--;
        }
        
         // Callback function to check response every 15 seconds
        function checkResponse() {
            $.ajax({
                url: "https://wisemudra.com/status-enquiry", // Replace with your API endpoint
                method: "GET", // Or POST, depending on your API
                dataType: "json",
                success: function (response) {
                    // Handle response
                    if (response.data.data.status === 1) {
                        window.location.href = response.redirectUrl;
                        // You can perform additional actions if the response is true
                    } else {
                        console.log("Transaction under process");
                        // You can perform additional actions if the response is false
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error in API call:", error);
                }
            });
        }

        
        // Start the countdown immediately
        updateCountdown();
        const countdownInterval = setInterval(updateCountdown, 1000); // Update countdown every 1 second
        
        // Start the callback function every 15 seconds
        const callbackInterval = setInterval(checkResponse, 15000); // Check response every 15 seconds
    });*/
</script>
