/**
 * Parts Plus Innovation Solutions — app.js
 * Public frontend JavaScript
 * No build step required — plain ES6+
 */

"use strict";

/* ═══════════════════════════════════════════════════════
   0.  Utilities
═══════════════════════════════════════════════════════ */
const $ = (sel, ctx = document) => ctx.querySelector(sel);
const $$ = (sel, ctx = document) => [...ctx.querySelectorAll(sel)];

function debounce(fn, ms = 300) {
    let t;
    return (...args) => {
        clearTimeout(t);
        t = setTimeout(() => fn(...args), ms);
    };
}

function getCsrf() {
    return document.querySelector('meta[name="csrf-token"]')?.content ?? "";
}

async function postJson(url, data) {
    const res = await fetch(url, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": getCsrf(),
            Accept: "application/json",
        },
        body: JSON.stringify(data),
    });
    return res.json();
}

/* ═══════════════════════════════════════════════════════
   1.  HTML class updates (js-available)
═══════════════════════════════════════════════════════ */
document.documentElement.classList.replace("no-js", "js");

/* ═══════════════════════════════════════════════════════
   2.  Sticky Header — shrink on scroll
═══════════════════════════════════════════════════════ */
(function initStickyHeader() {
    const header = $("#siteHeader");
    if (!header) return;

    let lastY = 0;
    let ticking = false;

    function update() {
        const y = window.scrollY;
        if (y > 60) {
            header.classList.add("header--scrolled");
        } else {
            header.classList.remove("header--scrolled");
        }
        // Hide header on scroll down, reveal on scroll up (mobile only)
        if (window.innerWidth <= 768) {
            if (y > lastY && y > 120) {
                header.classList.add("header--hidden");
            } else {
                header.classList.remove("header--hidden");
            }
        }
        lastY = y;
        ticking = false;
    }

    window.addEventListener(
        "scroll",
        () => {
            if (!ticking) {
                requestAnimationFrame(update);
                ticking = true;
            }
        },
        { passive: true },
    );
})();

/* ═══════════════════════════════════════════════════════
   3.  Mobile Menu
═══════════════════════════════════════════════════════ */
(function initMobileMenu() {
    const toggle = $("#mobileMenuToggle");
    const close = $("#mobileMenuClose");
    const menu = $("#mobileMenu");
    const overlay = $("#mobileMenuOverlay");
    if (!toggle || !menu) return;

    function openMenu() {
        document.body.classList.add("mobile-menu-open");
        menu.setAttribute("aria-hidden", "false");
        overlay.setAttribute("aria-hidden", "false");
        toggle.setAttribute("aria-expanded", "true");
        menu.querySelector("a, button")?.focus();
    }

    function closeMenu() {
        document.body.classList.remove("mobile-menu-open");
        menu.setAttribute("aria-hidden", "true");
        overlay.setAttribute("aria-hidden", "true");
        toggle.setAttribute("aria-expanded", "false");
        toggle.focus();
    }

    toggle.addEventListener("click", () => {
        document.body.classList.contains("mobile-menu-open")
            ? closeMenu()
            : openMenu();
    });

    close?.addEventListener("click", closeMenu);
    overlay?.addEventListener("click", closeMenu);

    // Close on Escape
    document.addEventListener("keydown", (e) => {
        if (
            e.key === "Escape" &&
            document.body.classList.contains("mobile-menu-open")
        )
            closeMenu();
    });

    // Sub-menu toggles
    $$(".mobile-nav-toggle").forEach((btn) => {
        btn.addEventListener("click", function () {
            const expanded = this.getAttribute("aria-expanded") === "true";
            this.setAttribute("aria-expanded", String(!expanded));
            const sub = this.nextElementSibling;
            if (sub) sub.classList.toggle("open", !expanded);
        });
    });
})();

