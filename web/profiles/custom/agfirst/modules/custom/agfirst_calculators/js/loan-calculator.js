(function ($) {

    function getDataSet() {
        var output = {};
        var loanAmount = output.loanAmount = parseFloat($('#loanAmount').val());
        var interestRate = output.interestRate = parseFloat($('#interestRate').val());
        var paymentsPerYear = output.paymentsPerYear = parseInt($('#paymentsPerYear').val());
        var years = output.years = parseInt($('#years').val());
        var numberOfPayments = output.numberOfPayments = paymentsPerYear * years;

        var payment = output.payment = pmt(interestRate / 100 / paymentsPerYear, numberOfPayments, -loanAmount);
        $("#loanAmount").keydown(function (e) {
            if (e.keyCode === 188) {
                e.preventDefault();
            }
        });

        output.schedule = computeSchedule(loanAmount,
            interestRate,
            paymentsPerYear,
            years,
            payment);
        return output;

    }


    function reloadTable(ds) {
        // map the schedule to 2 digits after decimal point.
        var schedule = ds.schedule.map(function (n) {
            return [n[0], n[1].toFixed(2), n[2].toFixed(2), n[3].toFixed(2)];
        });

        $('#schedule').empty();
        $('#schedule').html('<table cellpadding="0" cellspacing="0" border="0" class="display table" id="schedule_table"></table>');
        $('#schedule_table').dataTable({
            "data": schedule,
            "searching": false,
            "columns": [
                {"title": "Period"},
                {"title": "Principle"},
                {"title": "Interest"},
                {"title": "Remaining"}
            ],
            "search": false,
            "paging": false,
            "ordering": false,
            "info": false
        });
    }

    function reload() {
        var ds = getDataSet();

        $('#paymentAmount').text('$' + ds.payment.toFixed(2));
        reloadTable(ds);
    }


    $(document).on('keyup', '.user-input', reload);

    $(document).ready(function () {
        reload();
    });

})(jQuery);
