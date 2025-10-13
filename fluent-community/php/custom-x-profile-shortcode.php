<?php

function custom_fluent_profile_shortcode($atts)
{
    $atts = shortcode_atts(array(
        'user_id' => get_current_user_id(),
        'style' => 'full'
    ), $atts);

    // Check if FluentCommunity is active
    if (!class_exists('FluentCommunity\App\Models\User') || !class_exists('FluentCommunity\App\Models\XProfile')) {
        return '<div class="fluent-error">FluentCommunity plugin is not active.</div>';
    }

    try {
        // Use FluentCommunity models to get user data
        $user = \FluentCommunity\App\Models\User::find($atts['user_id']);
        if (!$user) {
            return '<div class="fluent-error">User not found.</div>';
        }

        $xprofile = \FluentCommunity\App\Models\XProfile::where('user_id', $atts['user_id'])->first();

        // Get user statistics
        $feed_count = \FluentCommunity\App\Models\Feed::where('user_id', $user->ID)->count();
        $comment_count = \FluentCommunity\App\Models\Comment::where('user_id', $user->ID)->count();
        $reaction_count = \FluentCommunity\App\Models\Reaction::where('user_id', $user->ID)->count();

        // Get user spaces/groups
        $user_spaces = \FluentCommunity\App\Models\SpaceUserPivot::where('user_id', $user->ID)
            ->with('space')
            ->limit(5)
            ->get();

        // Build enhanced output with modern design
        $output = '<div class="custom-fluent-profile profile-' . esc_attr($atts['style']) . '">';

        // Header Section
        $output .= '<div class="profile-header">';

        // Avatar
        $avatar_url = get_avatar_url($user->ID, ['size' => 100]);
        $output .= '<div class="profile-avatar">';
        $output .= '<img src="' . esc_url($avatar_url) . '" alt="' . esc_attr($user->display_name) . '">';
        $output .= '</div>';

        // User Info
        $output .= '<div class="profile-info">';
        $output .= '<h3 class="profile-name">' . esc_html($user->display_name) . '</h3>';
        $output .= '<p class="profile-username">@' . esc_html($user->user_login) . '</p>';

        // Join date
        if ($xprofile && $xprofile->created_at) {
            $join_date = human_time_diff(strtotime($xprofile->created_at), current_time('timestamp'));
            $output .= '<p class="profile-joined"><i class="dashicons dashicons-calendar-alt"></i> Joined ' . esc_html($join_date) . ' ago</p>';
        }

        $output .= '</div>'; // Close profile-info
        $output .= '</div>'; // Close profile-header

        // Bio Section
        if ($xprofile && !empty($xprofile->bio)) {
            $output .= '<div class="profile-bio">';
            $output .= '<h4><i class="dashicons dashicons-admin-users"></i> About</h4>';
            $output .= '<p>' . wp_kses_post($xprofile->bio) . '</p>';
            $output .= '</div>';
        }

        // Stats Section
        $output .= '<div class="profile-stats">';
        $output .= '<h4><i class="dashicons dashicons-chart-bar"></i> Community Stats</h4>';
        $output .= '<div class="stats-grid">';
        $output .= '<div class="stat-item"><span class="stat-number">' . intval($feed_count) . '</span><span class="stat-label">Posts</span></div>';
        $output .= '<div class="stat-item"><span class="stat-number">' . intval($comment_count) . '</span><span class="stat-label">Comments</span></div>';
        $output .= '<div class="stat-item"><span class="stat-number">' . intval($reaction_count) . '</span><span class="stat-label">Reactions</span></div>';
        $output .= '</div>';
        $output .= '</div>';

        // Spaces/Groups Section
        if (!$user_spaces->isEmpty()) {
            $output .= '<div class="profile-spaces">';
            $output .= '<h4><i class="dashicons dashicons-groups"></i> Member of ' . count($user_spaces) . ' Space(s)</h4>';
            $output .= '<div class="spaces-list">';
            foreach ($user_spaces as $space_pivot) {
                if ($space_pivot->space) {
                    $output .= '<span class="space-badge">' . esc_html($space_pivot->space->title) . '</span>';
                }
            }
            $output .= '</div>';
            $output .= '</div>';
        }

        // Contact Info (if available)
        if ($user->user_email && !empty($user->user_email)) {
            $output .= '<div class="profile-contact">';
            $output .= '<h4><i class="dashicons dashicons-email"></i> Contact</h4>';
            $output .= '<p class="profile-email"><i class="dashicons dashicons-email-alt"></i> ' . esc_html($user->user_email) . '</p>';
            $output .= '</div>';
        }

        $output .= '</div>'; // Close main container

        // Enhanced CSS Styling
        $output .= '<style>
			.custom-fluent-profile {
				max-width: 600px;
				background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
				border-radius: 20px;
				padding: 0;
				margin: 30px auto;
				box-shadow: 0 20px 40px rgba(102, 126, 234, 0.2);
				overflow: hidden;
				color: #333;
				font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
			}
			
			.profile-header {
				background: rgba(255, 255, 255, 0.95);
				padding: 30px;
				text-align: center;
				position: relative;
			}
			
			.profile-avatar {
				margin-bottom: 20px;
			}
			
			.profile-avatar img {
				margin: 0 auto;
				width: 100px;
				height: 100px;
				border-radius: 50%;
				border: 4px solid #fff;
				box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
				transition: transform 0.3s ease;
			}
			
			.profile-avatar img:hover {
				transform: scale(1.05);
			}
			
			.profile-name {
				margin: 0 0 5px 0;
				font-size: 1.8em;
				font-weight: 700;
				color: #2c3e50;
			}
			
			.profile-username {
				margin: 0 0 10px 0;
				color: #7f8c8d;
				font-size: 1.1em;
			}
			
			.profile-joined {
				margin: 10px 0 0 0;
				color: #95a5a6;
				font-size: 0.9em;
				display: flex;
				align-items: center;
				justify-content: center;
				gap: 5px;
			}
			
			.profile-bio,
			.profile-stats,
			.profile-spaces,
			.profile-contact {
				background: rgba(255, 255, 255, 0.95);
				margin: 2px 0 0 0;
				padding: 25px 30px;
			}
			
			.profile-bio h4,
			.profile-stats h4,
			.profile-spaces h4,
			.profile-contact h4 {
				margin: 0 0 15px 0;
				color: #2c3e50;
				font-size: 1.2em;
				display: flex;
				align-items: center;
				gap: 8px;
			}
			
			.profile-bio p {
				margin: 0;
				line-height: 1.6;
				color: #34495e;
			}
			
			.stats-grid {
				display: grid;
				grid-template-columns: repeat(3, 1fr);
				gap: 20px;
				margin-top: 15px;
			}
			
			.stat-item {
				text-align: center;
				padding: 20px;
				background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
				border-radius: 12px;
				color: white;
				transition: transform 0.3s ease;
			}
			
			.stat-item:hover {
				transform: translateY(-3px);
			}
			
			.stat-number {
				display: block;
				font-size: 2em;
				font-weight: 800;
				margin-bottom: 5px;
			}
			
			.stat-label {
				font-size: 0.9em;
				opacity: 0.9;
				text-transform: uppercase;
				letter-spacing: 1px;
			}
			
			.spaces-list {
				display: flex;
				flex-wrap: wrap;
				gap: 10px;
				margin-top: 15px;
			}
			
			.space-badge {
				background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
				color: white;
				padding: 8px 15px;
				border-radius: 20px;
				font-size: 0.9em;
				font-weight: 500;
			}
			
			.profile-email {
				margin: 10px 0 0 0;
				display: flex;
				align-items: center;
				gap: 8px;
				color: #34495e;
			}
			
			.fluent-error {
				background: #e74c3c;
				color: white;
				padding: 15px;
				border-radius: 8px;
				text-align: center;
				margin: 20px 0;
			}
			
			.dashicons {
				width: 20px;
				height: 20px;
				font-size: 20px;
			}
			
			/* Compact Style */
			.profile-compact {
				max-width: 400px;
			}
			
			.profile-compact .profile-header {
				padding: 20px;
			}
			
			.profile-compact .profile-avatar img {
				width: 70px;
				height: 70px;
			}
			
			.profile-compact .stats-grid {
				grid-template-columns: repeat(3, 1fr);
				gap: 10px;
			}
			
			.profile-compact .stat-item {
				padding: 15px 10px;
			}
			
			.profile-compact .stat-number {
				font-size: 1.5em;
			}
			
			/* Mobile Responsive */
			@media (max-width: 768px) {
				.custom-fluent-profile {
					margin: 20px 10px;
				}
				
				.stats-grid {
					grid-template-columns: 1fr;
					gap: 10px;
				}
				
				.spaces-list {
					justify-content: center;
				}
			}
		</style>';

        return $output;
    } catch (Exception $e) {
        return '<div class="fluent-error">Error loading profile: ' . esc_html($e->getMessage()) . '</div>';
    }
}
add_shortcode('custom_fluent_profile', 'custom_fluent_profile_shortcode');
