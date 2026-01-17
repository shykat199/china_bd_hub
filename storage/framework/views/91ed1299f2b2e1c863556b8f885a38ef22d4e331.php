<?php $__env->startSection('title', $title ?? 'Shop'); ?>
<?php $__env->startPush('custom-css'); ?>
    <style>
        .custom-pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            list-style: none;
            padding: 0;
            margin: 30px 0;
        }

        .custom-pagination .page-item {
            display: flex;
        }

        .custom-pagination .page-link {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            border: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            color: #111;
            background: #fff;
            transition: all 0.2s ease;
        }

        .custom-pagination .page-item.active .page-link {
            background: #c4161c;   /* RED ACTIVE */
            border-color: #c4161c;
            color: #fff;
        }

        .custom-pagination .page-item.disabled .page-link {
            opacity: 0.5;
            pointer-events: none;
        }

        .custom-pagination .page-link:hover {
            background: #f5f5f5;
        }

        @media (max-width: 576px) {
            .custom-pagination .page-link {
                width: 36px;
                height: 36px;
                font-size: 14px;
            }
        }

    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    <!-- Breadcrumb Start -->
    <nav class="breadcrumb-manu maan-shop-breadcrumb" area-label="breadcrumb" data-background="<?php echo e(asset('frontend/img/breadcrumb.png')); ?>">
        <h3><?php echo e($title ?? 'Shop'); ?></h3>
    </nav>
    <!-- Breadcrumb End -->

    <!-- Shop List Start -->
    <section class="shop-list mybazar-product-with-sidebar" style="padding: 15px 0">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 order-lg-last" id="product-area">
                    <!-- ** ajax loader start ** -->
                    <div id="product-loader">
                        <div class="overlay-content">
                            <img src="<?php echo e(asset('frontend/img/loader/bar.gif')); ?>" alt="Loading..." />
                        </div>
                    </div>
                    <!-- ** ajax loader end ** -->
                    <div class="maan-mybazar-filter">
                        <div class="maan-filter-wrapper">
                            <div class="filter-left">
                                <p class="m-0">
                                    <?php if($products->count() > 0): ?>
                                        <?php echo e($products->firstItem()); ?> - <?php echo e($products->lastItem()); ?> <?php echo e(__('of')); ?>

                                    <?php endif; ?>
                                    <?php echo e($products->total()); ?> <?php echo e(__('Results')); ?>

                                </p>
                            </div>
                            <div class="maan-filter-right">
                                <select name="sorting" id="sorting">
                                    <option selected="selected" disabled><?php echo e(__('SORT BY')); ?></option>
                                    <option value="price"><?php echo e(__('Price')); ?></option>
                                    <option value="popularity"><?php echo e(__('Popularity')); ?></option>
                                </select>
                                <div class="nav filter-grid">
                                    <h5><?php echo e(__('View')); ?></h5>
                                    <a class="active" href="#ShopGrid" data-bs-toggle="tab">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="26" viewBox="0 0 24 26">
                                            <defs>
                                                <clipPath id="clip-path">
                                                    <rect width="24" height="26" fill="none" />
                                                </clipPath>
                                            </defs>
                                            <g id="Repeat_Grid_1" data-name="Repeat Grid 1" clip-path="url(#clip-path)">
                                                <g transform="translate(-1676 -611)">
                                                    <rect id="Rectangle_146" data-name="Rectangle 146" width="6" height="6" transform="translate(1676 611)" fill="#ff8400" />
                                                </g>
                                                <g transform="translate(-1667 -611)">
                                                    <rect id="Rectangle_146-2" data-name="Rectangle 146" width="6" height="6" transform="translate(1676 611)" fill="#ff8400" />
                                                </g>
                                                <g transform="translate(-1658 -611)">
                                                    <rect id="Rectangle_146-3" data-name="Rectangle 146" width="6" height="6" transform="translate(1676 611)" fill="#ff8400" />
                                                </g>
                                                <g transform="translate(-1676 -601)">
                                                    <rect id="Rectangle_146-4" data-name="Rectangle 146" width="6" height="6" transform="translate(1676 611)" fill="#ff8400" />
                                                </g>
                                                <g transform="translate(-1667 -601)">
                                                    <rect id="Rectangle_146-5" data-name="Rectangle 146" width="6" height="6" transform="translate(1676 611)" fill="#ff8400" />
                                                </g>
                                                <g transform="translate(-1658 -601)">
                                                    <rect id="Rectangle_146-6" data-name="Rectangle 146" width="6" height="6" transform="translate(1676 611)" fill="#ff8400" />
                                                </g>
                                                <g transform="translate(-1676 -591)">
                                                    <rect id="Rectangle_146-7" data-name="Rectangle 146" width="6" height="6" transform="translate(1676 611)" fill="#ff8400" />
                                                </g>
                                                <g transform="translate(-1667 -591)">
                                                    <rect id="Rectangle_146-8" data-name="Rectangle 146" width="6" height="6" transform="translate(1676 611)" fill="#ff8400" />
                                                </g>
                                                <g transform="translate(-1658 -591)">
                                                    <rect id="Rectangle_146-9" data-name="Rectangle 146" width="6" height="6" transform="translate(1676 611)" fill="#ff8400" />
                                                </g>
                                            </g>
                                        </svg>
                                    </a>
                                    <a href="#ShopList" data-bs-toggle="tab">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26">
                                            <g id="Group_182" data-name="Group 182" transform="translate(-1430 -578)">
                                                <rect id="Rectangle_147" data-name="Rectangle 147" width="26" height="4" transform="translate(1430 578)" fill="#ff8400" />
                                                <rect id="Rectangle_148" data-name="Rectangle 148" width="26" height="4" transform="translate(1430 585)" fill="#ff8400" />
                                                <rect id="Rectangle_149" data-name="Rectangle 149" width="26" height="4" transform="translate(1430 593)" fill="#ff8400" />
                                                <rect id="Rectangle_150" data-name="Rectangle 150" width="26" height="4" transform="translate(1430 600)" fill="#ff8400" />
                                            </g>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="ShopGrid" style="margin-top: -15px">
                            <div class="row auto-margin-3 mb-3">
                                <!-- ** ajax loader start ** -->
                                <div id="product-loader">
                                    <div class="overlay-content">
                                        <img src="<?php echo e(asset('frontend/img/loader/bar.gif')); ?>" alt="Loading..." />
                                    </div>
                                </div>
                                <!-- ** ajax loader end ** -->
                                <?php if($products->count() == 0): ?>
                                    <div class="text-center" style="margin-top: 25px">
                                        <p><?php echo e(__('Not available. Try search with different keyword')); ?></p>
                                    </div>
                                <?php endif; ?>
                                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-sm-6 col-md-4 col-lg-3 col-6">
                                        <?php if (isset($component)) { $__componentOriginal21c3c2c55e30da1a9f261981b4e771a6d80c22bd = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Frontend\ProductCard::class, ['product' => $product]); ?>
