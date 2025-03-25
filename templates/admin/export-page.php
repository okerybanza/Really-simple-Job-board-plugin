<div class="wrap">
    <h1><?php _e('Export Applications', 'simple-job-board'); ?></h1>
    
    <form method="post" action="">
        <?php wp_nonce_field('sjb_export'); ?>
        
        <div class="card">
            <h2><?php _e('Export Options', 'simple-job-board'); ?></h2>
            <p><?php _e('Click the button below to export all job applications as a CSV file.', 'simple-job-board'); ?></p>
            
            <p>
                <label>
                    <input type="checkbox" name="export_only_new" value="1">
                    <?php _e('Only export applications not previously exported', 'simple-job-board'); ?>
                </label>
            </p>
            
            <p class="submit">
                <button type="submit" name="sjb_export_applications" class="button button-primary">
                    <?php _e('Export to CSV', 'simple-job-board'); ?>
                </button>
            </p>
        </div>
    </form>
</div>