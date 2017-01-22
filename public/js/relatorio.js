(function() {

  $(window).on('load', function () {
    $(document).foundation();
  });



  $("[data-share-channel]").click(function() {
    $this = $(this);
    var channel = $this.attr('data-share-channel');
    var state = $this.attr('data-state');
    var companySize = $this.attr('data-company-size');
    mixpanel.track('compartilhamento', {canal: channel, estado: state, porte: companySize});
  });

  [].slice.call(document.querySelectorAll('[data-progress]')).forEach(function(container) {

    var progress = parseInt(container.getAttribute('data-progress')) / 100.0;
    var color = container.getAttribute('data-highest') ? '#EE9770' : '#FFFFFF';
    var bar = new ProgressBar.Circle(container, {
      strokeWidth: 50,
      easing: 'easeInOut',
      duration: 1400,
      color: color
    });

    bar.animate(progress);
  });

  $("#share-email").click(function() {
    $("#share-modal").toggle()
  });

  $("#share-form").submit(function(e) {
    e.preventDefault();

    $("#share-form-container").toggle();
    $("#share-form-load").toggle();

    var $this = $(this);
    var state = $this.attr('data-state');
    var companySize = $this.attr('data-company-size')
    mixpanel.track('compartilhamento', {canal: 'email', estado: state, porte: companySize});

    $.ajax({
      url: '/share',
      type: 'POST',
      data: $(this).serialize()
    }).always(function() {
      $("#share-form-email-sent").toggle().delay(2000).slideUp();
      $(".share-email-field").val('');
      $("#share-form-container").toggle();
      $("#share-form-load").toggle();
    });
  });


})();
