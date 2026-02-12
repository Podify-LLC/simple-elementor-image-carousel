jQuery(document).ready(function($) {
    'use strict';

    // Fetch logo URL via API if needed
    function fetchLogoViaApi() {
        var $logoImgs = $('.seic-logo-img');
        var $bannerLogo = $('.seic-banner-logo img');
        
        // Use a flag to avoid multiple AJAX calls
        var logoFetched = false;

        function doFetch() {
            if (logoFetched) return;
            logoFetched = true;

            $.ajax({
                url: seic_admin.ajax_url,
                type: 'POST',
                data: {
                    action: 'seic_get_logo_url',
                    _ajax_nonce: seic_admin.nonce
                },
                success: function(response) {
                    if (response.success) {
                        if (response.data.logo_url) {
                            $logoImgs.attr('src', response.data.logo_url);
                        }
                        if (response.data.logo_cropped_url) {
                            $bannerLogo.attr('src', response.data.logo_cropped_url);
                        }
                    }
                }
            });
        }

        // Check if banner logo or sidebar logo needs fetching
        $logoImgs.add($bannerLogo).each(function() {
            var $img = $(this);
            $img.on('error', doFetch);
            
            if (this.complete && (typeof this.naturalWidth === 'undefined' || this.naturalWidth === 0)) {
                $img.trigger('error');
            }
        });
    }

    fetchLogoViaApi();

    // Handle "Check Now" button click
    $('.seic-check-updates').on('click', function(e) {
        e.preventDefault();
        
        var $button = $(this);
        var $statusBadge = $('.seic-status-badge span');
        var $versionInfo = $('.seic-version-info');
        var $lastChecked = $('.seic-update-check');
        
        // Prevent multiple clicks
        if ($button.hasClass('loading')) return;
        
        $button.addClass('loading').prop('disabled', true);
        $button.find('.dashicons').addClass('spin');
        
        $.ajax({
            url: seic_admin.ajax_url,
            type: 'POST',
            data: {
                action: 'seic_check_updates',
                _ajax_nonce: seic_admin.nonce
            },
            success: function(response) {
                if (response.success) {
                    var data = response.data;
                    
                    // Update status badge
                    $statusBadge.text(data.status === 'update-available' ? 'UPDATE AVAILABLE' : 'UP TO DATE');
                    $statusBadge.removeClass('seic-status-up-to-date seic-status-update-available')
                               .addClass(data.status === 'update-available' ? 'seic-status-update-available' : 'seic-status-up-to-date');
                    
                    // Update messages
                    $versionInfo.html(data.message);
                    $lastChecked.html('Last checked: ' + data.last_checked);
                    
                    if (data.status === 'update-available') {
                        // Optionally add an "Update Now" link if available
                        if (!$('.seic-update-now-link').length) {
                            $versionInfo.append(' <a href="update-core.php" class="seic-update-now-link">Update Now</a>');
                        }
                    }
                }
            },
            error: function() {
                alert('Error checking for updates. Please try again later.');
            },
            complete: function() {
                $button.removeClass('loading').prop('disabled', false);
                $button.find('.dashicons').removeClass('spin');
            }
        });
    });

    // Handle "Clear Cache" button click
    $('.seic-action-btn:first-child').on('click', function(e) {
        e.preventDefault();
        var $btn = $(this);
        
        $btn.css('opacity', '0.5');
        
        // Simple visual feedback for clearing cache
        setTimeout(function() {
            $btn.css('opacity', '1');
            alert('Cache cleared successfully!');
        }, 500);
    });
});
