<div class="sjb-dashboard">
    <div class="sjb-dashboard-header">
        <h2><?php _e('Your Job Listings', 'simple-job-board'); ?></h2>
        <a href="<?php echo esc_url(admin_url('post-new.php?post_type=sjb_job')); ?>" class="sjb-button">
            <?php _e('Add New Job', 'simple-job-board'); ?>
        </a>
    </div>

    <div class="sjb-dashboard-stats">
        <div class="sjb-stat-card">
            <span class="sjb-stat-number"><?php echo count(SJB_Dashboard::get_user_jobs(get_current_user_id())); ?></span>
            <span class="sjb-stat-label"><?php _e('Total Jobs', 'simple-job-board'); ?></span>
        </div>
    </div>

    <table class="sjb-jobs-table">
        <thead>
            <tr>
                <th><?php _e('Job Title', 'simple-job-board'); ?></th>
                <th><?php _e('Applications', 'simple-job-board'); ?></th>
                <th><?php _e('Status', 'simple-job-board'); ?></th>
                <th><?php _e('Actions', 'simple-job-board'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (SJB_Dashboard::get_user_jobs(get_current_user_id()) as $job): ?>
            <tr>
                <td><?php echo esc_html($job->post_title); ?></td>
                <td>
                    <?php 
                    $apps = SJB_Job_Applications::count_for_job($job->ID);
                    echo $apps ? sprintf(_n('%d application', '%d applications', $apps), $apps) : '—';
                    ?>
                </td>
                <td>
                    <span class="sjb-status-badge sjb-status-<?php echo esc_attr(get_post_meta($job->ID, '_sjb_status', true) ?: 'open'); ?>">
                        <?php echo esc_html(ucfirst(get_post_meta($job->ID, '_sjb_status', true) ?: 'open')); ?>
                    </span>
                </td>
                <td>
                    <a href="<?php echo get_edit_post_link($job->ID); ?>" class="sjb-action-link">
                        <?php _e('Edit', 'simple-job-board'); ?>
                    </a>
                    <a href="<?php echo get_permalink($job->ID); ?>" class="sjb-action-link">
                        <?php _e('View', 'simple-job-board'); ?>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>