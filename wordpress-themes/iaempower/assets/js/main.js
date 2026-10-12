
(function(){
  "use strict";
  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  var header = document.querySelector('[data-header]');
  var navLinks = Array.prototype.slice.call(document.querySelectorAll('[data-nav]'));
  var sections = ['inicio','servicios','casos','equipo','precios','recursos','contacto']
    .map(function(id){ return document.getElementById(id); })
    .filter(Boolean);

  var progressBar = document.getElementById('scrollProgress');
  function onScroll(){
    header.classList.toggle('is-scrolled', window.scrollY > 8);
    var doc = document.documentElement;
    var max = doc.scrollHeight - doc.clientHeight;
    progressBar.style.width = (max > 0 ? (doc.scrollTop / max * 100) : 0) + '%';
  }
  document.addEventListener('scroll', onScroll, {passive:true});
  onScroll();

  if ('IntersectionObserver' in window){
    var spy = new IntersectionObserver(function(entries){
      entries.forEach(function(entry){
        if (!entry.isIntersecting) return;
        var id = entry.target.id;
        navLinks.forEach(function(a){
          a.classList.toggle('is-active', a.getAttribute('href') === '#' + id);
        });
      });
    }, {rootMargin: '-45% 0px -50% 0px', threshold: 0});
    sections.forEach(function(s){ spy.observe(s); });
  }

  var sectionNav = document.getElementById('sectionNav');
  if (sectionNav && sections.length){
    var prevBtn = sectionNav.querySelector('[data-scroll="prev"]');
    var nextBtn = sectionNav.querySelector('[data-scroll="next"]');

    function currentSectionIndex(){
      var idx = 0;
      sections.forEach(function(section, i){
        if (section.getBoundingClientRect().top <= 120) idx = i;
      });
      return idx;
    }

    function goToSection(index){
      if (index < 0 || index >= sections.length) return;
      sections[index].scrollIntoView({behavior: reduceMotion ? 'auto' : 'smooth', block: 'start'});
    }

    function updateSectionNavState(){
      var idx = currentSectionIndex();
      prevBtn.classList.toggle('is-disabled', idx <= 0);
      nextBtn.classList.toggle('is-disabled', idx >= sections.length - 1);
    }

    prevBtn.addEventListener('click', function(){ goToSection(currentSectionIndex() - 1); });
    nextBtn.addEventListener('click', function(){ goToSection(currentSectionIndex() + 1); });
    document.addEventListener('scroll', updateSectionNavState, {passive:true});
    updateSectionNavState();
  }

  var leakCalc = document.getElementById('leakCalculator');
  if (leakCalc){
    var weeksPerYear = parseFloat(leakCalc.dataset.weeks) || 47;
    var recoveryPct = parseFloat(leakCalc.dataset.recovery) || 35;
    var avgInvestment = parseFloat(leakCalc.dataset.investment) || 3000;

    var leakHours = document.getElementById('leakHours');
    var leakPeople = document.getElementById('leakPeople');
    var leakCost = document.getElementById('leakCost');
    var leakHoursOut = document.getElementById('leakHoursOut');
    var leakPeopleOut = document.getElementById('leakPeopleOut');
    var leakCostOut = document.getElementById('leakCostOut');
    var leakAnnualCost = document.getElementById('leakAnnualCost');
    var leakRecoverable = document.getElementById('leakRecoverable');
    var leakPayback = document.getElementById('leakPayback');

    function formatEUR(amount){
      return Math.round(amount).toLocaleString('es-ES', { useGrouping: true }) + ' €';
    }

    function updateLeakCalc(){
      var hours = parseFloat(leakHours.value) || 0;
      var people = parseFloat(leakPeople.value) || 0;
      var cost = parseFloat(leakCost.value) || 0;

      leakHoursOut.textContent = hours + ' h';
      leakPeopleOut.textContent = people;
      leakCostOut.textContent = formatEUR(cost) + '/h';

      var annualCost = hours * cost * weeksPerYear * people;
      var recoverable = annualCost * (recoveryPct / 100);
      var monthlySavings = recoverable / 12;
      var payback = monthlySavings > 0 ? (avgInvestment / monthlySavings) : 0;

      leakAnnualCost.textContent = formatEUR(annualCost);
      leakRecoverable.textContent = formatEUR(recoverable);
      if (payback <= 0){ leakPayback.textContent = '—'; }
      else if (payback < 0.5){ leakPayback.textContent = '< 2 semanas'; }
      else { leakPayback.textContent = payback.toFixed(1) + ' meses'; }
    }

    [leakHours, leakPeople, leakCost].forEach(function(input){
      input.addEventListener('input', updateLeakCalc);
    });
    updateLeakCalc();
  }

  var toggle = document.getElementById('menuToggle');
  var mobileNav = document.getElementById('mobileNav');
  function closeMenu(){
    toggle.setAttribute('aria-expanded','false');
    mobileNav.classList.remove('is-open');
  }
  toggle.addEventListener('click', function(){
    var open = toggle.getAttribute('aria-expanded') === 'true';
    toggle.setAttribute('aria-expanded', String(!open));
    mobileNav.classList.toggle('is-open', !open);
  });
  mobileNav.querySelectorAll('a').forEach(function(a){ a.addEventListener('click', closeMenu); });
  document.addEventListener('keydown', function(e){ if (e.key === 'Escape') closeMenu(); });

  if ('IntersectionObserver' in window){
    var reveal = new IntersectionObserver(function(entries){
      entries.forEach(function(entry){
        if (entry.isIntersecting){
          entry.target.classList.add('is-visible');
          reveal.unobserve(entry.target);
        }
      });
    }, {threshold: 0.15, rootMargin: '0px 0px -8% 0px'});
    document.querySelectorAll('[data-reveal]').forEach(function(el){ reveal.observe(el); });
  } else {
    document.querySelectorAll('[data-reveal]').forEach(function(el){ el.classList.add('is-visible'); });
  }

  function animateCount(el){
    var target = parseFloat(el.getAttribute('data-count'));
    var suffix = el.getAttribute('data-suffix') || '';
    var prefix = el.getAttribute('data-prefix') || '';
    if (reduceMotion || !target){ el.textContent = prefix + target + suffix; return; }
    var start = null, duration = 1400;
    function step(ts){
      if (!start) start = ts;
      var p = Math.min((ts - start) / duration, 1);
      var eased = 1 - Math.pow(1 - p, 3);
      el.textContent = prefix + Math.round(target * eased) + suffix;
      if (p < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
  }
  var statEls = document.querySelectorAll('.hero-stats dd');
  if (statEls.length && 'IntersectionObserver' in window){
    var counted = false;
    var countObs = new IntersectionObserver(function(entries){
      entries.forEach(function(entry){
        if (entry.isIntersecting && !counted){
          counted = true;
          statEls.forEach(animateCount);
          countObs.disconnect();
        }
      });
    }, {threshold:0.3});
    countObs.observe(statEls[0].closest('.hero-stats'));
  }

  var caseMetrics = document.querySelectorAll('.case-metric .value[data-count]');
  if (caseMetrics.length && 'IntersectionObserver' in window){
    var cmObs = new IntersectionObserver(function(entries){
      entries.forEach(function(entry){
        if (entry.isIntersecting){
          animateCount(entry.target);
          cmObs.unobserve(entry.target);
        }
      });
    }, {threshold:0.4});
    caseMetrics.forEach(function(el){ cmObs.observe(el); });
  }

  /* faq accordion */
  document.querySelectorAll('.faq-item').forEach(function(item){
    var btn = item.querySelector('.faq-q');
    btn.addEventListener('click', function(){
      var isOpen = item.classList.toggle('is-open');
      btn.setAttribute('aria-expanded', String(isOpen));
    });
  });

  var form = document.getElementById('contactForm');
  if (form){
    form.addEventListener('submit', function(e){
      if (!form.checkValidity()){
        e.preventDefault();
        form.reportValidity();
      }
      /* valid submissions post normally to admin-post.php (see inc/contact-form.php) */
    });
  }

  /* spotlight follow on glass cards */
  if (!reduceMotion && window.matchMedia('(hover:hover) and (pointer:fine)').matches){
    document.querySelectorAll('.card').forEach(function(card){
      card.addEventListener('mousemove', function(e){
        var r = card.getBoundingClientRect();
        card.style.setProperty('--mx', (e.clientX - r.left) + 'px');
        card.style.setProperty('--my', (e.clientY - r.top) + 'px');
      });
    });

    /* magnetic buttons */
    document.querySelectorAll('[data-magnetic]').forEach(function(btn){
      var strength = 14;
      btn.addEventListener('mousemove', function(e){
        var r = btn.getBoundingClientRect();
        var mx = (e.clientX - r.left - r.width/2) / (r.width/2);
        var my = (e.clientY - r.top - r.height/2) / (r.height/2);
        btn.style.transform = 'translate(' + (mx*strength) + 'px,' + (my*strength*0.6) + 'px)';
      });
      btn.addEventListener('mouseleave', function(){ btn.style.transform = ''; });
    });

    /* 3d tilt on bento pillars and case cards */
    document.querySelectorAll('[data-tilt]').forEach(function(card){
      card.addEventListener('mousemove', function(e){
        var r = card.getBoundingClientRect();
        var px = (e.clientX - r.left) / r.width - 0.5;
        var py = (e.clientY - r.top) / r.height - 0.5;
        card.style.transform = 'perspective(900px) rotateX(' + (py*-6) + 'deg) rotateY(' + (px*8) + 'deg) translateY(-4px)';
      });
      card.addEventListener('mouseleave', function(){ card.style.transform = ''; });
    });
  }

  /* hero canvas: signal network rising over the sierra skyline
     (only present on front-page.php — absent on single.php/index.php) */
  var canvas = document.getElementById('heroCanvas');
  if (canvas){
  var ctx = canvas.getContext('2d');
  var hero = document.getElementById('inicio');
  var dpr = Math.min(window.devicePixelRatio || 1, 2);
  var W = 0, H = 0, ridgeY = [], nodes = [], raf = null;

  function css(name){ return getComputedStyle(document.documentElement).getPropertyValue(name).trim(); }
  function hexToRgb(hex){
    hex = hex.replace('#','');
    if (hex.length === 3) hex = hex.split('').map(function(c){return c+c;}).join('');
    var n = parseInt(hex,16);
    return [(n>>16)&255,(n>>8)&255,n&255];
  }
  function seededRandom(seed){
    var s = seed;
    return function(){ s = (s * 9301 + 49297) % 233280; return s / 233280; };
  }
  function buildRidge(width, baseY, amp, seed){
    var rand = seededRandom(seed);
    var pts = [];
    var steps = 14;
    for (var i=0;i<=steps;i++){
      var x = (width / steps) * i;
      var y = baseY - Math.abs(Math.sin(i*0.9 + seed) * amp * (0.4 + rand()*0.9));
      pts.push([x,y]);
    }
    return pts;
  }
  function resize(){
    var rect = hero.getBoundingClientRect();
    W = rect.width; H = rect.height;
    canvas.width = W * dpr; canvas.height = H * dpr;
    canvas.style.width = W + 'px'; canvas.style.height = H + 'px';
    ctx.setTransform(dpr,0,0,dpr,0,0);
    ridgeY = [ buildRidge(W, H*0.62, H*0.16, 2.1), buildRidge(W, H*0.72, H*0.13, 5.4) ];
    var count = Math.max(16, Math.min(46, Math.round(W/34)));
    nodes = [];
    for (var i=0;i<count;i++){
      nodes.push({ x: Math.random()*W, y: Math.random()*(H*0.55),
        vx: (Math.random()-0.5)*0.12, vy: (Math.random()-0.5)*0.08, r: 1 + Math.random()*1.6 });
    }
  }
  function draw(){
    ctx.clearRect(0,0,W,H);
    var groundRgb = hexToRgb(css('--ground').startsWith('#') ? css('--ground') : '#08090c');
    var accentRgb = hexToRgb(css('--accent').startsWith('#') ? css('--accent') : '#8e52ff');
    var accent2Rgb = hexToRgb(css('--accent-2').startsWith('#') ? css('--accent-2') : '#d8b4fe');

    var glow = ctx.createRadialGradient(W*0.72,H*0.9,0,W*0.72,H*0.9,W*0.68);
    glow.addColorStop(0,'rgba('+accent2Rgb.join(',')+',0.24)');
    glow.addColorStop(0.5,'rgba('+accentRgb.join(',')+',0.07)');
    glow.addColorStop(1,'rgba('+accentRgb.join(',')+',0)');
    ctx.fillStyle = glow; ctx.fillRect(0,0,W,H);

    ridgeY.forEach(function(ridge, idx){
      ctx.beginPath(); ctx.moveTo(0,H);
      ridge.forEach(function(p){ ctx.lineTo(p[0],p[1]); });
      ctx.lineTo(W,H); ctx.closePath();
      var shade = idx === 0 ? 0.6 : 0.9;
      ctx.fillStyle = 'rgba('+Math.round(groundRgb[0]+18)+','+Math.round(groundRgb[1]+18)+','+Math.round(groundRgb[2]+20)+','+shade+')';
      ctx.fill();
    });

    var maxDist = Math.max(70, W*0.09);
    for (var i=0;i<nodes.length;i++){
      for (var j=i+1;j<nodes.length;j++){
        var a = nodes[i], b = nodes[j];
        var dx = a.x-b.x, dy = a.y-b.y;
        var dist = Math.sqrt(dx*dx+dy*dy);
        if (dist < maxDist){
          ctx.strokeStyle = 'rgba('+accentRgb.join(',')+','+((1 - dist/maxDist)*0.22)+')';
          ctx.lineWidth = 1;
          ctx.beginPath(); ctx.moveTo(a.x,a.y); ctx.lineTo(b.x,b.y); ctx.stroke();
        }
      }
    }
    nodes.forEach(function(n){
      ctx.beginPath(); ctx.arc(n.x,n.y,n.r,0,Math.PI*2);
      ctx.fillStyle = 'rgba('+accent2Rgb.join(',')+',0.8)'; ctx.fill();
    });
  }
  function step(){
    nodes.forEach(function(n){
      n.x += n.vx; n.y += n.vy;
      if (n.x < 0 || n.x > W) n.vx *= -1;
      if (n.y < 0 || n.y > H*0.6) n.vy *= -1;
    });
    draw();
    raf = requestAnimationFrame(step);
  }
  function start(){
    resize(); draw();
    if (!reduceMotion){ if (raf) cancelAnimationFrame(raf); raf = requestAnimationFrame(step); }
  }
  var resizeTimer;
  window.addEventListener('resize', function(){
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(start, 150);
  });
  document.addEventListener('visibilitychange', function(){
    if (document.hidden && raf){ cancelAnimationFrame(raf); raf = null; }
    else if (!document.hidden && !reduceMotion && !raf){ raf = requestAnimationFrame(step); }
  });
  start();
  }
})();