/* ═══════════════════════════════════════════════════════
   4.  Header Search — Autocomplete / Suggestions
═══════════════════════════════════════════════════════ */
(function initSearch() {
    const inputs = $$(".header-search-input, .mobile-search input");

    inputs.forEach((input) => {
        // Create dropdown container
        const wrap = input.closest("form");
        if (!wrap) return;

        const dd = document.createElement("div");
        dd.className = "search-suggestions";
        dd.setAttribute("role", "listbox");
        dd.setAttribute("aria-label", "Search suggestions");
        dd.hidden = true;
        wrap.style.position = "relative";
        wrap.appendChild(dd);

        const fetchSuggestions = debounce(async (q) => {
            if (q.length < 2) {
                dd.hidden = true;
                return;
            }
            try {
                const res = await fetch(
                    `/api/parts/search-suggestions?q=${encodeURIComponent(q)}`,
                    {
                        headers: { Accept: "application/json" },
                    },
                );
                const data = await res.json();
                renderSuggestions(data.suggestions ?? []);
            } catch {
                dd.hidden = true;
            }
        }, 250);

        function renderSuggestions(items) {
            dd.innerHTML = "";
            if (!items.length) {
                dd.hidden = true;
                return;
            }

            items.forEach((item, i) => {
                const el = document.createElement("a");
                el.className = "search-suggestion-item";
                el.href = item.url;
                el.setAttribute("role", "option");
                el.setAttribute("tabindex", "-1");
                el.innerHTML = `
          <span class="suggestion-icon">
            <i class="fa-solid fa-${item.type === "part" ? "gear" : "tag"}"></i>
          </span>
          <span class="suggestion-label">${escapeHtml(item.label)}</span>
          ${item.meta ? `<span class="suggestion-meta">${escapeHtml(item.meta)}</span>` : ""}
        `;
                dd.appendChild(el);
            });

            dd.hidden = false;
        }

        input.addEventListener("input", (e) =>
            fetchSuggestions(e.target.value.trim()),
        );

        // Keyboard navigation
        input.addEventListener("keydown", (e) => {
            if (dd.hidden) return;
            const items = $$(".search-suggestion-item", dd);
            const active = $(".search-suggestion-item:focus", dd);
            const idx = active ? items.indexOf(active) : -1;

            if (e.key === "ArrowDown") {
                e.preventDefault();
                items[idx + 1]?.focus();
            }
            if (e.key === "ArrowUp") {
                e.preventDefault();
                idx > 0 ? items[idx - 1].focus() : input.focus();
            }
            if (e.key === "Escape") {
                dd.hidden = true;
                input.focus();
            }
        });

        // Close on outside click
        document.addEventListener("click", (e) => {
            if (!wrap.contains(e.target)) dd.hidden = true;
        });
    });

    function escapeHtml(s) {
        return String(s)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;");
    }
})();

/* ═══════════════════════════════════════════════════════
   5.  Flash Message Auto-Dismiss
═══════════════════════════════════════════════════════ */
(function initFlashMessages() {
    const stack = $("#flashStack");
    if (!stack) return;

    $$(".flash", stack).forEach((flash, i) => {
        setTimeout(
            () => {
                flash.style.opacity = "0";
                flash.style.transform = "translateX(20px)";
                flash.style.transition = "opacity .3s, transform .3s";
                setTimeout(() => flash.remove(), 310);
            },
            5000 + i * 600,
        );
    });
})();

/* ═══════════════════════════════════════════════════════
   6.  Back to Top
═══════════════════════════════════════════════════════ */
(function initBackToTop() {
    const btn = $("#backToTop");
    if (!btn) return;

    function toggle() {
        btn.classList.toggle("visible", window.scrollY > 400);
    }

    window.addEventListener("scroll", debounce(toggle, 80), { passive: true });

    btn.addEventListener("click", () => {
        window.scrollTo({ top: 0, behavior: "smooth" });
    });
})();

