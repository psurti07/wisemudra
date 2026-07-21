<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Slot</title>
    <link rel="shortcut icon" href="{{ asset('front/images/logo/favicon.ico') }}" title="Favicon" sizes="16x16" />
    <link rel="icon" href="{{ asset('front/images/logo/favicon.ico') }}" type="image/x-icon" />
    <link rel="shortcut icon" href="{{asset('front/images/logo/favicon.ico')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{asset('front/images/logo/favicon.ico')}}" type="image/x-icon">
    <link rel="icon" href="{{ asset('front/images/logo/favicon.ico') }}" type="image/x-icon" />
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('front/images/logo/apple-touch-icon-152x152.png') }}" />
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('front/images/logo/apple-touch-icon-120x120.png') }}" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('front/images/logo/apple-touch-icon-76x76.png') }}" />
    <link rel="apple-touch-icon" href="{{ asset('front/images/logo/apple-touch-icon-60x60.png') }}" />
    <link rel="icon" href="{{ asset('front/images/logo/main-favicon-180x180.png') }}" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.3/toastr.min.css" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "DM Sans", sans-serif;
        }

        .main {
            max-width: 420px;
            margin: auto;
            background: #fff;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            padding: 15px;
        }

        .header h1 {
            font-weight: 600;
            font-size: 24px;
            color: #0a8b4b;
        }

        .header {
            display: flex;
            justify-content: space-between;
            padding: 0;
            align-items: center;
            margin-bottom: 30px;
            margin-top: 35px;
        }

        .banner-main {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #ebf9f5;
            margin-bottom: 20px;
            padding: 0;
            border-radius: 12px;
            border: 1px solid #0a8b4b;
        }

        .banner-main .banner-text {
            padding: 17px 0 0 17px;

            text-align: start;
        }

        .banner-main .banner-text .fa {
            font-size: 20px;
        }

        .fa {
            font-size: 20px;
            margin-right: 5px;
        }

        .banner-main img {
            width: 150px;
            height: 120px;
        }

        .banner-main .banner-text h2 {
            font-size: 16px;
            line-height: 25px;
            color: #212529;
            font-weight: 400;
            margin-top: 15px;

        }

        .banner-main .banner-text .tag {
            background: #0a8b4b;
            color: #fff;
            padding: 7px 17px;
            border-radius: 6px;
            font-size: 12px;
        }

        .banner-main .banner-text .tag svg {
            vertical-align: middle;
        }

        p {
            font-size: 12px;
            line-height: 24px;
            color: #212529;
            margin-top: 0;
            margin-bottom: 0;
        }

        #form h4 {
            font-size: 15px;
            line-height: 20px;
            margin: 10px 0 5px 0;
            font-weight: 600;
            color: #2E2E2E;

        }

        #form .select-languge {
            margin-bottom: 35px;
        }

        #form .tabs-main {
            display: flex;
            gap: 7px;
            flex-wrap: wrap;
            margin-top: 16px;
        }

        #form .tabs-main .item {
            padding: 6px 6px;
            border: 1px solid #0a8b4b;
            border-radius: 6px;
            cursor: pointer;
            width: 22.3%;
            text-align: center;
        }

        #slots .item {
            width: 31.96% !important;
        }

        #form .tabs-main .item.tabs-item {
            width: 32%;
        }

        #form .active {
            background: #ebf9f5;
            color: #212529;
        }

        #form .tabs {
            display: flex;
            margin-top: 10px;
            background: #f3f3f3;
            margin-bottom: 20px;
        }

        /* #form .tab {
            flex: 1;
            text-align: center;
            padding: 8px;
            border: none;
            cursor: pointer;
        }

        #form .tab.active {
            background: #144835;
            color: #fff;
            border: 1px solid #144835;
            border-radius: 9px;
        } */

        #form .tabs-main .item.active {
            background: #ebf9f5;
        }

        .footer {
            text-align: center;
            border-top: 1px solid #e4e4e7;
            position: sticky;
            bottom: 0;
            z-index: 999;
            background: #fff;
        }

        .footer button.btn-submit {
            min-width: 290px;
            padding: 16px;
            margin-top: 20px;
            background: #5d3693;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 20px;
            text-transform: uppercase;
            margin: auto;
            margin-top: 15px;
            margin-bottom: 15px;
        }

        .footer button.btn-submit:hover {
            background-color: #0a8b4b;
        }

        .footer .item {
            text-align: center;
            line-height: 1.4;
        }

        .box-agent {
            background: #ebf9f5;
            border-radius: 12px;
            display: flex;
            gap: 15px;
            align-items: center;
            margin-bottom: 60px;
            padding: 25px 10px;
            border: 1px solid #0a8b4b;
        }

        .box-agent .agent-img-list .agent-img-item {
            height: 50px;
            width: 50px;
            border: 1px solid #0a8b4b;
            border-radius: 50px;
            padding: 4px;
            background-color: #fff;
            margin-right: -15px;
            text-align: center;
        }

        .box-agent .agent-img-list {
            display: flex;
            align-items: center;
            padding-left: 0;
        }

        .box-agent .agent-img-list li {
            list-style: none;
        }

        .box-agent .agent-img-list .agent-img-item img {
            width: 100%;
            border-radius: 50%;
        }

        .box-agent span {
            font-size: 22px;
            padding-left: 10px;
            color: #0a8b4b;

        }

        .slot-days {
            font-weight: 600;
            font-size: 15px;
            line-height: 24px;
            margin-bottom: 8px;
        }

        .slot-date {
            font-weight: normal;
            font-size: 13px;
            line-height: 12px;
            margin-top: 5px;
            color: #585d69;
        }

        @media screen and (max-width:767px) {
            .slot-days {
                font-size: 13px;
            }
        }
    </style>
