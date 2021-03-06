(function() {

  $(window).on('load', function () {
    $(document).foundation();
  });

  var sentShareForm = false;
  $("#share-form").submit(function() {
    sentShareForm = true;
    var state = $this.attr('data-state');
    var companySize = $this.attr('data-company-size')
    mixpanel.track('compartilhamento', {canal: 'email', estado: state, porte: companySize});
  });

  $("[data-share-channel]").click(function() {
    $this = $(this);
    var channel = $this.attr('data-share-channel');
    var state = $this.attr('data-state');
    var companySize = $this.attr('data-company-size')
    mixpanel.track('compartilhamento', {canal: channel, estado: state, porte: companySize});
  });

  [].slice.call(document.querySelectorAll('[data-progress]')).forEach(function(container) {
    var countdownText = $("#countdown-text");
    var graphLabel = $("#countdown-container");
    var restartButton = $("#restart-button");
    var absoluteProgress = parseInt(container.getAttribute('data-progress'));
    var remainingSteps = absoluteProgress;
    var progress = 1;

    var bar = new ProgressBar.Circle(container, {
      strokeWidth: 8,
      easing: 'easeInOut',
      duration: 500,
      color: '#FFFFFF',
      svgStyle: null
    });

    bar.animate(progress);

    function updateProgress() {
      countdownText.text(remainingSteps);
      bar.animate(progress);
    }

    var interval = setInterval(function() {
      remainingSteps -= 1;
      progress = remainingSteps / absoluteProgress;

      updateProgress()

      if (remainingSteps == 0) {
        clearInterval(interval);
        graphLabel.css('background', '#EE9770');
        if (!sentShareForm) {
          window.location.href = restartButton.attr('href');
        }
      }
    }, 1000);
  });

})();
