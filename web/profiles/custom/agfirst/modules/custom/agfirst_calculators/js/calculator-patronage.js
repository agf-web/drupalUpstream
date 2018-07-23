(function ($) {

  $(document).ready(function() {
    var patronagePercent = (window.agf_calc_patronage_percent || 19.42) / 100; /* 19.42% Patronage */
    /*var loanAmount = 100000;
    var interestRate = 10/100;*/
    $(document).on('click', '#btnSubmit', function(e) {
      e.preventDefault();
      var loanAmount = $('#tbxLoanAmount').val().replace(/,/g, '');
      var interestRate = $('#tbxAnnualRate').val() / 100;
      var patronageDistro = calcPatronageDistro(loanAmount, interestRate, patronagePercent);
      var effectiveInterestExpense = calcEffectiveInterestExpense(loanAmount, interestRate, patronageDistro);
      var effectiveInterestRate = calcEffectiveInterestRate(interestRate, patronagePercent);
      $('span[data-id="loan-amount"]').text(parseInt(loanAmount * interestRate, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
      $('span[data-id="annual-rate"]').text($('#tbxAnnualRate').val() + '%');
      $('span[data-id="estimated-patronage"]').text('$' + patronageDistro.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
      $('span[data-id="effective-interest-expense"]').text('$' + effectiveInterestExpense.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
      $('span[data-id="effective-interest-rate"]').text((effectiveInterestRate * 100).toFixed(2) + '%');
      $('.calculator-results').addClass('reveal');
    });
    $(document).on('click', '#clearForm', function(e) {
      $('.calculator-results').removeClass('reveal');
    });
  });
  function calcPatronageDistro(loanAmount, interestRate, patronagePercent) {
    var patronageDistro = (loanAmount * interestRate) * patronagePercent;
    return (patronageDistro);
  }
  function calcEffectiveInterestExpense(loanAmount, interestRate, patronageDistro) {
    var grossInterestExpense = loanAmount * interestRate;
    var effectiveInterestExpense = grossInterestExpense - patronageDistro;
    return (effectiveInterestExpense);
  }
  function calcEffectiveInterestRate(interestRate, patronagePercent) {
    var effectiveInterestRate = interestRate * (1 - patronagePercent);
    return (effectiveInterestRate);
  }

})(jQuery);
