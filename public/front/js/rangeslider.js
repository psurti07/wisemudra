    (function ($) {
    $(function () {


        $("input[type=range]").rangeslider({polyfill: false});

        var s = $("#clients-attracted"),
            r = $("#daily-orders-per-client"),
            p = $("#agent-result"),
            o = $("#agent-commission"),
            t = $("#totat-payable-ag");

        function formatIndianCurrency(num) {
            return num.toLocaleString('en-IN');
        }

        function agFormula() {
            let mi = o.val() / (12 * 100);
            let amt = parseInt(s.val() * mi * Math.pow(1 + mi, r.val()) / (Math.pow(1 + mi, r.val()) - 1));
            let totalPayable = amt * parseInt(r.val());
            p.html(`<span class="ag-calculator_results-currency">&#8377;</span> ${formatIndianCurrency(amt)}`);
            t.html(formatIndianCurrency(totalPayable));
        }

        $(".js-calculator_input-wrap > .js-calculator_text-input").on("change input", function () {
            $(this).parent().find(".js-calculator_range").val($(this).val()).change();
            agFormula();
        });

        $(".js-calculator_input-wrap > .js-calculator_range").on("change input", function () {
            $(this).parent().find(".js-calculator_text-input").val($(this).val());
            agFormula();
        });
        agFormula();

    });
})(jQuery);
