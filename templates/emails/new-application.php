<!DOCTYPE html>
<html>
<head>
    <title><?php _e('New Job Application', 'simple-job-board'); ?></title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2271b1; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; border: 1px solid #ddd; }
        .footer { margin-top: 20px; text-align: center; font-size: 0.8em; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?php _e('New Job Application', 'simple-job-board'); ?></h1>
        </div>
        
        <div class="content">
            <h2><?php echo get_the_title($data['job_id']); ?></h2>
            
            <p><strong><?php _e('Applicant Name:', 'simple-job-board'); ?></strong> <?php echo $data['name']; ?></p>
            <p><strong><?php _e('Email:', 'simple-job-board'); ?></strong> <?php echo $data['email']; ?></p>
            <p><strong><?php _e('Phone:', 'simple-job-board'); ?></strong> <?php echo $data['phone'] ?: __('Not provided', 'simple-job-board'); ?></p>
            
            <h3><?php _e('Cover Letter:', 'simple-job-board'); ?></h3>
            <p><?php echo nl2br($data['message']); ?></p>
            
            <?php if (!empty($data['resume_path'])) : ?>
            <p>
                <strong><?php _e('Resume:', 'simple-job-board'); ?></strong> 
                <a href="<?php echo esc_url(wp_get_attachment_url($data['resume_path'])); ?>">
                    <?php _e('Download Resume', 'simple-job-board'); ?>
                </a>
            </p>
            <?php endif; ?>
        </div>
        
        <div class="footer">
            <p><?php _e('This email was sent from your website.', 'simple-job-board'); ?></p>
        </div>
    </div>
</body>
</html>