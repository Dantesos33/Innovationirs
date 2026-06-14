/**
 * Parts Plus Innovation Solutions — Admin Panel JavaScript
 * public/js/admin.js
 */

/* ── Sidebar Toggle ────────────────────────────────────────── */
const sidebar = document.getElementById("sidebar");
const sidebarToggle = document.getElementById("sidebarToggle");
const adminMain = document.getElementById("adminMain");

if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener("click", () => {
        sidebar.classList.toggle("open");
    });

    // Close on outside click (mobile)
    document.addEventListener("click", (e) => {
        if (
            window.innerWidth <= 768 &&
            !sidebar.contains(e.target) &&
            !sidebarToggle.contains(e.target)
        ) {
            sidebar.classList.remove("open");
        }
    });
}

/* ── Flash Message Auto-Dismiss ────────────────────────────── */
document.querySelectorAll(".flash-close").forEach((btn) => {
    btn.addEventListener("click", () => {
        btn.closest(".flash").style.opacity = "0";
        setTimeout(() => btn.closest(".flash")?.remove(), 200);
    });
});
setTimeout(() => {
    document.querySelectorAll(".flash").forEach((el) => {
        el.style.transition = "opacity 0.3s";
        el.style.opacity = "0";
        setTimeout(() => el.remove(), 300);
    });
}, 5000);

/* ── Delete Confirmation Modal ─────────────────────────────── */
const deleteModal = document.getElementById("deleteModal");
const deleteForm = document.getElementById("deleteForm");
const deleteCancel = document.getElementById("deleteCancel");
const deleteMessage = document.getElementById("deleteModalMessage");

document.querySelectorAll("[data-delete-url]").forEach((btn) => {
    btn.addEventListener("click", (e) => {
        e.preventDefault();
        const url = btn.dataset.deleteUrl;
        const label = btn.dataset.deleteLabel || "this item";
        if (deleteMessage)
            deleteMessage.textContent = `Are you sure you want to delete "${label}"? This action cannot be undone.`;
        if (deleteForm) deleteForm.action = url;
        if (deleteModal) deleteModal.style.display = "flex";
    });
});

if (deleteCancel) {
    deleteCancel.addEventListener("click", () => {
        if (deleteModal) deleteModal.style.display = "none";
    });
}
if (deleteModal) {
    deleteModal.addEventListener("click", (e) => {
        if (e.target === deleteModal) deleteModal.style.display = "none";
    });
}

/* ── Bulk Select ───────────────────────────────────────────── */
const selectAll = document.getElementById("selectAll");
const bulkBar = document.getElementById("bulkBar");
const bulkCount = document.getElementById("bulkCount");
const bulkForm = document.getElementById("bulkForm");

function updateBulkBar() {
    const checked = document.querySelectorAll(".row-check:checked");
    if (bulkBar && bulkCount) {
        if (checked.length > 0) {
            bulkBar.classList.add("visible");
            bulkCount.textContent = checked.length;
        } else {
            bulkBar.classList.remove("visible");
        }
    }
}

if (selectAll) {
    selectAll.addEventListener("change", () => {
        document.querySelectorAll(".row-check").forEach((cb) => {
            cb.checked = selectAll.checked;
        });
        updateBulkBar();
    });
}

document.querySelectorAll(".row-check").forEach((cb) => {
    cb.addEventListener("change", updateBulkBar);
});

// Bulk action submit
document.querySelectorAll("[data-bulk-action]").forEach((btn) => {
    btn.addEventListener("click", () => {
        const action = btn.dataset.bulkAction;
        const ids = [...document.querySelectorAll(".row-check:checked")].map(
            (cb) => cb.value,
        );
        if (ids.length === 0) return;
        if (action === "delete") {
            if (
                !confirm(
                    `Delete ${ids.length} selected item(s)? This cannot be undone.`,
                )
            )
                return;
        }
        if (bulkForm) {
            const actionInput = bulkForm.querySelector('[name="action"]');
            const idsInput = bulkForm.querySelector('[name="ids"]');
            if (actionInput) actionInput.value = action;
            if (idsInput) idsInput.value = JSON.stringify(ids);
            bulkForm.submit();
        }
    });
});

/* ── Image Upload Preview ──────────────────────────────────── */
document.querySelectorAll("[data-image-upload]").forEach((area) => {
    const input = area.querySelector('input[type="file"]');
    const preview =
        area.closest(".form-group")?.querySelector(".image-preview") ||
        document.querySelector(
            '[data-image-preview="' + area.dataset.imageUpload + '"]',
        );
    if (!input) return;

    input.addEventListener("change", () => {
        const file = input.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (e) => {
            if (preview) {
                const img =
                    preview.querySelector("img") ||
                    document.createElement("img");
                img.src = e.target.result;
                img.style.display = "block";
                preview.style.display = "block";
                if (!preview.querySelector("img")) preview.prepend(img);
            }
        };
        reader.readAsDataURL(file);
    });

    // Drag and drop
    area.addEventListener("dragover", (e) => {
        e.preventDefault();
        area.classList.add("dragover");
    });
    area.addEventListener("dragleave", () => area.classList.remove("dragover"));
    area.addEventListener("drop", (e) => {
        e.preventDefault();
        area.classList.remove("dragover");
        if (e.dataTransfer.files.length) {
            input.files = e.dataTransfer.files;
            input.dispatchEvent(new Event("change"));
        }
    });
});