</head>

<body>
    <form id="form" action="{{ route('schedule-slot') }}" method="POST">
        @csrf
        <input type="hidden" id="language" name="language" value="1">
        <input type="hidden" id="date" name="date">
        <input type="hidden" id="time" name="time">
        <input type="hidden" id="user_id" name="user_id" value="{{ $user->id }}">
        <div class="main">
            <div class="container">
                <div class="header">
                    <h1>Hi, {{ $user->first_name }} 👋</h1>
                    <span class="phone"><i class="fa fa-mobile-alt"></i>{{ $user->mobile }}</span>
                </div>
                <div class="banner-main">
                    <div class="banner-text">
                        <span class="tag"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-telephone" viewBox="0 0 16 16">
                                <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.6 17.6 0 0 0 4.168 6.608 17.6 17.6 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.68.68 0 0 0-.58-.122l-2.19.547a1.75 1.75 0 0 1-1.657-.459L5.482 8.062a1.75 1.75 0 0 1-.46-1.657l.548-2.19a.68.68 0 0 0-.122-.58zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z" />
                            </svg> FREE CALL</span>
                        <h2>Begin Your Success Journey With Expert Support</h2>
                    </div>
                    <img src="{{ asset('front/webinar/images/webinarpage/img-2.png') }}" alt="expert">
                </div>
                <!-- Language Tabs -->
                <div class="select-languge">
                    <h4>Choose Preferred Language</h4>
                    <p>We will try to arrange your call in your preferred language</p>
                    <div class="tabs-main" id="languageTabs">
                        <div class="item active" data-value="2">English</div>
                        <div class="item" data-value="1">Hindi</div>
                        <div class="item" data-value="3">Gujarati</div>
                    </div>
                </div>
                <!-- Date -->

                <div class="select-languge">
                    <h4>Choose Date</h4>
                    <div class="tabs-main" id="dates"></div>
                </div>

                <!-- Time -->
                <div class="select-languge">
                    <h4>Choose Time Slot</h4>
                    {{-- <div class="tabs">
                        <div class="tab active" data-type="morning">Morning</div>
                        <div class="tab" data-type="afternoon">Afternoon</div>
                        <div class="tab" data-type="evening">Evening</div>
                    </div> --}}
                    <div class="tabs-main" id="slots"></div>
                    <div id="noSlotsMessage" style="display:none; color:#6c757d;font-size:12px;text-align:center;">
                        No slots available for this date. Please select another date.
                    </div>
                </div>
                <div class="select-languge">
                    <div class="box-agent">
                        <ul class="agent-img-list">
                            <li class="agent-img-item">
                                <img src="{{ asset('front/webinar/images/webinarpage/model-1.jpeg') }}" alt="">
                            </li>
                            <li class="agent-img-item">
                                <img src="{{ asset('front/webinar/images/webinarpage/model-2.jpeg') }}" alt="">
                            </li>
                            <li class="agent-img-item">
                                <img src="{{ asset('front/webinar/images/webinarpage/model-3.jpeg') }}" alt="">
                            </li>

                        </ul>
                        <span>
                            45+
                        </span>
                        <div class="rate">
                            <p>
                                Projects Delivered Successfully – Let's Get You Started Now!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer">
            <div class="">
                <button type="submit" class="btn-submit" id="bookSlot">Book Your Slot</button>
            </div>
        </footer>
    </form>

    <script type="text/javascript" src="{{ asset('front/webinar/js/jquery.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.3/toastr.min.js"></script>
    <script>
        let selectedDate = null;

        // ------------------ LANGUAGE ------------------
        document.querySelectorAll('#languageTabs .item').forEach(el => {
            el.onclick = () => {
                document.querySelectorAll('#languageTabs .item').forEach(i => i.classList.remove('active'));
                el.classList.add('active');
                document.getElementById('language').value = el.dataset.value;
            };
        });

        function isToday(date) {
            let now = new Date();
            return date.toDateString() === now.toDateString();
        }

        // function setTab(type) {
        //     document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        //     document.querySelector(`.tab[data-type="${type}"]`).classList.add('active');
        //     generateSlots(type);
        // }

        // function getDefaultTabByTime() {
        //     let now = new Date();
        //     let hour = now.getHours() + now.getMinutes() / 60;

        //     if (hour < 11.75) return 'morning';
        //     if (hour < 15.75) return 'afternoon';
        //     return 'evening';
        // }

        // ------------------ DATES ------------------
        function generateDates() {
            let count = 0;
            let i = 0;
            let isFirst = true; // 👈 track first selectable date

            const today = new Date();

            while (count < 4) {
                let d = new Date();
                d.setDate(d.getDate() + i);

                if (d.getDay() !== 0 && d.getDay() !== 6) { // skip Sunday and saturday

                    let line1 = '';
                    let day = d.getDate();
                    let month = d.toLocaleString('en-US', {
                        month: 'short'
                    }).toUpperCase();
                    let line2 = `${day} ${month}`;

                    let diffDays = Math.floor((d - today) / (1000 * 60 * 60 * 24));

                    if (diffDays === 0) {
                        line1 = '<span class="slot-days">Today</span>';
                    } else if (diffDays === 1) {
                        line1 = '<span class="slot-days">Tomorrow</span>';
                    } else {
                        line1 = '<span class="slot-days">' + d.toLocaleString('en-US', {
                            weekday: 'short'
                        }) + '</span>';
                    }

                    let div = document.createElement('div');
                    div.className = 'item';
                    div.innerHTML = `${line1}<br><div class="slot-date">${line2}</div>`;

                    div.onclick = () => {
                        document.querySelectorAll('#dates .item').forEach(e => e.classList.remove('active'));
                        div.classList.add('active');

                        selectedDate = new Date(d);
                        document.getElementById('date').value = selectedDate.toISOString().split('T')[0];
                        generateSlots();
                        // if (isToday(selectedDate)) {
                        //     setTab(getDefaultTabByTime());
                        // } else {
                        //     setTab('morning');
                        // }
                    };

                    document.getElementById('dates').appendChild(div);

                    // 🔥 Auto select first date (Today)
                    if (isFirst) {
                        div.click();
                        isFirst = false;
                    }

                    count++;
                }
                i++;
            }
        }

        document.querySelectorAll('#slots .item').forEach(el => {
            el.onclick = () => {
                document.querySelectorAll('#slots .item').forEach(i => i.classList.remove('active'));
                el.classList.add('active');
                document.getElementById('time').value = el.dataset.value;
            };
        });

        // ------------------ TIME SLOTS ------------------
        // function getActiveTab() {
        //     return document.querySelector('.tab.active').dataset.type;
        // }

        function generateSlots() {
            const container = document.getElementById('slots');
            container.innerHTML = '';
            const noSlotsMessage = document.getElementById('noSlotsMessage');
            noSlotsMessage.style.display = 'none';

            if (!selectedDate) return;

            // 🔥 Fixed time slots (24-hour format for logic)
            const slots = [
                "11:00", "11:30", "12:00", "12:30", "13:00", "14:00", "14:30", "15:00", "15:30", "16:00"
            ];

            let now = new Date();
            let nearestSlot = null;
            let minDiff = Infinity;

            slots.forEach(time => {
                let [h, m] = time.split(':').map(Number);

                let slotDate = new Date(selectedDate);
                slotDate.setHours(h, m, 0, 0);

                // 🔥 Skip past slots only for TODAY
                if (isToday(selectedDate) && slotDate <= now) {
                    return;
                }

                // Convert to AM/PM
                let hour12 = h % 12 || 12;
                let ampm = h < 12 ? 'AM' : 'PM';

                let formatted = `${hour12}:${String(m).padStart(2, '0')} ${ampm}`;

                let div = document.createElement('div');
                div.className = 'item';
                div.innerText = formatted;
                div.dataset.value = time; // store original value

                div.onclick = () => {
                    document.querySelectorAll('#slots .item').forEach(s => s.classList.remove('active'));
                    div.classList.add('active');
                    document.getElementById('time').value = time; // store 24h format
                };

                container.appendChild(div);

                // 🔥 Find nearest slot (only for today)
                if (isToday(selectedDate)) {
                    let diff = slotDate - now;
                    if (diff > 0 && diff < minDiff) {
                        minDiff = diff;
                        nearestSlot = div;
                    }
                }
            });

            // 🔥 AUTO SELECT
            if (isToday(selectedDate)) {
                if (nearestSlot) {
                    nearestSlot.click();
                }
            } else {
                let firstSlot = container.querySelector('.item');
                if (firstSlot) firstSlot.click();
            }

            // 🔥 SHOW MESSAGE IF EMPTY
            if (container.children.length === 0) {
                noSlotsMessage.style.display = 'block';
                document.getElementById('time').value = '';
            }
        }

        // ------------------ TABS ------------------
        // document.querySelectorAll('.tab').forEach(tab => {
        //     tab.onclick = () => {
        //         document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        //         tab.classList.add('active');
        //         generateSlots(tab.dataset.type);
        //     };
        // });

        // ------------------ SUBMIT ------------------
        document.addEventListener('DOMContentLoaded', function() {

            const bookSlot = document.getElementById('bookSlot');
            const form = document.getElementById('form');

            if (!bookSlot || !form) return;

            bookSlot.addEventListener('click', async function(event) {
                event.preventDefault();

                let data = {
                    language: document.getElementById('language').value,
                    date: document.getElementById('date').value,
                    time: document.getElementById('time').value
                };

                if (!data.language || !data.date || !data.time) {
                    toastr.warning('Please select language, Date and Time');
                    return;
                }

                const formData = new FormData(form);
                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const result = await response.json();

                    if (result.status === 422) {
                        toastr.error('Please select language, Date or Time');
                        return; // 👈 stop, don't run success
                    }

                    toastr.success(result.message);

                    if (result?.redirect) {
                        setTimeout(() => { // 👈 wait before redirect
                            window.location.href = result.redirect;
                        }, 1500);
                    }

                } catch (error) {
                    toastr.error('An error occurred. Please try again.');
                }
            });
        });

        // INIT
        generateDates();
    </script>

</body>

</html>