/* ═══════════════════════════════════════════════════════
   7.  Filter Sidebar Accordion
═══════════════════════════════════════════════════════ */
(function initFilters() {
    $$(".filter-group-toggle").forEach((toggle) => {
        const expanded = toggle.getAttribute("aria-expanded") !== "false";
        const body = toggle.nextElementSibling;
        if (!body) return;

        if (!expanded) body.style.display = "none";

        toggle.addEventListener("click", function () {
            const isOpen = this.getAttribute("aria-expanded") === "true";
            this.setAttribute("aria-expanded", String(!isOpen));
            body.style.display = isOpen ? "none" : "";
        });
    });

    // Filter form: auto-submit on checkbox change
    $$('.filter-option input[type="checkbox"]').forEach((cb) => {
        cb.addEventListener("change", () => {
            cb.closest("form")?.submit();
        });
    });
})();

/* ═══════════════════════════════════════════════════════
   8.  Part Image Gallery
═══════════════════════════════════════════════════════ */
(function initPartGallery() {
    const mainImg = $(".part-gallery-main img");
    if (!mainImg) return;

    $$(".part-gallery-thumb").forEach((thumb) => {
        thumb.addEventListener("click", function () {
            const src = this.dataset.full || this.querySelector("img")?.src;
            if (!src) return;

            // Crossfade swap
            mainImg.style.opacity = "0";
            mainImg.style.transition = "opacity .18s ease";
            setTimeout(() => {
                mainImg.src = src;
                mainImg.style.opacity = "1";
            }, 180);

            $$(".part-gallery-thumb").forEach((t) =>
                t.classList.remove("part-gallery-thumb--active"),
            );
            this.classList.add("part-gallery-thumb--active");
        });
    });

    // Zoom on click (simple lightbox)
    const mainWrap = $(".part-gallery-main");
    if (mainWrap) {
        mainWrap.addEventListener("click", function () {
            const overlay = document.createElement("div");
            overlay.className = "img-lightbox";
            overlay.innerHTML = `
        <div class="img-lightbox-bg"></div>
        <div class="img-lightbox-inner">
          <img src="${mainImg.src}" alt="${mainImg.alt}">
          <button class="img-lightbox-close" aria-label="Close">
            <i class="fa-solid fa-xmark"></i>
          </button>
        </div>`;
            document.body.appendChild(overlay);
            document.body.style.overflow = "hidden";

            function closeLightbox() {
                overlay.remove();
                document.body.style.overflow = "";
            }

            overlay
                .querySelector(".img-lightbox-bg")
                .addEventListener("click", closeLightbox);
            overlay
                .querySelector(".img-lightbox-close")
                .addEventListener("click", closeLightbox);
            document.addEventListener("keydown", function esc(e) {
                if (e.key === "Escape") {
                    closeLightbox();
                    document.removeEventListener("keydown", esc);
                }
            });
        });
    }
})();

