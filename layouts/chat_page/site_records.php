<div class="search">
    <i class="iconic_search_old">
        <svg version="1.1" style="margin-top: -8px; margin-left: -2px;" xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 1024 1024">
            <path fill="currentColor" d="M991.284 932.702l-243.714-253.474c62.663-74.491 96.997-168.216 96.997-265.788 0-227.967-185.473-413.44-413.44-413.44s-413.44 185.473-413.44 413.44 185.473 413.44 413.44 413.44c85.582 0 167.137-25.813 236.865-74.815l245.565 255.398c10.264 10.66 24.069 16.538 38.863 16.538 14.003 0 27.287-5.339 37.371-15.046 21.427-20.618 22.11-54.808 1.492-76.253zM431.128 107.854c168.504 0 305.586 137.082 305.586 305.586s-137.082 305.586-305.586 305.586-305.586-137.082-305.586-305.586 137.082-305.586 305.586-305.586z"></path>
        </svg>
    </i>
    <input type="search" placeholder='<?php echo(Registry::load('strings')->search_here) ?>' />
</div>

<div class="d-none create_ajx_request"></div>
<div class="current_record d-none">

    <label class="selector selected select_all d-none">
        <input type="checkbox" name="select_all" value="all" />
        <span class="checkmark"></span>
    </label>

    <div class="title">
        <div>
            <span class="text"></span>
            <span class="filter"></span>
            <div class="dropdown_list">
                <ul></ul>
            </div>
        </div>
    </div>

    <div class="options">

        <div class="toggle_checkbox">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 1024 1024">
                <path fill="currentColor" d="M111.531 87.765c-9.351 2.542-16.968 8.642-21.456 16.719l-0.090 0.177-3.797 7.125v331.093l4.267 7.595c4.693 8.405 12.544 14.507 21.973 17.109 8.448 2.347 321.365 2.347 329.813 0 9.493-2.619 17.237-8.778 21.88-16.932l0.093-0.177 4.267-7.595v-331.093l-3.797-7.125c-4.523-8.405-11.477-13.739-22.187-16.981-11.179-3.371-320.213-3.285-330.965 0.085zM580.864 130.432c-9.351 2.542-16.968 8.642-21.456 16.719l-0.090 0.177c-3.157 5.931-3.797 9.856-3.797 23.339 0 14.123 0.555 17.195 4.267 23.808 4.693 8.405 12.544 14.507 21.973 17.109 8.448 2.347 321.365 2.347 329.813 0 9.493-2.619 17.237-8.778 21.88-16.932l0.093-0.177c3.712-6.656 4.267-9.643 4.224-23.808-0.043-18.475-3.456-27.52-12.843-34.176-12.075-8.619-9.771-8.491-178.944-8.363-124.715 0.085-159.531 0.555-165.12 2.304zM384 277.333v106.667h-213.333v-213.333h213.333v106.667zM580.864 343.765c-9.351 2.542-16.968 8.642-21.456 16.719l-0.090 0.177c-3.157 5.931-3.797 9.856-3.797 23.339 0 14.123 0.555 17.195 4.267 23.808 4.693 8.405 12.544 14.507 21.973 17.109 8.448 2.347 321.365 2.347 329.813 0 9.493-2.619 17.237-8.778 21.88-16.932l0.093-0.177c3.712-6.656 4.267-9.643 4.224-23.808-0.043-18.475-3.456-27.52-12.843-34.176-12.075-8.619-9.771-8.491-178.944-8.363-124.715 0.085-159.531 0.555-165.12 2.304zM111.531 557.099c-9.351 2.542-16.968 8.642-21.456 16.719l-0.090 0.177-3.797 7.125v331.093l4.267 7.595c4.693 8.405 12.544 14.507 21.973 17.109 8.448 2.347 321.365 2.347 329.813 0 9.493-2.619 17.237-8.778 21.88-16.932l0.093-0.177 4.267-7.595v-331.093l-3.797-7.125c-4.523-8.405-11.477-13.739-22.187-16.981-11.179-3.371-320.213-3.285-330.965 0.085zM580.864 599.765c-9.351 2.542-16.968 8.642-21.456 16.719l-0.090 0.177c-3.157 5.931-3.797 9.856-3.797 23.339 0 14.123 0.555 17.195 4.267 23.808 4.693 8.405 12.544 14.507 21.973 17.109 8.448 2.347 321.365 2.347 329.813 0 9.493-2.619 17.237-8.778 21.88-16.932l0.093-0.177c3.712-6.656 4.267-9.643 4.224-23.808-0.043-18.475-3.456-27.52-12.843-34.176-12.075-8.619-9.771-8.491-178.944-8.363-124.715 0.085-159.531 0.555-165.12 2.304zM384 746.667v106.667h-213.333v-213.333h213.333v106.667zM580.864 813.099c-9.351 2.542-16.968 8.642-21.456 16.719l-0.090 0.177c-3.157 5.931-3.797 9.856-3.797 23.339 0 14.123 0.555 17.195 4.267 23.808 4.693 8.405 12.544 14.507 21.973 17.109 8.448 2.347 321.365 2.347 329.813 0 9.493-2.619 17.237-8.778 21.88-16.932l0.093-0.177c3.712-6.656 4.267-9.643 4.224-23.808-0.043-18.475-3.456-27.52-12.843-34.176-12.075-8.619-9.771-8.491-178.944-8.363-124.715 0.085-159.531 0.555-165.12 2.304z"></path>
            </svg>
        </div>

        <div class="sort dropdown_button d-none">
            <span>
                <span></span>
                <i class="iconic_arrow-down_old">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 1024 1024">
                        <path fill="currentColor" d="M511.999 771.854c-16.34 0-32.678-6.239-45.134-18.692l-392.021-392.025c-24.937-24.937-24.937-65.37 0-90.297 24.929-24.929 65.351-24.929 90.29 0l346.864 346.887 346.867-346.873c24.937-24.929 65.358-24.929 90.284 0 24.95 24.929 24.95 65.36 0 90.297l-392.014 392.025c-12.463 12.455-28.801 18.679-45.134 18.679z"></path>
                    </svg>
                </i>
            </span>
            <div class="sort_by_list">
                <div class="dropdown_list">
                    <ul></ul>
                </div>
            </div>
        </div>
    </div>

    <div class="record_info d-none">
        <div class="offset">
            <textarea name="current_record_offset" class="current_record_offset"></textarea>
        </div>
        <div class="filter">
            <input type="text" name="current_record_filter" value='' class="current_record_filter" />
        </div>
        <div class="sort_by">
            <input type="text" name="current_record_sort_by" value='' class="current_record_sort_by" />
        </div>
        <div class="search_keyword">
            <input type="text" name="current_record_search_keyword" value='' class="current_record_search_keyword" />
        </div>
        <div class="data_attributes"></div>
        <div class="refresh_current_record"></div>
    </div>

