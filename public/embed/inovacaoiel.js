(function(window) {
  'use strict';

  if (window.__inovacao_iel_script_included__) {
    console.warn("Oops! Script inserido mais de uma vez. Certifique-se de incluir o embed apenas uma vez.");
    return;
  }

  window.__inovacao_iel_script_included__ = true;

  var selfScript = document.getElementById('iel-embed-script');

  if (selfScript == null) {
    console.error("Oops! Não encontrei o script com id='iel-embed-script', tente adicionar para que o embed funcione corretamente.");
    return;
  }

  var parser = document.createElement('a');
  parser.href = selfScript.src;
  var splitedPath = parser.pathname.split('/');
  splitedPath.pop();
  var path = splitedPath.join('/');
  var selfUrl = parser.protocol + "//" + parser.host + path;

  function appendFormToBody() {
    var html = ' \
      <div id="__iel-wrapper" style="display: none"> \
        <div id="__iel-frame-container"> \
          <iframe src="' + selfUrl + '/../" \
        </div> \
      </div> \
    ';

    var div = document.createElement('div');
    div.innerHTML = html;
    document.body.appendChild(div);
  }

  function appendCssToHead() {
    var link = document.createElement('link');
    link.setAttribute('rel', 'stylesheet');
    link.setAttribute('type', 'text/css');
    // TODO: Change
    link.setAttribute('href', selfUrl + '/inovacaoiel.css');
    document.getElementsByTagName('head')[0].appendChild(link);
  }

  function onLinkClick() {
    var wrapper = document.getElementById('__iel-wrapper');
    wrapper.classList = '__iel-wrapper--open';
  }

  function bindFormLinks() {
    [].slice.call(document.querySelectorAll('[data-iel-form-toogle]')).forEach(function(element) {
      if (element.onclick) {
        var currentOnClick = element.onclick;
        element.onclick = function(evt) {
          currentOnClick(evt);
          onLinkClick(evt);
        };
      } else {
        element.onclick = onLinkClick;
      }
    });
  }

  function onPageLoad() {
    appendCssToHead();
    appendFormToBody();
    bindFormLinks();
  }

    console.log(selfUrl)
  if (window.attachEvent) {
    window.attachEvent('onload', onPageLoad);
  } else {
      if (window.onload) {
          var currentOnload = window.onload;
          window.onload = function(evt) {
              currentOnload(evt);
              onPageLoad(evt);
          };
      } else {
          window.onload = onPageLoad;
      }
  }
})(window);
