async function postForm(url, data) {
    const fd = new FormData();

    Object.entries(data).forEach(([k, v]) => {
        fd.append(k, String(v));
    });

    const r = await fetch(url, {
        method: "POST",
        body: fd,
    });

    return r.json().catch(() => ({ ok: false }));
}

function recalcCartTotal() {
    let total = 0;

    document.querySelectorAll(".cart-row").forEach((row) => {
        const qty = parseInt(
            row.querySelector(".qty-value")?.textContent || 0,
            10
        );
        const priceBlock = row.querySelector(".cart-price");
        const unitPrice = parseFloat(priceBlock?.dataset.unitPrice || 0);

        const itemTotal = qty * unitPrice;
        const totalEl = priceBlock?.querySelector(".item-total");

        if (totalEl) {
            totalEl.textContent = itemTotal.toFixed(2) + " ₽";
        }

        total += itemTotal;
    });

    const cartTotal = document.getElementById("cartTotal");

    if (cartTotal) {
        cartTotal.textContent = total.toFixed(2) + " ₽";
    }
}

document.addEventListener("click", async (e) => {
    const addBtn = e.target.closest("[data-product-add]");
    const incBtn = e.target.closest("[data-qty-inc]");
    const decBtn = e.target.closest("[data-qty-dec]");
    const remBtn = e.target.closest("[data-remove-from-wishlist]");

    if (!addBtn && !incBtn && !decBtn && !remBtn) {
        return;
    }

    const container =
        e.target.closest(".product-page__info") ||
        e.target.closest(".cart-row");

    if (!container) {
        return;
    }

    let productId = null;
    let delta = 0;
    let url = "api/wishlist_update.php";

    if (addBtn) {
        productId = addBtn.dataset.productAdd;
        delta = 1;
        url = "api/wishlist_add.php";
    }

    if (incBtn) {
        productId = incBtn.dataset.qtyInc;
        delta = 1;
    }

    if (decBtn) {
        productId = decBtn.dataset.qtyDec;
        delta = -1;
    }

    if (remBtn) {
        productId = remBtn.dataset.removeFromWishlist;
        url = "api/wishlist_remove.php";
    }

    const payload = { product_id: productId };

    if (!remBtn) {
        payload.delta = delta;
    }

    const res = await postForm(url, payload);

    if (!res.ok) {
        return;
    }

    if (remBtn) {
        container.remove();
        recalcCartTotal();
        return;
    }

    const qtyBlock = container.querySelector(".qty-control");
    const qtyValue = container.querySelector(".qty-value");
    const addButton = container.querySelector("[data-product-add]");

    if (res.qty > 0) {
        if (qtyValue) {
            qtyValue.textContent = res.qty;
        }

        if (addButton) {
            addButton.classList.add("hidden");
        }

        if (qtyBlock) {
            qtyBlock.classList.remove("hidden");
        }
    } else {
        container.remove();
    }

    recalcCartTotal();
});

document.addEventListener("DOMContentLoaded", recalcCartTotal);

const payBtn = document.getElementById("payBtn");
const modal = document.getElementById("paymentModal");
const closePayBtn = document.getElementById("closePayBtn");
const confirmPayBtn = document.getElementById("confirmPayBtn");

if (payBtn) {
    payBtn.addEventListener("click", () => {
        const total = getCartTotalValue();
        document.getElementById("payAmount").textContent =
            total.toFixed(2) + " ₽";

        modal.classList.remove("hidden");
    });
}

if (closePayBtn) {
    closePayBtn.addEventListener("click", () => {
        modal.classList.add("hidden");
    });
}

if (confirmPayBtn) {
    confirmPayBtn.addEventListener("click", async () => {
        confirmPayBtn.disabled = true;
        confirmPayBtn.textContent = "Оплата…";

        await new Promise((r) => setTimeout(r, 1500));
        await postForm("api/wishlist_clear.php", {});

        document.querySelectorAll(".cart-row").forEach((row) => row.remove());
        recalcCartTotal();

        const tableWrap = document.querySelector(".table-wrap");

        if (tableWrap) {
            tableWrap.innerHTML = '<p class="muted">Корзина пуста.</p>';
        }

        modal.classList.add("hidden");

        alert("✅ Заказ успешно оформлен!\nСпасибо за покупку.");

        confirmPayBtn.disabled = false;
        confirmPayBtn.textContent = "Оплатить";
    });
}

const cardNumber = document.getElementById("cardNumber");
const cardExp = document.getElementById("cardExp");
const cardCvc = document.getElementById("cardCvc");

if (cardNumber) {
    cardNumber.addEventListener("input", () => {
        let value = cardNumber.value.replace(/\D/g, "").slice(0, 16);
        value = value.replace(/(\d{4})(?=\d)/g, "$1 ");
        cardNumber.value = value;
    });
}

if (cardExp) {
    cardExp.addEventListener("input", () => {
        let value = cardExp.value.replace(/\D/g, "").slice(0, 4);

        if (value.length >= 3) {
            value = value.slice(0, 2) + "/" + value.slice(2);
        }

        cardExp.value = value;
    });
}

if (cardCvc) {
    cardCvc.addEventListener("input", () => {
        cardCvc.value = cardCvc.value.replace(/\D/g, "").slice(0, 3);
    });
}

function getCartTotalValue() {
    const el = document.getElementById("cartTotal");

    if (!el) {
        return 0;
    }

    return parseFloat(el.textContent.replace(/[^\d.]/g, "")) || 0;
}

const slider = document.getElementById("topSlider");
const track = slider?.querySelector(".slides-track");
const slides = slider?.querySelectorAll(".slide") || [];
const prevBtn = document.getElementById("sliderPrev");
const nextBtn = document.getElementById("sliderNext");

let slideIndex = 0;

function updateSlider() {
    track.style.transform = `translateX(-${slideIndex * 100}%)`;
}

function nextSlide() {
    slideIndex = (slideIndex + 1) % slides.length;
    updateSlider();
}

function prevSlide() {
    slideIndex = (slideIndex - 1 + slides.length) % slides.length;
    updateSlider();
}

if (slides.length > 1) {
    setInterval(nextSlide, 4000);
}

nextBtn?.addEventListener("click", nextSlide);
prevBtn?.addEventListener("click", prevSlide);
