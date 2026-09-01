/* ============================================================
   PIISTON LANDING PAGE — landing.js
   Interactive behaviors, scroll reveal, counters, tabs, carousel, theme
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {

  // 1. Light / Dark Theme Toggle Engine
  const themeToggleBtn = document.getElementById('theme-toggle-btn');
  const storedTheme = localStorage.getItem('piiston_theme');
  const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

  const applyTheme = (theme) => {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('piiston_theme', theme);
    if (themeToggleBtn) {
      const sunIcon = themeToggleBtn.querySelector('.theme-icon-sun');
      const moonIcon = themeToggleBtn.querySelector('.theme-icon-moon');
      if (sunIcon && moonIcon) {
        if (theme === 'dark') {
          sunIcon.style.setProperty('display', 'inline-block', 'important');
          moonIcon.style.setProperty('display', 'none', 'important');
        } else {
          sunIcon.style.setProperty('display', 'none', 'important');
          moonIcon.style.setProperty('display', 'inline-block', 'important');
        }
      }
    }
  };

  if (storedTheme) {
    applyTheme(storedTheme);
  } else if (systemPrefersDark) {
    applyTheme('dark');
  } else {
    applyTheme('light');
  }

  if (themeToggleBtn) {
    themeToggleBtn.addEventListener('click', () => {
      const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
      const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
      applyTheme(newTheme);
    });
  }

  // 2. Sticky Header Effect
  const header = document.querySelector('.lp-header');
  if (header) {
    header.classList.add('lp-header--scrolled');
    window.addEventListener('scroll', () => {
      if (window.scrollY > 30) {
        header.classList.add('lp-header--scrolled');
      } else {
        header.classList.remove('lp-header--scrolled');
      }
    });
  }

  // 2b. Active Nav Link on Scroll
  const navLinks = document.querySelectorAll('.lp-nav__link');
  const mobileNavLinks = document.querySelectorAll('.lp-mobile-nav__link');
  const allNavLinks = [...navLinks, ...mobileNavLinks];
  const sections = [];
  const sectionIds = [];

  allNavLinks.forEach(link => {
    const href = link.getAttribute('href');
    if (href && href.startsWith('#') && href.length > 1) {
      const id = href.slice(1);
      if (!sectionIds.includes(id)) {
        const section = document.getElementById(id);
        if (section) {
          sections.push(section);
          sectionIds.push(id);
        }
      }
    }
  });

  if (sections.length > 0 && allNavLinks.length > 0) {
    const navObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const id = entry.target.getAttribute('id');
          allNavLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href === `#${id}`) {
              link.classList.add('is-active');
            } else {
              link.classList.remove('is-active');
            }
          });
        }
      });
    }, {
      rootMargin: '-20% 0px -70% 0px',
      threshold: 0
    });

    sections.forEach(section => navObserver.observe(section));
  }

  // 3. Mobile Menu Drawer Toggle
  const menuToggle = document.querySelector('.lp-menu-toggle');
  const mobileNav = document.getElementById('mobileNav') || document.querySelector('.lp-mobile-nav');
  const mobileNavClose = document.getElementById('mobileNavClose');
  const mobileNavOverlay = document.getElementById('mobileNavOverlay');

  function openMobileMenu() {
    if (mobileNav) mobileNav.classList.add('is-open');
    if (mobileNavOverlay) mobileNavOverlay.classList.add('is-open');
    document.body.style.overflow = 'hidden';
  }

  function closeMobileMenu() {
    if (mobileNav) mobileNav.classList.remove('is-open');
    if (mobileNavOverlay) mobileNavOverlay.classList.remove('is-open');
    document.body.style.overflow = '';
  }

  if (menuToggle) {
    menuToggle.addEventListener('click', () => {
      if (mobileNav && mobileNav.classList.contains('is-open')) {
        closeMobileMenu();
      } else {
        openMobileMenu();
      }
    });
  }

  if (mobileNavClose) {
    mobileNavClose.addEventListener('click', closeMobileMenu);
  }

  if (mobileNavOverlay) {
    mobileNavOverlay.addEventListener('click', closeMobileMenu);
  }

  if (mobileNav) {
    mobileNav.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', closeMobileMenu);
    });
  }

  // 3b. 4-Pillar Glass Architecture Hub Interactivity
  const pillarCards = document.querySelectorAll('.pillar-card');
  const pillarPanels = document.querySelectorAll('.pillar-panel');

  if (pillarCards.length > 0 && pillarPanels.length > 0) {
    pillarCards.forEach(card => {
      card.addEventListener('click', () => {
        const targetPillarId = card.dataset.pillar;

        pillarCards.forEach(c => c.classList.remove('is-active'));
        card.classList.add('is-active');

        pillarPanels.forEach(panel => {
          if (panel.id === targetPillarId) {
            panel.classList.add('is-active');
          } else {
            panel.classList.remove('is-active');
          }
        });
      });
    });
  }

  // 4. Scroll Reveal Observer
  const revealElements = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');
  if (revealElements.length > 0) {
    const revealObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.1,
      rootMargin: '0px 0px -30px 0px'
    });

    revealElements.forEach(el => revealObserver.observe(el));
  }

  // 5. Counter Animation on Scroll
  const counterElements = document.querySelectorAll('[data-counter]');
  if (counterElements.length > 0) {
    const counterObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const target = entry.target;
          const endValue = parseInt(target.getAttribute('data-counter'), 10);
          const duration = 2000;
          const frameDuration = 1000 / 60;
          const totalFrames = Math.round(duration / frameDuration);
          let frame = 0;

          const counterInterval = setInterval(() => {
            frame++;
            const progress = frame / totalFrames;
            const currentCount = Math.round(endValue * (1 - (1 - progress) * (1 - progress)));
            target.textContent = currentCount.toLocaleString();

            if (frame === totalFrames) {
              clearInterval(counterInterval);
              target.textContent = endValue.toLocaleString();
            }
          }, frameDuration);

          observer.unobserve(target);
        }
      });
    }, { threshold: 0.2 });

    counterElements.forEach(el => counterObserver.observe(el));
  }

  // 6. Solutions by Role Tabs
  const solTabs = document.querySelectorAll('.solutions__tab');
  const solPanels = document.querySelectorAll('.solutions__panel');
  if (solTabs.length > 0) {
    solTabs.forEach(tab => {
      tab.addEventListener('click', () => {
        const role = tab.getAttribute('data-role');

        solTabs.forEach(t => t.classList.remove('is-active'));
        solPanels.forEach(p => p.classList.remove('is-active'));

        tab.classList.add('is-active');
        const targetPanel = document.querySelector(`.solutions__panel[data-role="${role}"]`);
        if (targetPanel) {
          targetPanel.classList.add('is-active');
        }
      });
    });
  }

  // 7. How It Works Tabs
  const hiwTabs = document.querySelectorAll('.hiw-role-tab');
  const hiwStepsList = document.querySelectorAll('.hiw-steps');
  if (hiwTabs.length > 0) {
    hiwTabs.forEach(tab => {
      tab.addEventListener('click', () => {
        const role = tab.getAttribute('data-hiw-role');

        hiwTabs.forEach(t => t.classList.remove('is-active'));
        hiwStepsList.forEach(s => s.classList.remove('is-active'));

        tab.classList.add('is-active');
        const targetSteps = document.querySelector(`.hiw-steps[data-hiw-role="${role}"]`);
        if (targetSteps) {
          targetSteps.classList.add('is-active');
        }
      });
    });
  }

  // 8. Testimonials Carousel
  const track = document.querySelector('.testimonials-track');
  const prevBtn = document.querySelector('.testimonials-arrow--prev');
  const nextBtn = document.querySelector('.testimonials-arrow--next');
  const dots = document.querySelectorAll('.testimonials-dot');

  if (track && dots.length > 0) {
    let currentIndex = 0;
    const cards = track.querySelectorAll('.testimonial-card');
    const totalCards = cards.length;

    const updateCarousel = (index) => {
      if (index < 0) index = totalCards - 1;
      if (index >= totalCards) index = 0;
      currentIndex = index;

      const cardWidth = cards[0].offsetWidth + 24;
      track.style.transform = `translateX(-${currentIndex * cardWidth}px)`;

      dots.forEach((dot, i) => {
        dot.classList.toggle('is-active', i === currentIndex);
      });
    };

    if (prevBtn) prevBtn.addEventListener('click', () => updateCarousel(currentIndex - 1));
    if (nextBtn) nextBtn.addEventListener('click', () => updateCarousel(currentIndex + 1));

    dots.forEach((dot, i) => {
      dot.addEventListener('click', () => updateCarousel(i));
    });
  }

  // 9. FAQ Accordion Single Toggle
  const faqItems = document.querySelectorAll('.faq-item');
  if (faqItems.length > 0) {
    faqItems.forEach(item => {
      const q = item.querySelector('.faq-item__q');
      if (q) {
        q.addEventListener('click', () => {
          const isOpen = item.classList.contains('is-open');
          faqItems.forEach(other => other.classList.remove('is-open'));
          item.classList.toggle('is-open', !isOpen);
        });
      }
    });
  }

  // 10. Marketplace Categories & Live Search Interactivity
  const marketCats = document.querySelectorAll('.market-cat');
  const productCards = document.querySelectorAll('.product-card');
  const marketSearchInput = document.querySelector('.market-search__input');

  if (marketCats.length > 0) {
    marketCats.forEach(cat => {
      cat.addEventListener('click', () => {
        marketCats.forEach(c => c.classList.remove('is-active'));
        cat.classList.add('is-active');

        const catName = cat.querySelector('.market-cat__name')?.textContent.toLowerCase().trim();
        if (productCards.length > 0 && catName) {
          productCards.forEach(card => {
            const productName = card.querySelector('.product-card__name')?.textContent.toLowerCase() || '';
            const productSeller = card.querySelector('.product-card__seller')?.textContent.toLowerCase() || '';
            if (catName === 'engines' || productName.includes(catName) || productSeller.includes(catName)) {
              card.style.display = 'flex';
              card.style.animation = 'fadeIn 0.35s ease';
            } else {
              card.style.display = 'none';
            }
          });
        }
      });
    });
  }

  if (marketSearchInput && productCards.length > 0) {
    marketSearchInput.addEventListener('input', (e) => {
      const query = e.target.value.toLowerCase().trim();
      productCards.forEach(card => {
        const text = card.textContent.toLowerCase();
        if (query === '' || text.includes(query)) {
          card.style.display = 'flex';
        } else {
          card.style.display = 'none';
        }
      });
    });
  }

  // Order Part Button Toast Feedback
  const productBtns = document.querySelectorAll('.product-card__btn');
  productBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      btn.style.transform = 'scale(1.25)';
      btn.style.background = 'var(--accent-green)';
      btn.style.borderColor = 'var(--accent-green)';
      btn.style.color = '#FFF';
      setTimeout(() => {
        btn.style.transform = 'none';
        btn.style.background = '';
        btn.style.borderColor = '';
        btn.style.color = '';
      }, 600);
    });
  });

  // 11. Contact Form Submission
  const contactForm = document.querySelector('.contact-form');
  if (contactForm) {
    contactForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const submitBtn = contactForm.querySelector('button[type="submit"]');
      const originalText = submitBtn.textContent;
      submitBtn.disabled = true;
      submitBtn.textContent = 'Sending...';

      try {
        const formData = new FormData(contactForm);
        const response = await fetch(contactForm.action, {
          method: 'POST',
          body: formData,
          headers: {
            'Accept': 'application/json',
          },
        });

        const data = await response.json();

        if (response.ok && data.success) {
          submitBtn.textContent = 'Sent ✓';
          submitBtn.style.background = 'var(--accent-green)';
          contactForm.reset();
        } else {
          submitBtn.textContent = originalText;
          submitBtn.style.background = '';
          const errors = data.errors || {};
          Object.keys(errors).forEach((field) => {
            const input = contactForm.querySelector(`[name="${field}"]`);
            if (input) {
              input.style.borderColor = '#e74c3c';
              setTimeout(() => { input.style.borderColor = ''; }, 3000);
            }
          });
          alert(data.message || 'Something went wrong. Please try again.');
        }
      } catch (error) {
        submitBtn.textContent = originalText;
        submitBtn.style.background = '';
        alert('Network error. Please try again later.');
      } finally {
        submitBtn.disabled = false;
        setTimeout(() => {
          submitBtn.textContent = originalText;
          submitBtn.style.background = '';
        }, 3000);
      }
    });
  }

  // 12. Footer Newsletter Form Submission
  const newsletterForm = document.querySelector('.footer__newsletter-form');
  if (newsletterForm) {
    newsletterForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const input = newsletterForm.querySelector('input');
      const submitBtn = newsletterForm.querySelector('button');
      const originalText = submitBtn.textContent;
      submitBtn.disabled = true;
      submitBtn.textContent = 'Subscribing...';

      try {
        const formData = new FormData(newsletterForm);
        const response = await fetch(newsletterForm.action, {
          method: 'POST',
          body: formData,
          headers: {
            'Accept': 'application/json',
          },
        });

        const data = await response.json();

        if (response.ok && data.success) {
          submitBtn.textContent = 'Subscribed ✓';
          submitBtn.style.background = 'var(--accent-green)';
          input.value = '';
        } else {
          submitBtn.textContent = originalText;
          submitBtn.style.background = '';
          alert(data.message || 'Something went wrong. Please try again.');
        }
      } catch (error) {
        submitBtn.textContent = originalText;
        submitBtn.style.background = '';
        alert('Network error. Please try again later.');
      } finally {
        submitBtn.disabled = false;
        setTimeout(() => {
          submitBtn.textContent = originalText;
          submitBtn.style.background = '';
        }, 3000);
      }
    });
  }

  // 13. Smooth Scrolling for Anchor Links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const href = this.getAttribute('href');
      if (href !== '#' && href.length > 1) {
        const targetEl = document.querySelector(href);
        if (targetEl) {
          e.preventDefault();
          const navOffset = 80;
          const targetPos = targetEl.getBoundingClientRect().top + window.pageYOffset - navOffset;
          window.scrollTo({
            top: targetPos,
            behavior: 'smooth'
          });
        }
      }
    });
  });

  // 13. Interactive Work Details Modal Controller
  const workModal = document.getElementById('work-details-modal');
  const workTriggers = document.querySelectorAll('[data-work-details]');
  const workModalCloseBtns = document.querySelectorAll('.work-modal-close-btn');
  const workModalTabs = document.querySelectorAll('.work-modal__tab');
  const workModalPanels = document.querySelectorAll('.work-modal__panel');
  const stageFillLine = document.querySelector('.stage-tracker__line-fill');

  const openWorkModal = (data = {}) => {
    if (!workModal) return;
    
    // Update Modal Fields if custom data provided
    const vehicleTitle = workModal.querySelector('.modal-vehicle-title');
    const garageTitle = workModal.querySelector('.modal-garage-name');
    const vinCode = workModal.querySelector('.modal-vin-code');
    const statusPill = workModal.querySelector('.modal-status-pill');

    if (vehicleTitle && data.vehicle) vehicleTitle.textContent = data.vehicle;
    if (garageTitle && data.garage) garageTitle.textContent = data.garage;
    if (vinCode && data.vin) vinCode.textContent = data.vin;
    if (statusPill && data.status) statusPill.textContent = data.status;

    // Reset and animate stage line fill
    if (stageFillLine) {
      stageFillLine.style.width = '0%';
      setTimeout(() => {
        stageFillLine.style.width = data.fill || '66%';
      }, 150);
    }

    workModal.classList.add('is-active');
    document.body.style.overflow = 'hidden';
  };

  const closeWorkModal = () => {
    if (!workModal) return;
    workModal.classList.remove('is-active');
    document.body.style.overflow = '';
  };

  workTriggers.forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const rawData = btn.getAttribute('data-work-details');
      let parsed = {};
      try {
        if (rawData) parsed = JSON.parse(rawData);
      } catch (err) {}
      openWorkModal(parsed);
    });
  });

  workModalCloseBtns.forEach(btn => {
    btn.addEventListener('click', closeWorkModal);
  });

  if (workModal) {
    const backdrop = workModal.querySelector('.work-modal__backdrop');
    if (backdrop) {
      backdrop.addEventListener('click', closeWorkModal);
    }
  }

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && workModal && workModal.classList.contains('is-active')) {
      closeWorkModal();
    }
  });

  // Modal Internal Tab Switcher
  workModalTabs.forEach(tab => {
    tab.addEventListener('click', () => {
      const targetTab = tab.getAttribute('data-modal-tab');
      workModalTabs.forEach(t => t.classList.remove('is-active'));
      workModalPanels.forEach(p => p.classList.remove('is-active'));

      tab.classList.add('is-active');
      const activePanel = workModal.querySelector(`.work-modal__panel[data-modal-panel="${targetTab}"]`);
      if (activePanel) activePanel.classList.add('is-active');
    });
  });

  // Mobile Money Pay Action Toast
  const payEscrowBtn = document.querySelector('.work-modal-pay-btn');
  if (payEscrowBtn) {
    payEscrowBtn.addEventListener('click', () => {
      payEscrowBtn.disabled = true;
      payEscrowBtn.textContent = 'Processing MoMo Escrow...';
      setTimeout(() => {
        payEscrowBtn.textContent = 'Approved & Escrow Locked ✓';
        payEscrowBtn.style.background = 'var(--accent-green)';
        payEscrowBtn.style.borderColor = 'var(--accent-green)';
        payEscrowBtn.style.color = '#FFF';
        if (stageFillLine) stageFillLine.style.width = '100%';
        
        // Update stage 4 icon
        const step4 = workModal.querySelector('.stage-step[data-step="4"]');
        if (step4) {
          step4.classList.add('is-done');
          step4.classList.remove('is-current');
        }
      }, 1500);
    });
  }

  // 3. Cookie Consent Gate
  const cookieBanner = document.getElementById('cookie-consent-banner');
  if (cookieBanner) {
    const getConsentCookie = () => {
      const match = document.cookie.match(/(?:^|; )piiston_cookie_consent=([^;]+)/);
      return match ? decodeURIComponent(match[1]) : null;
    };

    const showBanner = () => {
      if (cookieBanner) cookieBanner.style.display = 'block';
    };

    const hideBanner = () => {
      if (cookieBanner) cookieBanner.style.display = 'none';
    };

    const setConsent = (consent) => {
      const xhr = new XMLHttpRequest();
      xhr.open('POST', '{{ route("cookie.consent") }}', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
      xhr.send('consent=' + encodeURIComponent(consent));
    };

    const consent = getConsentCookie();
    if (!consent) {
      showBanner();
    }

    cookieBanner.querySelectorAll('[data-consent]').forEach(btn => {
      btn.addEventListener('click', () => {
        const value = btn.getAttribute('data-consent');
        setConsent(value);
        hideBanner();
      });
    });
  }

});

