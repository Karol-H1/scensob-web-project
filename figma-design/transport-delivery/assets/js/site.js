/* SCENSOB — interaction for the static build.
   Plain JavaScript, no dependencies, so the pages work straight from disk. */

(function () {
  'use strict';

  var CHECK_ICON =
    '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"' +
    ' fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"' +
    ' stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>';

  /* ------------------------------------------------------------------ *
   * Mobile navigation
   * ------------------------------------------------------------------ */
  var toggle = document.querySelector('.nav-toggle');
  var nav = document.getElementById('primary-nav');

  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      var open = nav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }

  /* ------------------------------------------------------------------ *
   * Hero enquiry card — switch between employer and candidate
   * ------------------------------------------------------------------ */
  var modeButtons = Array.prototype.slice.call(document.querySelectorAll('[data-mode]'));

  if (modeButtons.length) {
    var fieldSets = Array.prototype.slice.call(document.querySelectorAll('[data-mode-fields]'));
    var modeLabel = document.querySelector('[data-mode-label]');
    var LABELS = { employer: 'Request staff', candidate: 'Find a role' };

    modeButtons.forEach(function (button) {
      button.addEventListener('click', function () {
        var mode = button.dataset.mode;

        modeButtons.forEach(function (other) {
          other.setAttribute('aria-pressed', other === button ? 'true' : 'false');
        });

        fieldSets.forEach(function (set) {
          set.hidden = set.dataset.modeFields !== mode;
        });

        if (modeLabel) { modeLabel.textContent = LABELS[mode] || LABELS.employer; }
      });
    });
  }

  /* Hero card submit hands over to the full contact form. The form already
     points at contact.html, so this only tidies the URL by dropping the query
     string the browser would otherwise append. */
  var heroForm = document.querySelector('.enquiry-form');

  if (heroForm) {
    heroForm.addEventListener('submit', function (event) {
      event.preventDefault();
      window.location.href = 'contact.html';
    });
  }

  /* ------------------------------------------------------------------ *
   * Catalog sector filter
   * ------------------------------------------------------------------ */
  var filters = Array.prototype.slice.call(document.querySelectorAll('.filter'));
  var blocks = Array.prototype.slice.call(document.querySelectorAll('.sector-block'));

  if (filters.length && blocks.length) {
    filters.forEach(function (button) {
      button.addEventListener('click', function () {
        var wanted = button.dataset.sector;

        filters.forEach(function (other) {
          other.setAttribute('aria-pressed', other === button ? 'true' : 'false');
        });

        blocks.forEach(function (block) {
          block.hidden = !(wanted === 'all' || block.dataset.sector === wanted);
        });
      });
    });
  }

  /* ------------------------------------------------------------------ *
   * Contact — three-step enquiry
   * ------------------------------------------------------------------ */
  var card = document.getElementById('enquiry-card');

  if (card) {
    var TOTAL = 3;
    var panels = Array.prototype.slice.call(card.querySelectorAll('[data-step]'));
    var dots = Array.prototype.slice.call(card.querySelectorAll('[data-dot]'));
    var rules = Array.prototype.slice.call(card.querySelectorAll('[data-rule]'));
    var caption = card.querySelector('[data-step-caption]');
    var chipRow = card.querySelector('[data-selection-chips]');
    var form = card.querySelector('form[data-step="3"]');
    var grid = document.querySelector('[data-enquiry-grid]');
    var success = document.querySelector('[data-success]');
    var restart = document.querySelector('[data-restart]');

    var current = 1;
    var answers = { enquiry: null, sector: null };

    function paintSteps() {
      dots.forEach(function (dot) {
        var n = Number(dot.dataset.dot);
        dot.classList.toggle('is-done', n < current);
        dot.classList.toggle('is-current', n === current);
        dot.innerHTML = n < current ? CHECK_ICON : String(n);
      });

      rules.forEach(function (rule) {
        rule.classList.toggle('is-done', Number(rule.dataset.rule) < current);
      });

      if (caption) { caption.textContent = 'Step ' + current + ' of ' + TOTAL; }
    }

    function paintChips() {
      if (!chipRow) { return; }
      chipRow.innerHTML = '';

      [answers.enquiry, answers.sector].forEach(function (value) {
        if (!value) { return; }
        var chip = document.createElement('span');
        chip.className = 'sel-chip';
        chip.textContent = value;
        chipRow.appendChild(chip);
      });
    }

    function goTo(step) {
      current = Math.min(Math.max(step, 1), TOTAL);
      panels.forEach(function (panel) {
        panel.hidden = Number(panel.dataset.step) !== current;
      });
      paintSteps();
      if (current === TOTAL) { paintChips(); }
    }

    card.addEventListener('click', function (event) {
      var choice = event.target.closest('.choice');

      if (choice && card.contains(choice)) {
        var field = choice.dataset.field;
        answers[field] = choice.dataset.value;

        // Mark the chosen option within its own step only.
        var siblings = choice.parentElement.querySelectorAll('.choice');
        Array.prototype.forEach.call(siblings, function (other) {
          other.setAttribute('aria-pressed', other === choice ? 'true' : 'false');
        });

        goTo(current + 1);
        return;
      }

      var back = event.target.closest('[data-back]');
      if (back && card.contains(back)) {
        goTo(Number(back.dataset.back));
      }
    });

    if (form) {
      form.addEventListener('submit', function (event) {
        event.preventDefault();
        if (grid) { grid.hidden = true; }
        if (success) { success.hidden = false; }
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    }

    if (restart) {
      restart.addEventListener('click', function () {
        answers = { enquiry: null, sector: null };
        if (form) { form.reset(); }
        paintChips();
        Array.prototype.forEach.call(card.querySelectorAll('.choice'), function (choice) {
          choice.setAttribute('aria-pressed', 'false');
        });
        if (success) { success.hidden = true; }
        if (grid) { grid.hidden = false; }
        goTo(1);
      });
    }

    goTo(1);
  }
})();