<?php $component->withName('frontend.product-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal21c3c2c55e30da1a9f261981b4e771a6d80c22bd)): ?>
<?php $component = $__componentOriginal21c3c2c55e30da1a9f261981b4e771a6d80c22bd; ?>
<?php unset($__componentOriginal21c3c2c55e30da1a9f261981b4e771a6d80c22bd); ?>
<?php endif; ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php if($products->hasPages()): ?>
                                    <ul class="custom-pagination">

                                        
                                        <li class="page-item <?php echo e($products->onFirstPage() ? 'disabled' : ''); ?>">
                                            <a href="<?php echo e($products->previousPageUrl() ?? '#'); ?>"
                                               data-page="<?php echo e($products->currentPage() - 1); ?>"
                                               class="page-link">
                                                &laquo;
                                            </a>
                                        </li>

                                        
                                        <?php for($page = 1; $page <= $products->lastPage(); $page++): ?>
                                            <li class="page-item <?php echo e($page == $products->currentPage() ? 'active' : ''); ?>">
                                                <a href="<?php echo e($products->url($page)); ?>"
                                                   data-page="<?php echo e($page); ?>"
                                                   class="page-link">
                                                    <?php echo e($page); ?>

                                                </a>
                                            </li>
                                        <?php endfor; ?>

                                        
                                        <li class="page-item <?php echo e($products->hasMorePages() ? '' : 'disabled'); ?>">
                                            <a href="<?php echo e($products->nextPageUrl() ?? '#'); ?>"
                                               data-page="<?php echo e($products->currentPage() + 1); ?>"
                                               class="page-link">
                                                &raquo;
                                            </a>
                                        </li>

                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- shop list items -->
                        <div class="tab-pane fade" id="ShopList">
                            <div class="row auto-margin-3 mb-3" id="product-area">
                                <?php if($products->count() == 0): ?>
                                    <div class="text-center" style="margin-top: 25px">
                                        <p><?php echo e(__('Not available. Try search with different keyword')); ?></p>
                                    </div>
                                <?php endif; ?>
                                <div class="col-lg-12">
                                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if (isset($component)) { $__componentOriginal5d9b37d58b4fc322377df48fa54c686fcf1ccad0 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Frontend\ProductCard3::class, ['product' => $product]); ?>
<?php $component->withName('frontend.product-card3'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5d9b37d58b4fc322377df48fa54c686fcf1ccad0)): ?>
<?php $component = $__componentOriginal5d9b37d58b4fc322377df48fa54c686fcf1ccad0; ?>
<?php unset($__componentOriginal5d9b37d58b4fc322377df48fa54c686fcf1ccad0); ?>
<?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.frontend.page-navigation-ajax','data' => ['paginator' => $products]]); ?>
<?php $component->withName('frontend.page-navigation-ajax'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($products)]); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="sidebar">
                        <?php if (isset($component)) { $__componentOriginal131910ae5b02e910fe07dcb3fb90e4a7f977c985 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Frontend\CategoryWidget::class, ['categories' => $categories]); ?>