</div>
<div class="confirm_box d-none animate__animated animate__flipInX">
    <div class="error">
        <span class="message"><?php echo(Registry::load('strings')->error) ?> : <span></span></span>
    </div>
    <div class="content">
        <span class="text"></span>
        <span class="btn cancel"><span></span></span>
        <span class="btn submit"><span></span></span>
    </div>
</div>

<div class="records">

    <div class="zero_results d-none">
        <div>
            <div class="image">
                <img class="lazy" data-src="<?php echo Registry::load('config')->site_url ?>assets/files/defaults/no_results_found.webp" />
            </div>
            <div class="text">
                <span class="title"><?php echo(Registry::load('strings')->no_results_found) ?></span>
                <span class="subtitle"><?php echo(Registry::load('strings')->no_results_found_subtitle) ?></span>
            </div>
        </div>
    </div>

    <div class="on_error d-none">
        <div class="content">
            <div>
                <span class="error_image">
                    <img class="lazy" data-src="<?php echo Registry::load('config')->site_url ?>assets/files/defaults/error_image.webp" />
                </span>
                <span class="text">
                    <span class="title"><?php echo(Registry::load('strings')->error) ?></span>
                    <span class="subtitle"><?php echo Registry::load('strings')->error_message ?></span>
                </span>
            </div>
        </div>
    </div>

    <div class="loader aside_loader">
        <ul></ul>
    </div>
    <div class='dragfile dragupload'>
        <div>
            <div>
                <div class="icon"></div>
            </div>
        </div>
    </div>

    <ul class='list'>

    </ul>
</div>

<div class="tools">
    <div class="tool multiple_selection d-none">
        <span></span>
    </div>
    <div class="tool todo d-none">
        <span class="animate__animated animate__flipInY"></span>
    </div>
</div>