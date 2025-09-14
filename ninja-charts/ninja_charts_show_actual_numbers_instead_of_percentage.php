<?php

/**
 * Customize Ninja Charts to show actual numbers instead of percentages
 * 
 * This function adds custom JavaScript to override the Chart.js data labels formatter
 * to display actual values instead of percentages.
 */
function ninja_charts_show_actual_numbers_instead_of_percentage()
{
    $custom_js = "
    jQuery(document).ready(function() {
        console.log('Ninja Charts customization script loaded');
        
        // Hide charts initially to prevent flicker
        jQuery('.ninja-charts-chart-js-container').css('opacity', '0');
        
        // Function to customize charts
        function customizeNinjaCharts() {
            let charts = jQuery('.ninja-charts-chart-js-container');
            console.log('Found charts:', charts.length);
            
            if (charts.length > 0) {
                // Override the original Chart.js data labels configuration
                if (window.Chart && window.ChartDataLabels) {
                    console.log('Chart.js and ChartDataLabels are available');
                    
                    // Process charts immediately with minimal delay
                    setTimeout(function() {
                        let processed = 0;
                        charts.each(function() {
                            let chartContainer = jQuery(this);
                            let chartId = chartContainer.data('id');
                            let uniqid = chartContainer.data('uniqid');
                            let canvasDom = 'ninja_charts_instance' + uniqid;
                            let canvas = document.getElementById(canvasDom);
                            
                            console.log('Processing chart:', chartId, canvasDom);
                            
                            if (canvas) {
                                let existingChart = Chart.getChart(canvas);
                                if (existingChart) {
                                    console.log('Found existing chart, updating data labels');
                                    
                                    // Update the datalabels plugin configuration
                                    if (existingChart.options.plugins && existingChart.options.plugins.datalabels) {
                                        existingChart.options.plugins.datalabels.formatter = function(value, context) {
                                            console.log('Custom formatter called with value:', value);
                                            return value; // Return actual number instead of percentage
                                        };
                                        
                                        // Update the chart without animation for instant change
                                        existingChart.options.animation = false;
                                        existingChart.update('none'); // Use 'none' mode for instant update
                                        
                                        console.log('Chart updated with actual numbers');
                                    }
                                    
                                    // Show the chart after processing
                                    chartContainer.css('opacity', '1');
                                    processed++;
                                } else {
                                    // If chart not ready yet, show it anyway to prevent permanent hiding
                                    setTimeout(function() {
                                        chartContainer.css('opacity', '1');
                                    }, 100);
                                }
                            } else {
                                // If canvas not found, show container anyway
                                setTimeout(function() {
                                    chartContainer.css('opacity', '1');
                                }, 100);
                            }
                        });
                        
                        console.log('Processed charts:', processed);
                        
                        // Ensure all charts are visible after processing
                        setTimeout(function() {
                            jQuery('.ninja-charts-chart-js-container').css('opacity', '1');
                        }, 200);
                        
                    }, 50); // Reduced delay for faster processing
                } else {
                    console.log('Chart.js or ChartDataLabels not available yet, retrying...');
                    // Show charts if libraries aren't available to prevent permanent hiding
                    setTimeout(function() {
                        jQuery('.ninja-charts-chart-js-container').css('opacity', '1');
                    }, 500);
                    setTimeout(customizeNinjaCharts, 100);
                }
            } else {
                // No charts found, but ensure any hidden charts become visible
                setTimeout(function() {
                    jQuery('.ninja-charts-chart-js-container').css('opacity', '1');
                }, 200);
            }
        }
        
        // Try to customize charts quickly
        customizeNinjaCharts();
        
        // Backup: ensure charts are always visible after 1 second
        setTimeout(function() {
            jQuery('.ninja-charts-chart-js-container').css('opacity', '1');
        }, 1000);
        
        // Also try when new content is loaded (for AJAX/dynamic content)
        jQuery(document).on('DOMNodeInserted', function(e) {
            if (jQuery(e.target).find('.ninja-charts-chart-js-container').length > 0) {
                console.log('New chart detected, customizing...');
                jQuery(e.target).find('.ninja-charts-chart-js-container').css('opacity', '0');
                setTimeout(customizeNinjaCharts, 50);
            }
        });
    });
    ";

    // Add the custom JavaScript
    wp_add_inline_script('jquery', $custom_js);
}

// Hook into wp_enqueue_scripts to ensure it runs on every page
add_action('wp_enqueue_scripts', 'ninja_charts_show_actual_numbers_instead_of_percentage', 999);

// Also hook into the ninja charts specific action if it exists
add_action('ninja_charts_shortcode_assets_loaded', 'ninja_charts_show_actual_numbers_instead_of_percentage', 999);
