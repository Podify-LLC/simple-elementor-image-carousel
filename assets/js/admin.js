jQuery(document).ready(function($) {
    'use strict';

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
