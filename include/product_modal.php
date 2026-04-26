<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="productModalLabel">Produit</h5>
                    <div class="mt-2">
                        <span id="productModalNewBadge" class="badge bg-success me-2" style="display:none;">Nouveau</span>
                        <span id="productModalSaleBadge" class="badge bg-primary me-2" style="display:none;">Promo</span>
                        <span id="productModalDiscount" class="badge bg-warning text-dark" style="display:none;"></span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div class="row g-4 align-items-center">
                    <div class="col-md-5">
                        <div class="ratio ratio-1x1" style="background:#f8f9fa; border-radius:18px; display:flex; align-items:center; justify-content:center; overflow:hidden;">
                            <img id="productModalImage" src="" alt="Produit" style="max-width:100%; max-height:100%; display:block; object-fit:contain;" />
                        </div>
                    </div>
                    <div class="col-md-7">
                        <h5 id="productModalTitle"></h5>
                        <p class="text-muted" id="productModalDescription"></p>
                        <ul class="list-unstyled mb-3">
                            <li><strong>Couleur :</strong> <span id="productModalColor"></span></li>
                            <li><strong>Tailles :</strong> <span id="productModalSizes"></span></li>
                            <li><strong>Prix :</strong> <span id="productModalPrice"></span> <span id="productModalPriceOld" class="text-muted" style="text-decoration: line-through; display:none; margin-left:8px;"></span></li>
                        </ul>
                        <div>
                            <button type="button" class="btn btn-primary" id="productModalAddToCart">Ajouter au panier</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
