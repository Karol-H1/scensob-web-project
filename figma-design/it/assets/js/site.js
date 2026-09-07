/* SCENSOB IT — interaction for the static build.
   Plain JavaScript, no dependencies, so the pages work straight from disk. */

(function () {
  'use strict';

  var SERVICE_LABEL = {
    staffing: 'IT Staffing',
    managed: 'Managed IT Services',
    cloud: 'Cloud & Infrastructure',
    security: 'Cybersecurity',
    software: 'Software & Digital'
  };

  /* ------------------------------------------------------------------ *
   * Mobile navigation
   * ------------------------------------------------------------------ */
  var toggle = document.querySelector('.nav-toggle');
  var panel = document.getElementById('mobile-nav');

  if (toggle && panel) {
    toggle.addEventListener('click', function () {
      var open = panel.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });

    Array.prototype.forEach.call(panel.querySelectorAll('a'), function (link) {
      link.addEventListener('click', function () {
        panel.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
      });
    });
  }

  /* ------------------------------------------------------------------ *
   * Home hero — quick enquiry hands off to the quote wizard
   * ------------------------------------------------------------------ */
  var heroForm = document.getElementById('hero-quote-form');

  if (heroForm) {
    heroForm.addEventListener('submit', function (event) {
      event.preventDefault();
      var service = document.getElementById('hero-service').value;
      window.location.href = 'quote.html' + (service ? '?service=' + encodeURIComponent(service) : '');
    });
  }

  /* ------------------------------------------------------------------ *
   * Portfolio — category filter + independent case study expand/collapse
   * ------------------------------------------------------------------ */
  var filters = Array.prototype.slice.call(document.querySelectorAll('.filter'));
  var projectCards = Array.prototype.slice.call(document.querySelectorAll('.project-card'));
  var countLabel = document.querySelector('[data-project-count]');

  if (filters.length && projectCards.length) {
    filters.forEach(function (button) {
      button.addEventListener('click', function () {
        var wanted = button.dataset.sector;

        filters.forEach(function (other) {
          other.setAttribute('aria-pressed', other === button ? 'true' : 'false');
        });

        var visible = 0;
        projectCards.forEach(function (card) {
          var show = wanted === 'all' || card.dataset.sector === wanted;
          card.hidden = !show;
          if (show) { visible += 1; }
        });

        if (countLabel) {
          countLabel.textContent = visible + (visible === 1 ? ' project' : ' projects');
        }
      });
    });
  }

  projectCards.forEach(function (card) {
    var toggleBtn = card.querySelector('[data-toggle]');
    var detail = card.querySelector('[data-detail]');
    if (!toggleBtn || !detail) { return; }

    toggleBtn.addEventListener('click', function () {
      var open = detail.hidden;
      detail.hidden = !open;
      toggleBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
      toggleBtn.firstChild.textContent = open ? 'Show less ' : 'Read case study ';
    });
  });

  /* ------------------------------------------------------------------ *
   * Quote wizard — four steps
   * ------------------------------------------------------------------ */
  var card = document.getElementById('wizard-card');

  if (card) {
    var TOTAL = 4;
    var panels = Array.prototype.slice.call(card.querySelectorAll('[data-step]'));
    var dots = Array.prototype.slice.call(card.querySelectorAll('[data-dot]'));
    var rules = Array.prototype.slice.call(card.querySelectorAll('[data-rule]'));
    var caption = card.querySelector('[data-step-caption]');
    var success = document.querySelector('[data-success]');
    var restart = document.querySelector('[data-restart]');
    var form4 = card.querySelector('form[data-step="4"]');

    var current = 1;
    var answers = { service: [], timeline: null, size: null, budget: null, brief: '' };

    function paintSteps() {
      dots.forEach(function (dot) {
        var n = Number(dot.dataset.dot);
        dot.classList.toggle('is-done', n < current);
        dot.classList.toggle('is-current', n === current);
      });
      rules.forEach(function (rule) {
        rule.classList.toggle('is-done', Number(rule.dataset.rule) < current);
      });
      if (caption) { caption.textContent = 'Step ' + current + ' of ' + TOTAL; }
    }

    function goTo(step) {
      current = Math.min(Math.max(step, 1), TOTAL);
      panels.forEach(function (panel) {
        panel.hidden = Number(panel.dataset.step) !== current;
      });
      paintSteps();
      if (current === 4) { paintSummary(); }
      card.scrollIntoView({ block: 'start', behavior: 'smooth' });
    }

    function summaryLine() {
      var parts = [];
      if (answers.timeline) { parts.push('Timeline: ' + answers.timeline); }
      if (answers.size) { parts.push('Size: ' + answers.size); }
      parts.push('Budget: ' + (answers.budget || 'Not specified'));
      return parts.join(' · ');
    }

    function paintSummary() {
      var chipRow = card.querySelector('[data-summary-services]');
      var line = card.querySelector('[data-summary-line]');
      if (chipRow) {
        chipRow.innerHTML = '';
        answers.service.forEach(function (value) {
          var chip = document.createElement('span');
          chip.className = 'summary-chip';
          chip.textContent = SERVICE_LABEL[value] || value;
          chipRow.appendChild(chip);
        });
      }
      if (line) { line.textContent = summaryLine(); }
    }

    /* Step 1 — multi-select service buttons */
    var serviceButtons = Array.prototype.slice.call(card.querySelectorAll('[data-field="service"]'));
    var next1 = card.querySelector('[data-step="1"] [data-next]');

    function setService(value, on) {
      var idx = answers.service.indexOf(value);
      if (on && idx === -1) { answers.service.push(value); }
      if (!on && idx !== -1) { answers.service.splice(idx, 1); }
      if (next1) { next1.disabled = answers.service.length === 0; }
    }

    serviceButtons.forEach(function (button) {
      button.addEventListener('click', function () {
        var pressed = button.getAttribute('aria-pressed') === 'true';
        button.setAttribute('aria-pressed', pressed ? 'false' : 'true');
        setService(button.dataset.value, !pressed);
      });
    });

    /* Step 2 — single-select pill groups */
    var pillGroups = {
      timeline: Array.prototype.slice.call(card.querySelectorAll('[data-field="timeline"]')),
      size: Array.prototype.slice.call(card.querySelectorAll('[data-field="size"]')),
      budget: Array.prototype.slice.call(card.querySelectorAll('[data-field="budget"]'))
    };
    var next2 = card.querySelector('[data-step="2"] [data-next]');

    function checkStep2() {
      if (next2) { next2.disabled = !(answers.timeline && answers.size); }
    }

    Object.keys(pillGroups).forEach(function (field) {
      pillGroups[field].forEach(function (pill) {
        pill.addEventListener('click', function () {
          pillGroups[field].forEach(function (other) {
            other.setAttribute('aria-pressed', other === pill ? 'true' : 'false');
          });
          answers[field] = pill.textContent;
          checkStep2();
        });
      });
    });

    /* Step 3 — free text brief */
    var textarea = card.querySelector('[data-brief]');
    if (textarea) {
      textarea.addEventListener('input', function () { answers.brief = textarea.value; });
    }

    /* Navigation */
    card.addEventListener('click', function (event) {
      var next = event.target.closest('[data-next]');
      if (next && card.contains(next) && !next.disabled) { goTo(current + 1); return; }

      var back = event.target.closest('[data-back]');
      if (back && card.contains(back)) { goTo(Number(back.dataset.back)); }
    });

    /* Step 4 — submit */
    if (form4) {
      var submitBtn4 = form4.querySelector('[type="submit"]');
      var errorNote4 = form4.querySelector('[data-form-error]');

      form4.addEventListener('submit', function (event) {
        event.preventDefault();
        if (!form4.checkValidity()) { form4.reportValidity(); return; }

        if (errorNote4) { errorNote4.hidden = true; }
        if (submitBtn4) { submitBtn4.disabled = true; }

        var name = form4.querySelector('#q-name').value.trim();
        var data = new FormData(form4);
        data.set('services', JSON.stringify(answers.service));
        data.set('timeline', answers.timeline || '');
        data.set('size', answers.size || '');
        data.set('budget', answers.budget || '');
        data.set('brief', answers.brief || '');

        fetch('submit.php', { method: 'POST', body: data })
          .then(function (res) {
            return res.json().catch(function () { return {}; }).then(function (body) {
              return { ok: res.ok && body.ok, body: body };
            });
          })
          .then(function (result) {
            if (!result.ok) { throw new Error(result.body.error || 'Submission failed'); }

            var finalChips = document.querySelector('[data-final-services]');
            var finalLine = document.querySelector('[data-final-line]');
            var thanks = document.querySelector('[data-success-thanks]');

            if (finalChips) {
              finalChips.innerHTML = '';
              answers.service.forEach(function (value) {
                var chip = document.createElement('span');
                chip.className = 'summary-chip';
                chip.textContent = SERVICE_LABEL[value] || value;
                finalChips.appendChild(chip);
              });
            }
            if (finalLine) { finalLine.textContent = summaryLine(); }
            if (thanks) {
              thanks.textContent = 'Thank you, ' + name + '. A member of our IT team will review your requirements and respond within 2 hours during business hours.';
            }

            card.hidden = true;
            if (success) { success.hidden = false; }
            window.scrollTo({ top: 0, behavior: 'smooth' });
          })
          .catch(function () {
            if (errorNote4) { errorNote4.hidden = false; }
          })
          .then(function () {
            if (submitBtn4) { submitBtn4.disabled = false; }
          });
      });
    }

    if (restart) {
      restart.addEventListener('click', function () {
        answers = { service: [], timeline: null, size: null, budget: null, brief: '' };
        serviceButtons.forEach(function (b) { b.setAttribute('aria-pressed', 'false'); });
        Object.keys(pillGroups).forEach(function (field) {
          pillGroups[field].forEach(function (p) { p.setAttribute('aria-pressed', 'false'); });
        });
        if (textarea) { textarea.value = ''; }
        if (form4) { form4.reset(); }
        if (next1) { next1.disabled = true; }
        checkStep2();
        card.hidden = false;
        if (success) { success.hidden = true; }
        goTo(1);
      });
    }

    goTo(1);

    /* Arriving from the home hero or a services CTA: pre-select the service */
    var requested = (window.location.search.match(/[?&]service=([^&]+)/) || [])[1];
    if (requested) {
      var match = serviceButtons.filter(function (b) { return b.dataset.value === decodeURIComponent(requested); })[0];
      if (match) { match.click(); }
    }
  }
})();
