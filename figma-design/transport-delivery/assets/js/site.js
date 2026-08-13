/* SCENSOB — interaction for the static build.
   Plain JavaScript, no dependencies, so the pages work straight from disk. */

(function () {
  'use strict';

  var CHECK_ICON =
    '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"' +
    ' fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"' +
    ' stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>';

  /* Which follow-up each opening answer leads to, and which extra fields the
     details step then asks for. */
  var BRANCH_OF = {
    'I need to hire staff': 'hire',
    "I'm looking for a job": 'job',
    'GDP / compliance query': 'query',
    'General enquiry': 'query'
  };

  var PANEL_OF = { hire: 'sector', job: 'sector', query: 'urgency' };

  /* The hero card hands over with ?intent=, which maps back to a step 1 answer. */
  var ANSWER_OF_INTENT = {
    hire: 'I need to hire staff',
    job: "I'm looking for a job"
  };

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
   * Hero enquiry card — employer / candidate, then hand off to contact
   * ------------------------------------------------------------------ */
  var modeButtons = Array.prototype.slice.call(document.querySelectorAll('[data-mode]'));
  var heroMode = 'employer';

  if (modeButtons.length) {
    var fieldSets = Array.prototype.slice.call(document.querySelectorAll('[data-mode-fields]'));
    var modeLabel = document.querySelector('[data-mode-label]');
    var LABELS = { employer: 'Request staff', candidate: 'Find a role' };

    modeButtons.forEach(function (button) {
      button.addEventListener('click', function () {
        heroMode = button.dataset.mode;

        modeButtons.forEach(function (other) {
          other.setAttribute('aria-pressed', other === button ? 'true' : 'false');
        });

        fieldSets.forEach(function (set) {
          set.hidden = set.dataset.modeFields !== heroMode;
        });

        if (modeLabel) { modeLabel.textContent = LABELS[heroMode] || LABELS.employer; }
      });
    });
  }

  var heroForm = document.querySelector('.enquiry-form');

  if (heroForm) {
    heroForm.addEventListener('submit', function (event) {
      event.preventDefault();
      var intent = heroMode === 'candidate' ? 'job' : 'hire';
      window.location.href = 'contact.html?intent=' + intent;
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
    var branchPanels = Array.prototype.slice.call(card.querySelectorAll('[data-branch-panel]'));
    var dots = Array.prototype.slice.call(card.querySelectorAll('[data-dot]'));
    var rules = Array.prototype.slice.call(card.querySelectorAll('[data-rule]'));
    var caption = card.querySelector('[data-step-caption]');
    var chipRow = card.querySelector('[data-selection-chips]');
    var form = card.querySelector('form[data-step="3"]');
    var conditionals = form
      ? Array.prototype.slice.call(form.querySelectorAll('[data-when]'))
      : [];
    var grid = document.querySelector('[data-enquiry-grid]');
    var success = document.querySelector('[data-success]');
    var restart = document.querySelector('[data-restart]');

    var current = 1;
    var branch = null;
    var answers = { enquiry: null, detail: null };

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

      [answers.enquiry, answers.detail].forEach(function (value) {
        if (!value) { return; }
        var chip = document.createElement('span');
        chip.className = 'sel-chip';
        chip.textContent = value;
        chipRow.appendChild(chip);
      });
    }

    /* Show only the follow-up question this branch asks. */
    function paintBranchPanels() {
      var wanted = PANEL_OF[branch] || 'sector';
      branchPanels.forEach(function (panel) {
        panel.hidden = panel.dataset.branchPanel !== wanted;
      });
    }

    /* Show only the details fields this branch needs, and disable the rest so
       they stay out of the submitted data. */
    function paintFields() {
      conditionals.forEach(function (el) {
        var show = el.dataset.when === branch;
        el.hidden = !show;

        if (el.matches('input, textarea, select')) { el.disabled = !show; }
        Array.prototype.forEach.call(
          el.querySelectorAll('input, textarea, select'),
          function (input) { input.disabled = !show; }
        );
      });
    }

    function clearPressed(scope) {
      Array.prototype.forEach.call(scope.querySelectorAll('.choice'), function (choice) {
        choice.setAttribute('aria-pressed', 'false');
      });
    }

    function goTo(step) {
      current = Math.min(Math.max(step, 1), TOTAL);
      panels.forEach(function (panel) {
        panel.hidden = Number(panel.dataset.step) !== current;
      });
      paintSteps();
      if (current === 2) { paintBranchPanels(); }
      if (current === TOTAL) { paintFields(); paintChips(); }
    }

    function choose(choice) {
      var field = choice.dataset.field;
      answers[field] = choice.dataset.value;

      if (field === 'enquiry') {
        branch = BRANCH_OF[choice.dataset.value] || 'query';
        // The follow-up question changes with the branch, so any answer to the
        // previous one no longer applies.
        answers.detail = null;
        branchPanels.forEach(clearPressed);
      }

      var group = choice.parentElement;
      Array.prototype.forEach.call(group.querySelectorAll('.choice'), function (other) {
        other.setAttribute('aria-pressed', other === choice ? 'true' : 'false');
      });

      goTo(current + 1);
    }

    card.addEventListener('click', function (event) {
      var choice = event.target.closest('.choice');
      if (choice && card.contains(choice)) { choose(choice); return; }

      var back = event.target.closest('[data-back]');
      if (back && card.contains(back)) { goTo(Number(back.dataset.back)); }
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
        answers = { enquiry: null, detail: null };
        branch = null;
        if (form) { form.reset(); }
        paintChips();
        clearPressed(card);
        if (success) { success.hidden = true; }
        if (grid) { grid.hidden = false; }
        goTo(1);
      });
    }

    goTo(1);

    /* Arriving from the hero card: adopt its answer and open on step 2. */
    var intent = (window.location.search.match(/[?&]intent=([^&]+)/) || [])[1];
    var preset = intent && ANSWER_OF_INTENT[decodeURIComponent(intent)];

    if (preset) {
      var opening = Array.prototype.slice.call(
        card.querySelectorAll('.choice[data-field="enquiry"]')
      ).filter(function (choice) { return choice.dataset.value === preset; })[0];

      if (opening) { choose(opening); }
    }
  }
})();