<?php $component->withName('frontend.category-widget'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal131910ae5b02e910fe07dcb3fb90e4a7f977c985)): ?>
<?php $component = $__componentOriginal131910ae5b02e910fe07dcb3fb90e4a7f977c985; ?>
<?php unset($__componentOriginal131910ae5b02e910fe07dcb3fb90e4a7f977c985); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.frontend.brand-widget','data' => ['brands' => $brands]]); ?>
<?php $component->withName('frontend.brand-widget'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['brands' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($brands)]); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal6afd3121da86dd972eff1e198f832374a15d1476 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Frontend\PriceWidget::class, []); ?>
<?php $component->withName('frontend.price-widget'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['prices' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($prices)]); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6afd3121da86dd972eff1e198f832374a15d1476)): ?>
<?php $component = $__componentOriginal6afd3121da86dd972eff1e198f832374a15d1476; ?>
<?php unset($__componentOriginal6afd3121da86dd972eff1e198f832374a15d1476); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginala255c84e649e949e9cb806f41f6278b7020c5421 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Frontend\ColorWidget::class, ['colors' => $colors]); ?>
<?php $component->withName('frontend.color-widget'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala255c84e649e949e9cb806f41f6278b7020c5421)): ?>
<?php $component = $__componentOriginala255c84e649e949e9cb806f41f6278b7020c5421; ?>
<?php unset($__componentOriginala255c84e649e949e9cb806f41f6278b7020c5421); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginalc36937a800654614bf861d27573526c993147367 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Frontend\SizeWidget::class, ['sizes' => $sizes]); ?>
<?php $component->withName('frontend.size-widget'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc36937a800654614bf861d27573526c993147367)): ?>
<?php $component = $__componentOriginalc36937a800654614bf861d27573526c993147367; ?>
<?php unset($__componentOriginalc36937a800654614bf861d27573526c993147367); ?>
<?php endif; ?>
                        <div class="sidebar-widget">
                            <?php if (isset($component)) { $__componentOriginalabf66136fc5f0a94b8e1a77c4d72cdafccf55963 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Frontend\ProductWidget::class, ['title' => 'Popular Today','products' => $populars]); ?>
<?php $component->withName('frontend.product-widget'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalabf66136fc5f0a94b8e1a77c4d72cdafccf55963)): ?>
<?php $component = $__componentOriginalabf66136fc5f0a94b8e1a77c4d72cdafccf55963; ?>
<?php unset($__componentOriginalabf66136fc5f0a94b8e1a77c4d72cdafccf55963); ?>
<?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Shop List End -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/china_hub/resources/views/frontend/pages/shop.blade.php ENDPATH**/ ?>