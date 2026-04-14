/* ═══════════════════════════════════════════════
   Chandra & Sons Hospital — main.js
   ═══════════════════════════════════════════════ */

document.addEventListener('DOMContentLoaded', function () {

  /* ── HERO SLIDER ─────────────────────────────── */
  var slides = document.getElementById('heroSlides');
  var dots   = document.querySelectorAll('#heroDots .slider-dot');
  var total  = dots.length;
  var cur    = 0;
  var timer;

  function goTo(n) {
    cur = (n + total) % total;
    if (slides) slides.style.transform = 'translateX(-' + cur * 100 + '%)';
    dots.forEach(function(d, i) { d.classList.toggle('active', i === cur); });
  }

  function next() { goTo(cur + 1); }
  function prev() { goTo(cur - 1); }
  function startAuto() { timer = setInterval(next, 5000); }
  function stopAuto()  { clearInterval(timer); }

  var btnNext = document.getElementById('heroNext');
  var btnPrev = document.getElementById('heroPrev');
  if (btnNext) { btnNext.addEventListener('click', function() { stopAuto(); next(); startAuto(); }); }
  if (btnPrev) { btnPrev.addEventListener('click', function() { stopAuto(); prev(); startAuto(); }); }
  dots.forEach(function(d, i) {
    d.addEventListener('click', function() { stopAuto(); goTo(i); startAuto(); });
  });
  if (slides && total > 1) startAuto();

  /* ── TEAM CAROUSEL ───────────────────────────── */
  var teamTrack   = document.getElementById('teamTrack');
  var teamPrev    = document.getElementById('teamPrev');
  var teamNext    = document.getElementById('teamNext');
  var teamCounter = document.getElementById('teamCounter');
  var teamCur     = 0;
  var teamTotal   = teamTrack ? teamTrack.children.length : 0;

  function teamGoTo(n) {
    teamCur = (n + teamTotal) % teamTotal;
    if (teamTrack) teamTrack.style.transform = 'translateX(-' + teamCur * 100 + '%)';
    if (teamCounter) teamCounter.textContent = (teamCur + 1) + ' / ' + teamTotal;
  }
  if (teamPrev) teamPrev.addEventListener('click', function() { teamGoTo(teamCur - 1); });
  if (teamNext) teamNext.addEventListener('click', function() { teamGoTo(teamCur + 1); });

  /* ── TESTIMONIAL CAROUSEL ────────────────────── */
  var testTrack = document.getElementById('testTrack');
  var testDots  = document.querySelectorAll('#testDots .test-dot');
  var testTotal = testDots.length;
  var testCur   = 0;
  var testTimer;

  function testGoTo(n) {
    testCur = (n + testTotal) % testTotal;
    if (testTrack) testTrack.style.transform = 'translateX(-' + testCur * 100 + '%)';
    testDots.forEach(function(d, i) { d.classList.toggle('active', i === testCur); });
  }
  testDots.forEach(function(d, i) { d.addEventListener('click', function() { testGoTo(i); }); });
  if (testTrack && testTotal > 1) {
    testTimer = setInterval(function() { testGoTo(testCur + 1); }, 4500);
  }

  /* ── SERVICE CARD HOVER ARROW ─────────────────── */
  document.querySelectorAll('.service-card').forEach(function(card) {
    card.addEventListener('mouseenter', function() {
      var arrow = card.querySelector('.card-arrow');
      if (arrow) arrow.textContent = '→';
    });
  });

  /* ── SCROLL REVEAL ────────────────────────────── */
  var revealEls = document.querySelectorAll('.service-card, .portal-card, .dash-card, .info-card, .stat-highlight');
  if ('IntersectionObserver' in window) {
    var observer = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          entry.target.style.opacity    = '1';
          entry.target.style.transform  = 'translateY(0)';
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1 });

    revealEls.forEach(function(el) {
      el.style.opacity   = '0';
      el.style.transform = 'translateY(20px)';
      el.style.transition = 'opacity .5s ease, transform .5s ease';
      observer.observe(el);
    });
  }

});

/* ── Global helpers ──────────────────────────────── */
function filterTable() {
  var q = document.getElementById('searchInput').value.toLowerCase();
  document.querySelectorAll('#patientsTable tbody .patient-row').forEach(function(row) {
    row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
  });
}

function filterData() {
  var q = document.getElementById('searchInput').value.toLowerCase();
  document.querySelectorAll('.filter-card').forEach(function(card) {
    var doc = (card.getAttribute('data-doctor') || '').toLowerCase();
    var vis = false;
    card.querySelectorAll('.patient-row').forEach(function(row) {
      var name = (row.getAttribute('data-name') || '').toLowerCase();
      var show = name.includes(q) || doc.includes(q);
      row.style.display = show ? '' : 'none';
      if (show) vis = true;
    });
    card.style.display = vis ? '' : 'none';
  });
}

function toggleEdit(id) {
  var box = document.getElementById('edit-' + id);
  if (box) box.style.display = box.style.display === 'block' ? 'none' : 'block';
}

function setTimeSlot(el) {
  var t  = el.options[el.selectedIndex].getAttribute('data-time');
  var tf = document.getElementById('timeSlot');
  if (tf) tf.value = t || 'Not Set';
}

function updateRoleUI() {
  var role = document.getElementById('roleSelect').value;
  var dept = document.getElementById('deptInput');
  if (!dept) return;
  dept.disabled    = role === 'staff';
  dept.style.opacity = role === 'staff' ? '.4' : '1';
  dept.placeholder = role === 'staff' ? 'N/A — not required for staff' : 'e.g. Cardiology';
}
