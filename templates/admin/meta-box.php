<div class="sjb-meta-fields">
    <p>
        <label for="sjb_location"><?php _e('Location', 'simple-job-board'); ?></label>
        <input type="text" id="sjb_location" name="sjb_location" value="<?php echo esc_attr($meta['location']); ?>" class="widefat">
    </p>
    
    <p>
        <label for="sjb_type"><?php _e('Job Type', 'simple-job-board'); ?></label>
        <select id="sjb_type" name="sjb_type" class="widefat">
            <option value="full-time" <?php selected($meta['type'], 'full-time'); ?>><?php _e('Full-time', 'simple-job-board'); ?></option>
            <option value="part-time" <?php selected($meta['type'], 'part-time'); ?>><?php _e('Part-time', 'simple-job-board'); ?></option>
            <option value="contract" <?php selected($meta['type'], 'contract'); ?>><?php _e('Contract', 'simple-job-board'); ?></option>
            <option value="remote" <?php selected($meta['type'], 'remote'); ?>><?php _e('Remote', 'simple-job-board'); ?></option>
        </select>
    </p>
    
    <p>
        <label for="sjb_salary"><?php _e('Salary', 'simple-job-board'); ?></label>
        <input type="text" id="sjb_salary" name="sjb_salary" value="<?php echo esc_attr($meta['salary']); ?>" class="widefat">
    </p>
    
    <p>
        <label for="sjb_closing_date"><?php _e('Closing Date', 'simple-job-board'); ?></label>
        <input type="date" id="sjb_closing_date" name="sjb_closing_date" value="<?php echo esc_attr($meta['closing_date']); ?>" class="widefat">
    </p>
    
    <p>
        <label for="sjb_application_email"><?php _e('Application Email', 'simple-job-board'); ?></label>
        <input type="email" id="sjb_application_email" name="sjb_application_email" value="<?php echo esc_attr($meta['application_email']); ?>" class="widefat">
    </p>
</div>