/* ═══════════════════════════════════════════════════════
   9.  Quote Form — Multi-Step Enhancement
═══════════════════════════════════════════════════════ */
(function initQuoteForm() {
    const form = $("#quoteForm");
    if (!form) return;

    // Part number lookup — try to match against known parts
    const partNoInput = form.querySelector('[name="part_number"]');
    const makeSelect = form.querySelector('[name="make"]');

    if (partNoInput) {
        const lookupHint = document.createElement("div");
        lookupHint.className = "part-lookup-hint";
        lookupHint.hidden = true;
        partNoInput.closest(".form-group")?.appendChild(lookupHint);

        const doLookup = debounce(async (val) => {
            if (val.length < 3) {
                lookupHint.hidden = true;
                return;
            }
            try {
                const res = await fetch(
                    `/api/parts/lookup?q=${encodeURIComponent(val)}`,
                    {
                        headers: { Accept: "application/json" },
                    },
                );
                const data = await res.json();
                if (data.part) {
                    lookupHint.innerHTML = `
            <i class="fa-solid fa-circle-check" style="color:var(--success)"></i>
            Found: <strong>${escapeHtml(data.part.name)}</strong>
            — <a href="${data.part.url}" target="_blank">View Part</a>`;
                    lookupHint.hidden = false;
                } else {
                    lookupHint.innerHTML = `<i class="fa-solid fa-circle-info" style="color:var(--info)"></i> Part not found in inventory — we'll source it for you.`;
                    lookupHint.hidden = false;
                }
            } catch {
                lookupHint.hidden = true;
            }
        }, 500);

        partNoInput.addEventListener("input", (e) =>
            doLookup(e.target.value.trim()),
        );
    }

    // Model cascade: populate models when make changes
    if (makeSelect) {
        makeSelect.addEventListener("change", async function () {
            const makeId = this.value;
            const modelSel =
                form.querySelector('[name="model_id"]') ||
                form.querySelector('[name="model"]');
            if (!modelSel || !makeId) return;

            modelSel.innerHTML = '<option value="">Loading…</option>';
            modelSel.disabled = true;

            try {
                const res = await fetch(`/api/makes/${makeId}/models`, {
                    headers: { Accept: "application/json" },
                });
                const data = await res.json();
                modelSel.innerHTML =
                    '<option value="">— Select Model —</option>' +
                    (data.models ?? [])
                        .map(
                            (m) =>
                                `<option value="${m.id}">${escapeHtml(m.name)}${m.year_range ? " (" + m.year_range + ")" : ""}</option>`,
                        )
                        .join("");
                modelSel.disabled = false;
            } catch {
                modelSel.innerHTML =
                    '<option value="">Error loading models</option>';
                modelSel.disabled = false;
            }
        });
    }

    // Client-side validation feedback
    form.addEventListener("submit", function (e) {
        const required = $$("[required]", form);
        let valid = true;

        required.forEach((field) => {
            const group = field.closest(".form-group");
            const err = group?.querySelector(".form-error");

            if (!field.value.trim()) {
                field.classList.add("form-control--error");
                if (err) {
                    err.textContent = "This field is required.";
                    err.hidden = false;
                }
                if (valid) field.focus();
                valid = false;
            } else {
                field.classList.remove("form-control--error");
                if (err) err.hidden = true;
            }
        });

        if (!valid) e.preventDefault();
    });

    function escapeHtml(s) {
        return String(s)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;");
    }
})();

/* ═══════════════════════════════════════════════════════
   10. Newsletter Form — AJAX submit
═══════════════════════════════════════════════════════ */
(function initNewsletter() {
    const form = $("#newsletterForm");
    if (!form) return;

    form.addEventListener("submit", async function (e) {
        e.preventDefault();
        const btn = form.querySelector('button[type="submit"]');
        const input = form.querySelector('[name="email"]');
        const orig = btn.textContent;

        btn.disabled = true;
        btn.textContent = "Subscribing…";

        try {
            const data = await postJson(form.action, {
                email: input.value.trim(),
            });

            if (data.success) {
                form.innerHTML = `<p class="newsletter-success">
          <i class="fa-solid fa-circle-check"></i>
          Thanks! You're subscribed.
        </p>`;
            } else {
                btn.disabled = false;
                btn.textContent = orig;
                // Show inline error
                let errEl = form.querySelector(".newsletter-error");
                if (!errEl) {
                    errEl = document.createElement("p");
                    errEl.className = "newsletter-error";
                    form.appendChild(errEl);
                }
                errEl.textContent =
                    data.message ?? "Something went wrong. Please try again.";
            }
        } catch {
            btn.disabled = false;
            btn.textContent = orig;
        }
    });
})();

/* ═══════════════════════════════════════════════════════
   11. Lazy-load Images
═══════════════════════════════════════════════════════ */
(function initLazyLoad() {
    if (!("IntersectionObserver" in window)) return;

    const obs = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                const img = entry.target;
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.removeAttribute("data-src");
                    img.classList.add("img-loaded");
                }
                obs.unobserve(img);
            });
        },
        { rootMargin: "200px 0px" },
    );

    $$("img[data-src]").forEach((img) => obs.observe(img));
})();

