<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class SEIC_Admin_Page {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
    }

    public function add_menu_page() {
        add_menu_page(
            esc_html__( 'Image Carousel', 'seic' ),
            esc_html__( 'Image Carousel', 'seic' ),
            'manage_options',
            'seic-settings',
            array( $this, 'render_admin_page' ),
            'dashicons-images-alt2',
            60
        );
    }

    public function enqueue_assets( $hook ) {
        if ( 'toplevel_page_seic-settings' !== $hook ) {
            return;
        }

        wp_enqueue_style(
            'seic-admin',
            plugins_url( 'assets/css/admin.css', dirname( __FILE__ ) ),
            array(),
            SEIC_VERSION
        );
    }

    public function register_settings() {
        register_setting( 'seic_settings_group', 'seic_lazy_load_enabled' );
        register_setting( 'seic_settings_group', 'seic_default_speed' );
    }

    /**
     * Render the admin page
     */
    public function render_admin_page() {
        $active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'dashboard';
        $version = SEIC_VERSION;
        ?>
        <div class="seic-admin-wrapper">
            <div class="seic-sidebar">
                <div class="seic-logo">
                    <h2>Simple Carousel</h2>
                    <p class="seic-version">v<?php echo esc_html( $version ); ?></p>
                </div>
                <nav class="seic-nav">
                    <a href="?page=seic-settings&tab=dashboard" class="<?php echo $active_tab === 'dashboard' ? 'active' : ''; ?>">
                        <span class="dashicons dashicons-dashboard"></span> <?php esc_html_e( 'Dashboard', 'seic' ); ?>
                    </a>
                    <a href="?page=seic-settings&tab=features" class="<?php echo $active_tab === 'features' ? 'active' : ''; ?>">
                        <span class="dashicons dashicons-star-filled"></span> <?php esc_html_e( 'Features', 'seic' ); ?>
                    </a>
                    <a href="?page=seic-settings&tab=settings" class="<?php echo $active_tab === 'settings' ? 'active' : ''; ?>">
                        <span class="dashicons dashicons-admin-generic"></span> <?php esc_html_e( 'Settings', 'seic' ); ?>
                    </a>
                    <a href="?page=seic-settings&tab=contact" class="<?php echo $active_tab === 'contact' ? 'active' : ''; ?>">
                        <span class="dashicons dashicons-email"></span> <?php esc_html_e( 'Contact', 'seic' ); ?>
                    </a>
                </nav>
                <div class="seic-sidebar-footer">
                    <p><?php printf( esc_html__( 'By %s', 'seic' ), '<a href="https://podify.com" target="_blank">Podify Inc.</a>' ); ?></p>
                </div>
            </div>

            <div class="seic-content">
                <?php if ( $active_tab === 'dashboard' ) : ?>
                    <div class="seic-banner">
                        <div class="seic-banner-content">
                            <h1 class="seic-banner-title">
                                <?php esc_html_e( 'Welcome to Simple Carousel', 'seic' ); ?>
                                <span class="seic-banner-version">v<?php echo esc_html( $version ); ?></span>
                            </h1>
                            <p class="seic-banner-text">
                                <?php esc_html_e( 'The ultimate lightweight solution for creating high-performance image carousels in Elementor with seamless integration.', 'seic' ); ?>
                            </p>
                        </div>
                        <span class="dashicons dashicons-images-alt2 seic-banner-icon"></span>
                    </div>

                    <div class="seic-dashboard-grid">
                        <div class="seic-card seic-updater-card">
                            <div class="seic-card-header">
                                <h3 class="seic-card-title">
                                    <span class="dashicons dashicons-update"></span>
                                    <?php esc_html_e( 'Updater Status', 'seic' ); ?>
                                </h3>
                            </div>
                            <div class="seic-card-body">
                                <div class="seic-updater-status">
                                    <div class="seic-status-info">
                                        <p class="seic-status-badge">
                                            <strong><?php esc_html_e( 'Status:', 'seic' ); ?></strong>
                                            <span class="seic-status-up-to-date"><?php esc_html_e( 'UP TO DATE', 'seic' ); ?></span>
                                        </p>
                                        <p class="seic-version-info">
                                            <?php printf( esc_html__( 'You are on the latest version (%s).', 'seic' ), esc_html( $version ) ); ?>
                                        </p>
                                        <p class="seic-update-check">
                                            <?php printf( esc_html__( 'Last checked: %s', 'seic' ), date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) ) ); ?>
                                        </p>
                                    </div>
                                    <div class="seic-updater-actions">
                                        <button type="button" class="seic-btn-outline seic-check-updates">
                                            <span class="dashicons dashicons-update"></span>
                                            <?php esc_html_e( 'Check Now', 'seic' ); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="seic-card">
                            <div class="seic-card-header">
                                <h3 class="seic-card-title">
                                    <span class="dashicons dashicons-yes"></span>
                                    <?php esc_html_e( 'Key Features', 'seic' ); ?>
                                </h3>
                            </div>
                            <div class="seic-card-body">
                                <ul class="seic-features-list-simple">
                                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Elementor Widget Integration', 'seic' ); ?></li>
                                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Lightweight Swiper.js Engine', 'seic' ); ?></li>
                                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Fully Responsive Layouts', 'seic' ); ?></li>
                                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Optimized Asset Loading', 'seic' ); ?></li>
                                    <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Lazy Loading Support', 'seic' ); ?></li>
                                </ul>
                            </div>
                        </div>

                        <div class="seic-card">
                            <div class="seic-card-header">
                                <h3 class="seic-card-title">
                                    <span class="dashicons dashicons-grid-view"></span>
                                    <?php esc_html_e( 'Quick Actions', 'seic' ); ?>
                                </h3>
                            </div>
                            <div class="seic-card-body">
                                <div class="seic-quick-actions">
                                    <a href="#" class="seic-action-btn">
                                        <span class="dashicons dashicons-media-document"></span>
                                        <span><?php esc_html_e( 'Docs', 'seic' ); ?></span>
                                    </a>
                                    <a href="?page=seic-settings&tab=contact" class="seic-action-btn">
                                        <span class="dashicons dashicons-email"></span>
                                        <span><?php esc_html_e( 'Contact', 'seic' ); ?></span>
                                    </a>
                                    <a href="?page=seic-settings&tab=settings" class="seic-action-btn">
                                        <span class="dashicons dashicons-admin-generic"></span>
                                        <span><?php esc_html_e( 'Settings', 'seic' ); ?></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="seic-card seic-details-card">
                        <div class="seic-card-header">
                            <h3 class="seic-card-title">
                                <span class="dashicons dashicons-info"></span>
                                <?php esc_html_e( 'Plugin Details', 'seic' ); ?>
                            </h3>
                        </div>
                        <div class="seic-card-body">
                            <div class="seic-details-grid">
                                <p><strong><?php esc_html_e( 'Version:', 'seic' ); ?></strong> <?php echo esc_html( $version ); ?></p>
                                <p><strong><?php esc_html_e( 'Author:', 'seic' ); ?></strong> Podify Inc.</p>
                                <p><strong><?php esc_html_e( 'License:', 'seic' ); ?></strong> Free</p>
                                <p><?php printf( esc_html__( 'Need help? Check the %s or contact us.', 'seic' ), '<a href="#">' . esc_html__( 'Changelog', 'seic' ) . '</a>' ); ?></p>
                            </div>
                        </div>
                    </div>
                <?php elseif ( $active_tab === 'features' ) : ?>
                    <div class="seic-section-header">
                        <h2><?php esc_html_e( 'Available Features', 'seic' ); ?></h2>
                        <p><?php esc_html_e( 'Explore the powerful features included in Simple Carousel.', 'seic' ); ?></p>
                    </div>
                    <div class="seic-features-grid">
                        <div class="seic-feature-item">
                            <span class="dashicons dashicons-performance"></span>
                            <h3><?php esc_html_e( 'High Performance', 'seic' ); ?></h3>
                            <p><?php esc_html_e( 'Minimal CSS and JS footprint for lightning-fast loading.', 'seic' ); ?></p>
                        </div>
                        <div class="seic-feature-item">
                            <span class="dashicons dashicons-smartphone"></span>
                            <h3><?php esc_html_e( 'Mobile First', 'seic' ); ?></h3>
                            <p><?php esc_html_e( 'Fully responsive design that looks great on all devices.', 'seic' ); ?></p>
                        </div>
                        <div class="seic-feature-item">
                            <span class="dashicons dashicons-admin-generic"></span>
                            <h3><?php esc_html_e( 'Easy to Use', 'seic' ); ?></h3>
                            <p><?php esc_html_e( 'Intuitive Elementor controls for seamless customization.', 'seic' ); ?></p>
                        </div>
                    </div>
                <?php elseif ( $active_tab === 'settings' ) : ?>
                    <div class="seic-section-header">
                        <h2><?php esc_html_e( 'Plugin Settings', 'seic' ); ?></h2>
                        <p><?php esc_html_e( 'Configure the global behavior of the Simple Carousel plugin.', 'seic' ); ?></p>
                    </div>
                    <form method="post" action="options.php" class="seic-settings-form">
                        <?php
                        settings_fields( 'seic_settings_group' );
                        do_settings_sections( 'seic-settings' );
                        submit_button();
                        ?>
                    </form>
                <?php elseif ( $active_tab === 'contact' ) : ?>
                    <div class="seic-section-header">
                        <h2><?php esc_html_e( 'Contact Us', 'seic' ); ?></h2>
                        <p><?php esc_html_e( 'Need help? We are here for you.', 'seic' ); ?></p>
                    </div>
                    <div class="seic-card">
                        <div class="seic-card-body">
                            <p><?php esc_html_e( 'If you encounter any issues or have questions, please contact our team.', 'seic' ); ?></p>
                            <a href="https://podify.com/contact" target="_blank" class="button button-primary"><?php esc_html_e( 'Contact Us', 'seic' ); ?></a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}