/* SCENSOB Group — interaction for the static build.
   Plain JavaScript, no dependencies, so the pages work straight from disk. */

(function () {
  'use strict';

  /* ------------------------------------------------------------------ *
   * Mobile navigation
   * ------------------------------------------------------------------ */
  var navToggle = document.querySelector('.nav-toggle');
  var mobileNav = document.getElementById('mobile-nav');

  if (navToggle && mobileNav) {
    navToggle.addEventListener('click', function () {
      var open = mobileNav.classList.toggle('is-open');
      navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });

    Array.prototype.forEach.call(mobileNav.querySelectorAll('a'), function (link) {
      link.addEventListener('click', function () {
        mobileNav.classList.remove('is-open');
        navToggle.setAttribute('aria-expanded', 'false');
      });
    });
  }

  /* ------------------------------------------------------------------ *
   * Portfolio — filter case studies by division
   * ------------------------------------------------------------------ */
  var filters = Array.prototype.slice.call(document.querySelectorAll('.filter'));
  var cases = Array.prototype.slice.call(document.querySelectorAll('[data-case]'));
  var caseCount = document.querySelector('[data-case-count]');
  var noResults = document.querySelector('[data-no-results]');

  if (filters.length && cases.length) {
    function applyFilter(wanted) {
      var visible = 0;

      cases.forEach(function (card) {
        var show = wanted === 'all' || card.dataset.division === wanted;
        card.hidden = !show;
        if (show) { visible += 1; }
      });

      if (caseCount) {
        caseCount.textContent = visible + (visible === 1 ? ' case study' : ' case studies');
      }
      if (noResults) {
        noResults.style.display = visible === 0 ? 'block' : 'none';
      }
    }

    filters.forEach(function (button) {
      button.addEventListener('click', function () {
        filters.forEach(function (other) {
          other.setAttribute('aria-pressed', other === button ? 'true' : 'false');
        });
        applyFilter(button.dataset.division);
      });
    });

    applyFilter('all');
  }

  /* ------------------------------------------------------------------ *
   * Contact — enquiry type picker + submit
   * ------------------------------------------------------------------ */
  var typeButtons = Array.prototype.slice.call(document.querySelectorAll('[data-enquiry-type]'));
  var enquiryField = document.getElementById('enquiry-type');

  if (typeButtons.length) {
    typeButtons.forEach(function (button) {
      button.addEventListener('click', function () {
        typeButtons.forEach(function (other) {
          other.setAttribute('aria-pressed', other === button ? 'true' : 'false');
        });
        if (enquiryField) { enquiryField.value = button.dataset.enquiryType; }
      });
    });

    /* Arriving from a division link with ?type= preselects the matching answer. */
    var wanted = (window.location.search.match(/[?&]type=([^&]+)/) || [])[1];
    if (wanted) {
      var preset = decodeURIComponent(wanted);
      typeButtons.forEach(function (button) {
        if (button.dataset.enquiryType === preset) { button.click(); }
      });
    }
  }

  var contactForm = document.getElementById('contact-form');

  if (contactForm) {
    var submitBtn = contactForm.querySelector('[type="submit"]');
    var errorNote = contactForm.querySelector('[data-form-error]');
    var successPanel = document.querySelector('[data-success]');
    var formWrap = document.querySelector('[data-form-wrap]');

    contactForm.addEventListener('submit', function (event) {
      event.preventDefault();
      if (!contactForm.checkValidity()) { contactForm.reportValidity(); return; }

      if (errorNote) { errorNote.hidden = true; }
      if (submitBtn) { submitBtn.disabled = true; }

      var data = new FormData(contactForm);

      fetch('submit.php', { method: 'POST', body: data })
        .then(function (res) {
          return res.json().catch(function () { return {}; }).then(function (body) {
            return { ok: res.ok && body.ok, body: body };
          });
        })
        .then(function (result) {
          if (!result.ok) { throw new Error(result.body.error || 'Submission failed'); }
          if (formWrap) { formWrap.hidden = true; }
          if (successPanel) { successPanel.hidden = false; }
          window.scrollTo({ top: 0, behavior: 'smooth' });
        })
        .catch(function () {
          if (errorNote) { errorNote.hidden = false; }
        })
        .then(function () {
          if (submitBtn) { submitBtn.disabled = false; }
        });
    });
  }
})();