/* ═══════════════════════════════════════════════════════
   12. Scroll Reveal — animate elements into view
═══════════════════════════════════════════════════════ */
(function initScrollReveal() {
    if (!("IntersectionObserver" in window)) {
        // Fallback: just show everything
        $$("[data-reveal]").forEach((el) => (el.style.opacity = "1"));
        return;
    }

    const obs = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                const el = entry.target;
                const delay = el.dataset.revealDelay ?? 0;
                setTimeout(() => {
                    el.classList.add("revealed");
                }, Number(delay));
                obs.unobserve(el);
            });
        },
        { threshold: 0.1, rootMargin: "0px 0px -40px 0px" },
    );

    $$("[data-reveal]").forEach((el) => {
        el.style.opacity = "0";
        el.style.transform = "translateY(20px)";
        el.style.transition = "opacity .5s ease, transform .5s ease";
        obs.observe(el);
    });

    document.documentElement.addEventListener("transitionend", () => {}, false);

    // Add CSS for .revealed
    const style = document.createElement("style");
    style.textContent =
        ".revealed { opacity: 1 !important; transform: none !important; }";
    document.head.appendChild(style);
})();

/* ═══════════════════════════════════════════════════════
   13. Parts List — AJAX Pagination & Filter
═══════════════════════════════════════════════════════ */
(function initPartsAjax() {
    const partsGrid = $("#partsGrid");
    if (!partsGrid) return;

    // Intercept pagination link clicks
    document.addEventListener("click", async function (e) {
        const link = e.target.closest(
            ".pagination .page-link:not(.disabled):not(.active)",
        );
        if (!link || !partsGrid) return;

        e.preventDefault();
        const url = link.href;

        partsGrid.style.opacity = ".4";
        partsGrid.style.pointerEvents = "none";
        window.scrollTo({
            top: partsGrid.getBoundingClientRect().top + window.scrollY - 100,
            behavior: "smooth",
        });

        try {
            const res = await fetch(url, {
                headers: { "X-Requested-With": "XMLHttpRequest" },
            });
            const html = await res.text();
            const doc = new DOMParser().parseFromString(html, "text/html");
            const newGrid = doc.querySelector("#partsGrid");
            const newPagination = doc.querySelector(".pagination");

            if (newGrid) partsGrid.innerHTML = newGrid.innerHTML;
            const oldPagination = document.querySelector(".pagination");
            if (oldPagination && newPagination)
                oldPagination.replaceWith(newPagination);

            history.pushState(null, "", url);
            initLazyLoad();
        } catch {
            window.location.href = url;
        }

        partsGrid.style.opacity = "1";
        partsGrid.style.pointerEvents = "";
    });
})();

/* ═══════════════════════════════════════════════════════
   14. Smooth Anchor Links
═══════════════════════════════════════════════════════ */
(function initSmoothAnchors() {
    document.addEventListener("click", function (e) {
        const link = e.target.closest('a[href^="#"]');
        if (!link) return;
        const id = link.getAttribute("href").slice(1);
        if (!id) return;
        const target = document.getElementById(id);
        if (!target) return;
        e.preventDefault();
        target.scrollIntoView({ behavior: "smooth", block: "start" });
        history.pushState(null, "", "#" + id);
    });
})();