// Remove image
document.querySelectorAll(".image-preview-remove").forEach((btn) => {
    btn.addEventListener("click", () => {
        const preview = btn.closest(".image-preview");
        const input = document.getElementById(btn.dataset.clearInput);
        if (preview) preview.style.display = "none";
        if (input) input.value = "";
        // Set hidden flag to clear image on save
        const clearFlag = document.getElementById(btn.dataset.clearFlag);
        if (clearFlag) clearFlag.value = "1";
    });
});

/* ── Toggle Active Status (AJAX) ───────────────────────────── */
document.querySelectorAll("[data-toggle-url]").forEach((btn) => {
    btn.addEventListener("click", async () => {
        const url = btn.dataset.toggleUrl;
        const csrfToken = document.querySelector(
            'meta[name="csrf-token"]',
        ).content;
        try {
            const res = await fetch(url, {
                method: "PATCH",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
            });
            const data = await res.json();
            if (data.active !== undefined) {
                btn.classList.toggle("active", data.active);
                btn.title = data.active ? "Deactivate" : "Activate";
            }
        } catch (err) {
            console.error("Toggle failed", err);
        }
    });
});

/* ── Rich Text Toolbar (lightweight) ───────────────────────── */
document.querySelectorAll("[data-rich-editor]").forEach((editorWrap) => {
    const content = editorWrap.querySelector(".rich-editor-content");
    const hidden = document.getElementById(editorWrap.dataset.richEditor);
    if (!content) return;

    // Sync to hidden input
    content.addEventListener("input", () => {
        if (hidden) hidden.value = content.innerHTML;
    });

    // ── Sync on form submit (fixes null content on toolbar-only edits)
    const form = editorWrap.closest("form");
    if (form) {
        form.addEventListener("submit", () => {
            if (hidden) hidden.value = content.innerHTML;
        });
    }

    // Toolbar actions
    editorWrap.querySelectorAll("[data-cmd]").forEach((btn) => {
        btn.addEventListener("click", (e) => {
            e.preventDefault();
            const cmd = btn.dataset.cmd;
            const arg = btn.dataset.arg || null;
            document.execCommand(cmd, false, arg);
            content.focus();
            if (hidden) hidden.value = content.innerHTML;
        });
    });
});

/* ── Sort Order (drag-to-reorder) ──────────────────────────── */
// Basic implementation — use SortableJS in production
const sortableList = document.querySelector("[data-sortable]");
if (sortableList && window.Sortable) {
    new Sortable(sortableList, {
        handle: ".sortable-handle",
        animation: 150,
        ghostClass: "sortable-ghost",
        onEnd: () => {
            const ids = [...sortableList.querySelectorAll("[data-id]")].map(
                (el) => el.dataset.id,
            );
            const csrfToken = document.querySelector(
                'meta[name="csrf-token"]',
            ).content;
            fetch(sortableList.dataset.sortable, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ ids }),
            });
        },
    });
}

/* ── Chart.js Dashboard ────────────────────────────────────── */
function initDashboardCharts() {
    const quotesCtx = document.getElementById("quotesChart")?.getContext("2d");
    const statusCtx = document.getElementById("statusChart")?.getContext("2d");

    if (quotesCtx && window.quotesChartData) {
        new Chart(quotesCtx, {
            type: "line",
            data: {
                labels: window.quotesChartLabels,
                datasets: [
                    {
                        label: "Quote Requests",
                        data: window.quotesChartData,
                        borderColor: "#2563EB",
                        backgroundColor: "rgba(224,92,26,0.08)",
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 3,
                        pointBackgroundColor: "#2563EB",
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0, font: { size: 11 } },
                        grid: { color: "#F4F4F5" },
                    },
                    x: {
                        ticks: { font: { size: 10 }, maxRotation: 0 },
                        grid: { display: false },
                    },
                },
            },
        });
    }

    if (statusCtx && window.statusChartData) {
        new Chart(statusCtx, {
            type: "doughnut",
            data: {
                labels: window.statusChartLabels,
                datasets: [
                    {
                        data: window.statusChartData,
                        backgroundColor: [
                            "#2563EB",
                            "#2563EB",
                            "#16A34A",
                            "#D97706",
                            "#DC2626",
                            "#71717A",
                        ],
                        borderWidth: 0,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: "68%",
                plugins: {
                    legend: {
                        position: "bottom",
                        labels: { font: { size: 11 }, padding: 12 },
                    },
                },
            },
        });
    }
}

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initDashboardCharts);
} else {
    initDashboardCharts();
}

/* ── Auto-slug generation ──────────────────────────────────── */
const slugSource = document.getElementById("slugSource");
const slugTarget = document.getElementById("slug");
let slugEdited = false;

if (slugTarget) {
    slugTarget.addEventListener("input", () => {
        slugEdited = true;
    });
}
if (slugSource && slugTarget) {
    slugSource.addEventListener("input", () => {
        if (slugEdited) return;
        slugTarget.value = slugSource.value
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, "")
            .replace(/\s+/g, "-")
            .replace(/-+/g, "-")
            .trim();
    });
}

/* ── Quote Status quick-update ─────────────────────────────── */
const statusSelect = document.getElementById("quickStatusSelect");
if (statusSelect) {
    statusSelect.addEventListener("change", async () => {
        const url = statusSelect.dataset.url;
        const csrfToken = document.querySelector(
            'meta[name="csrf-token"]',
        ).content;
        await fetch(url, {
            method: "PATCH",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "Content-Type": "application/json",
                Accept: "application/json",
            },
            body: JSON.stringify({ status: statusSelect.value }),
        });
    });
}
