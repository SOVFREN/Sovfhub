<div class="header">
    <div class="go_back_icon">
        <span class="go_to_previous_page">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"></path>
            </svg>
        </span>
    </div>
    <div class="left">
        <span class="title">
            <span><?php echo Registry::load('strings')->membership ?></span>
        </span>
    </div>
    <div class="icons">
        <span class="load_membership_info">
            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"></path>
                <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"></path>
            </svg>
        </span>
    </div>
</div>

<div class="contents">
    <div class="preloader-container">
        <div class="preloader-animation">
            <div class="progress-bar"></div>
        </div>
    </div>
    <div class="membership-info">
        <div class="membership-card">
        </div>
    </div>
    <div class="available_packages">
        <div>
            <div class="header">
                <div>
                    <div class="left">
                        <h2 class="title"><?php echo Registry::load('strings')->available_packages ?></h2>
                        <p>
                            <?php echo Registry::load('strings')->pricing_subtitle ?>
                        </p>
                    </div>
                    <div class="right">
                        <span class="previous_pricing"><?php echo Registry::load('strings')->previous ?></span>
                        <span class="next_pricing highlight"><?php echo Registry::load('strings')->next ?></span>
                    </div>
                </div>
            </div>
            <div class="packages-container">
                <div class="pricing-table-container"></div>
            </div>
        </div>
    </div>

    <div class="payment_page">
        <div>
            <div class="header">
                <h3><?php echo Registry::load('strings')->selected_package_information ?></h3>
            </div>

            <div class="package-info">
                <div class="details">
                    <h3 class="package_name"></h3>
                    <p class="pricing">
                        <?php echo Registry::load('strings')->price ?>: <span></span>
                    </p>
                    <p class="duration"></p>
                </div>
                <span class="back-button"><?php echo Registry::load('strings')->cancel ?></span>
            </div>

            <div class="place_order">
                <div class="membership_place_order" payment_gateway_id="1">
                    <?php echo Registry::load('strings')->continue_text ?>
                </div>
            </div>

            <div class="payment-gateways">
                <h3><?php echo Registry::load('strings')->available_payment_gateways ?></h3>
                <ul></ul>
            </div>
        </div>
    </div>


</div>