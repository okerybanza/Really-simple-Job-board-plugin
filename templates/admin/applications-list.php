<div class="wrap">
    <h1><?php _e('Job Applications', 'simple-job-board'); ?></h1>
    
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th><?php _e('Job', 'simple-job-board'); ?></th>
                <th><?php _e('Applicant', 'simple-job-board'); ?></th>
                <th><?php _e('Email', 'simple-job-board'); ?></th>
                <th><?php _e('Date', 'simple-job-board'); ?></th>
                <th><?php _e('Status', 'simple-job-board'); ?></th>
                <th><?php _e('Actions', 'simple-job-board'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($applications as $app) : ?>
            <tr>
                <td><?php echo get_the_title($app->job_id); ?></td>
                <td><?php echo esc_html($app->applicant_name); ?></td>
                <td><?php echo esc_html($app->applicant_email); ?></td>
                <td><?php echo date_i18n(get_option('date_format'), strtotime($app->application_date)); ?></td>
                <td><?php echo ucfirst($app->status); ?></td>
                <td>
                    <a href="<?php echo admin_url("admin.php?page=job-applications&view={$app->id}"); ?>">
                        <?php _e('View', 'simple-job-board'); ?>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>