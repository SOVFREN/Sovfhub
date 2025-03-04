<?php
use SleekDB\Store;
?>
<section class="membership_packages" id="membership_packages">

    <div>

        <div class="available_membership_packages">
            <div class="container">
                <div class="row">
                    <div class="col-12 available_packages">
                        <div>
                            <div class="header">
                                <div>
                                    <div class="left">
                                        <h2 class="title"><?php echo Registry::load('strings')->landing_page_packages_section_heading; ?></h2>
                                        <p>
                                            <?php echo nl2br(Registry::load('strings')->landing_page_packages_section_description); ?>
                                        </p>
                                    </div>
                                    <div class="right">
                                        <span class="previous_pricing"><?php echo Registry::load('strings')->previous ?></span>
                                        <span class="next_pricing highlight"><?php echo Registry::load('strings')->next ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="packages-container">
                                <div class="pricing-table-container">
                                    <?php
                                    $language_id = Registry::load('current_user')->language;
                                    $columns = $join = $where = null;
                                    $columns = [
                                        'membership_packages.membership_package_id', 'membership_packages.string_constant', 'membership_packages.is_recurring',
                                        'membership_packages.pricing', 'membership_packages.duration'
                                    ];
                                    $where["membership_packages.disabled[!]"] = 1;
                                    $where["ORDER"] = ["membership_packages.package_sort_index" => "ASC", "membership_packages.membership_package_id" => "DESC"];
                                    $packages = DB::connect()->select('membership_packages', $columns, $where);

                                    $index = 1;

                                    foreach ($packages as $package) {
                                        $string_constant = $package['string_constant'];
                                        if (!empty($package["is_recurring"])) {
                                            $pk_duration = Registry::load('strings')->duration.': '.Registry::load('strings')->lifetime;
                                        } else {
                                            $pk_duration = Registry::load('strings')->duration.': '.$package['duration'].' '.Registry::load('strings')->days;
                                        }
                                        ?>
                                        <div class="pricing-table" membership_package_id="<?php echo $package['membership_package_id']; ?>">
                                            <div class="pricing-head">
                                                <h3 class="package_name"><?php echo Registry::load('strings')->$string_constant; ?></h3>
                                                <span class="pricing"><?php echo Registry::load('settings')->default_currency_symbol.$package['pricing']; ?></span>
                                                <span class="duration"><?php echo $pk_duration; ?></span>
                                            </div>
                                            <div class="pricing-body">
                                                <ul>

                                                    <?php
                                                    $no_sql = new Store('membership_package_benefits', 'assets/nosql_database/');
                                                    $benefits = $no_sql->findById($package["membership_package_id"]);
                                                    $membership_url = Registry::load('config')->site_url.'membership_packages/'.$package["membership_package_id"].'/';

                                                    if (!empty($benefits)) {
                                                        $language_index = 'language_'.$language_id;
                                                        if (isset($benefits[$language_index]) && is_array($benefits[$language_index])) {
                                                            $benefits = $benefits[$language_index];

                                                            foreach ($benefits as $benefit) {
                                                                ?>
                                                                <li>
                                                                    <svg class="tick-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                                        <path fill="currentColor" d="M9.293 16.293L5.293 12.293a1 1 0 0 1 1.414-1.414L10 14.586l7.293-7.293a1 1 0 1 1 1.414 1.414l-8 8a1 1 0 0 1-1.414 0z"></path>
                                                                    </svg>
                                                                    <?php echo $benefit; ?>
                                                                </li>
                                                                <?php
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </ul>
                                                <a class="buy_now" href="<?php echo $membership_url; ?>"><?php echo Registry::load('strings')->select_plan; ?></a>
                                            </div>
                                        </div>
                                        <?php
                                    } ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>