<div class="wrap">

    <h1>EstateAgent.Me XML</h1>

    <div class="container">
        <div class="ea-notice" style="float:right;max-width:40%;">
            <h2>
                Instructions / Setup
            </h2>
            <ol>
                <li style="margin-bottom:20px;">
                    Enter <strong>Agent ID</strong> and <strong>Authentication Key</strong> as generated from EstateAgent.Me
                </li>

                <li style="margin-bottom:20px;">
                    Generate a <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank"><strong>Google Maps API Key</strong></a> &amp; enter it in the field on this page.
                </li>

                <li style="margin-bottom:20px;">
                    Go to the <a href="options-permalink.php">Permalinks Page</a>,<br />
                    Ensure <strong>Plain is <em>NOT</em></strong> selected,<br />
                    And hit <strong>Save Changes</strong><br />
                    <span style="opacity:.6">(This refreshes your permalink structure in order to view Property Details pages, which is generated with SEO-friendly URL's)</span>
                </li>

                <li>
                    Install <a href="plugin-install.php?s=wp+control&tab=search&type=term" target="_blank">WP Control</a> Plugin &amp; Activate it.<br />
                    Then, under <strong>Settings > Cron Schedules > Manage Cron Events</strong> enter a new Cron Event.<br />
                    Hook Name = <strong>estateagentme-cron-update</strong><br />
                    Recurrence = <strong>Once Hourly</strong>
                </li>
            </ol>
        </div>

        <form method="post" action="options.php" style="float:left;max-width:50%;">
            <?php settings_fields( 'ea-settings' ); ?>
            <?php do_settings_sections( 'ea-settings' ); ?>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row">
                        EstateAgent.Me Agent ID
                    </th>
                    <td>
                        <input type="text" name="ea-agent-id" style="width:150px" value="<?php echo esc_attr( get_option('ea-agent-id') ); ?>" />
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">
                        Authentication Key
                    </th>
                    <td>
                        <input type="text" name="ea-auth-key" style="width:100%" value="<?php echo esc_attr( get_option('ea-auth-key') ); ?>" />
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">
                        Google Maps API Key
                    </th>
                    <td>
                        <input type="text" name="ea-google-maps-api-key" style="width:100%" value="<?php echo esc_attr( get_option('ea-google-maps-api-key') ); ?>" />
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">
                        Default property search selection
                    </th>
                    <td>
                        <select name="ea-default-search-selection" style="width:50%">
                            <option value="sale" <?php echo (esc_attr(get_option('ea-default-search-selection')) && esc_attr(get_option('ea-default-search-selection')) == 'sale' ? 'selected="selected"' : ''); ?>>For sale</option>
                            <option value="rent" <?php echo (esc_attr(get_option('ea-default-search-selection')) && esc_attr(get_option('ea-default-search-selection')) == 'rent' ? 'selected="selected"' : ''); ?>>For rent</option>
                        </select>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">
                        Accent Colour
                    </th>
                    <td>
                        <input type="text" value="<?php echo (get_option('ea-accent-colour') ? esc_attr(get_option('ea-accent-colour')) : '#dd9933' ); ?>" class="colour-picker" name="ea-accent-colour" data-default-color="#dd9933" />
                        <p class="description">The primary colour that is used throughout<br />
                            &nbsp;&nbsp;&nbsp;(eg, Button background colour, text link colour, etc)</p>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">
                        Accent Colour Alt
                    </th>
                    <td>
                        <input type="text" value="<?php echo (get_option('ea-accent-colour-alt') ? esc_attr(get_option('ea-accent-colour-alt')) : '#ffffff' ); ?>" class="colour-picker" name="ea-accent-colour-alt" data-default-color="#ffffff" />
                        <p class="description">The alternate colour to your Accent Colour<br />
                            &nbsp;&nbsp;&nbsp;(eg, Button text colour, etc)</p>
                    </td>
                </tr>
            </table>

            <?php submit_button(); ?>

        </form>
        
        <div style="display: block; visibility: hidden; clear: both; height: 0;"></div>
    </div>

    <div style="display: block; visibility: hidden; clear: both; height: 0;"></div>

    <?php if (isset($_GET['manual-run'])): ?>
        <hr />
        <div id="run-iframw-w">
            <p style="position:absolute;">Loading...</p>
            <iframe style="position:relative;" src="/wp-admin/options-general.php?page=estateagentme&run=1" width="100%" height="1500"></iframe>
        </div>
    <?php endif; ?>

</div>