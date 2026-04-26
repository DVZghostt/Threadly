document.addEventListener('DOMContentLoaded', function () {
    const modalElement = document.getElementById('productModal');
    if (!modalElement) return;

    const modal = new bootstrap.Modal(modalElement);
    const titleEl = document.getElementById('productModalTitle');
    const descEl = document.getElementById('productModalDescription');
    const colorEl = document.getElementById('productModalColor');
    const sizesEl = document.getElementById('productModalSizes');
    const priceEl = document.getElementById('productModalPrice');
    const priceOldEl = document.getElementById('productModalPriceOld');
    const imageEl = document.getElementById('productModalImage');
    const newBadge = document.getElementById('productModalNewBadge');
    const saleBadge = document.getElementById('productModalSaleBadge');
    const discountEl = document.getElementById('productModalDiscount');
    const addToCartBtn = document.getElementById('productModalAddToCart');

    let currentProductId = null;

    document.querySelectorAll('.product-open-modal').forEach(button => {
        button.addEventListener('click', function () {
            currentProductId = this.dataset.productId || null;
            titleEl.textContent = this.dataset.name || '';
            descEl.textContent = this.dataset.description || '';
            colorEl.textContent = this.dataset.color || 'Indisponible';
            sizesEl.textContent = this.dataset.sizes || 'Indisponible';
            priceEl.textContent = (this.dataset.price || '0.00') + ' €';

            const priceOld = this.dataset.priceOld || '';
            if (priceOld) {
                priceOldEl.textContent = priceOld + ' €';
                priceOldEl.style.display = 'inline';
            } else {
                priceOldEl.style.display = 'none';
            }

            const imgSrc = this.dataset.image || '';
            if (imgSrc) {
                imageEl.src = imgSrc;
                imageEl.style.display = 'block';
            } else {
                imageEl.style.display = 'none';
            }

            if (this.dataset.isNew === '1') {
                newBadge.style.display = 'inline-block';
            } else {
                newBadge.style.display = 'none';
            }
            if (this.dataset.isSale === '1') {
                saleBadge.style.display = 'inline-block';
                discountEl.style.display = this.dataset.discount ? 'inline-block' : 'none';
                discountEl.textContent = this.dataset.discount ? '−' + this.dataset.discount + '%' : '';
            } else {
                saleBadge.style.display = 'none';
                discountEl.style.display = 'none';
            }

            modal.show();
        });
    });

    addToCartBtn.addEventListener('click', function () {
        if (!currentProductId) return;

        const form = document.createElement('form');
        form.method = 'post';
        form.action = 'cart_action.php';
        form.style.display = 'none';

        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'add';
        form.appendChild(actionInput);

        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'id';
        idInput.value = currentProductId;
        form.appendChild(idInput);

        document.body.appendChild(form);
        form.submit();
    });
});