/* ═══════════════════════════════════════════════════════
   15. Lightbox CSS injection
═══════════════════════════════════════════════════════ */
(function injectLightboxStyles() {
    const css = `
    .img-lightbox {
      position: fixed; inset: 0; z-index: 9999;
      display: flex; align-items: center; justify-content: center;
      animation: fadeIn .2s ease;
    }
    .img-lightbox-bg {
      position: absolute; inset: 0;
      background: rgba(0,0,0,.88); cursor: zoom-out;
    }
    .img-lightbox-inner {
      position: relative; z-index: 1;
      max-width: 90vw; max-height: 90vh;
    }
    .img-lightbox-inner img {
      max-width: 90vw; max-height: 85vh;
      border-radius: 8px; object-fit: contain;
      box-shadow: 0 20px 60px rgba(0,0,0,.5);
    }
    .img-lightbox-close {
      position: absolute; top: -14px; right: -14px;
      width: 32px; height: 32px; border-radius: 50%;
      background: white; color: #111;
      font-size: 14px; cursor: pointer; border: none;
      display: flex; align-items: center; justify-content: center;
      box-shadow: 0 2px 8px rgba(0,0,0,.3);
      transition: background .15s;
    }
    .img-lightbox-close:hover { background: #f0f0f0; }
    @keyframes fadeIn {
      from { opacity: 0; } to { opacity: 1; }
    }
    /* Search suggestions */
    .search-suggestions {
      position: absolute; top: calc(100% + 4px); left: 0; right: 0;
      background: white; border-radius: 10px;
      border: 1px solid #E4E4E7;
      box-shadow: 0 12px 40px rgba(0,0,0,.12);
      z-index: 200; overflow: hidden;
    }
    .search-suggestion-item {
      display: flex; align-items: center; gap: 10px;
      padding: 10px 14px; font-size: 13px; color: #27272A;
      transition: background .12s;
    }
    .search-suggestion-item:hover,
    .search-suggestion-item:focus { background: #FFF4ED; outline: none; }
    .suggestion-icon { color: #E05C1A; font-size: 12px; width: 14px; flex-shrink: 0; }
    .suggestion-label { flex: 1; }
    .suggestion-meta  { color: #A1A1AA; font-size: 11px; }
    /* Part lookup hint */
    .part-lookup-hint {
      font-size: 12px; padding: 6px 10px; border-radius: 6px;
      background: #F9FAFB; border: 1px solid #E4E4E7;
      display: flex; align-items: center; gap: 6px; margin-top: 4px;
    }
    /* Newsletter success */
    .newsletter-success {
      display: flex; align-items: center; gap: 8px;
      color: white; font-size: 14px; font-weight: 500;
    }
    .newsletter-success i { color: #4ADE80; }
    .newsletter-error { font-size: 12px; color: #FCA5A5; margin-top: 4px; }
    /* Header scrolled state */
    .header--scrolled .header-inner { height: 50px; transition: height .2s ease; }
    .header--hidden { transform: translateY(-100%); transition: transform .3s ease; }
    .site-header { transition: transform .3s ease; }
    /* img lazy load fade-in */
    img[data-src] { opacity: 0; transition: opacity .3s ease; }
    img.img-loaded { opacity: 1; }
  `;
    const style = document.createElement("style");
    style.textContent = css;
    document.head.appendChild(style);
})();

/* ═══════════════════════════════════════════════════════
   16. Contact Form — Character Counter
═══════════════════════════════════════════════════════ */
(function initContactForm() {
    $$("textarea[maxlength]").forEach((ta) => {
        const max = parseInt(ta.getAttribute("maxlength"));
        if (!max) return;

        const counter = document.createElement("div");
        counter.className = "char-counter";
        counter.style.cssText =
            "font-size:11px;color:var(--gray-400);text-align:right;margin-top:3px;";
        ta.closest(".form-group")?.appendChild(counter);

        function update() {
            const left = max - ta.value.length;
            counter.textContent = `${left} characters remaining`;
            counter.style.color =
                left < 20 ? "var(--warning)" : "var(--gray-400)";
        }

        ta.addEventListener("input", update);
        update();
    });
})();

/* ═══════════════════════════════════════════════════════
   17. Init on DOM Ready
═══════════════════════════════════════════════════════ */
document.addEventListener("DOMContentLoaded", () => {
    // Mark initial active nav item
    const currentPath = window.location.pathname;
    $$(".nav-link").forEach((link) => {
        const href = link.getAttribute("href");
        if (href && href !== "/" && currentPath.startsWith(href)) {
            link.closest(".nav-item")?.classList.add("nav-item--active");
        }
    });

    // Add aria-current to breadcrumb last item
    $$(".breadcrumb-current").forEach((el) =>
        el.setAttribute("aria-current", "page"),
    );
});
