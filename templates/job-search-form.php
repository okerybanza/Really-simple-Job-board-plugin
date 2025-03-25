<form method="get" action="<?php echo get_post_type_archive_link('sjb_job'); ?>" class="sjb-search-form">
    <div class="sjb-search-fields">
        <input type="text" name="location" placeholder="<?php _e('Location', 'simple-job-board'); ?>" 
               value="<?php echo esc_attr($_GET['location'] ?? ''); ?>">
        
        <select name="job_type">
            <option value=""><?php _e('All Types', 'simple-job-board'); ?></option>
            <option value="full-time" <?php selected($_GET['job_type'] ?? '', 'full-time'); ?>>
                <?php _e('Full-time', 'simple-job-board'); ?>
            </option>
            <option value="part-time" <?php selected($_GET['job_type'] ?? '', 'part-time'); ?>>
                <?php _e('Part-time', 'simple-job-board'); ?>
            </option>
        </select>
        
        <?php
        $categories = get_terms(['taxonomy' => 'sjb_category', 'hide_empty' => false]);
        if ($categories) :
        ?>
        <select name="category">
            <option value=""><?php _e('All Categories', 'simple-job-board'); ?></option>
            <?php foreach ($categories as $cat) : ?>
            <option value="<?php echo esc_attr($cat->slug); ?>" 
                <?php selected($_GET['category'] ?? '', $cat->slug); ?>>
                <?php echo esc_html($cat->name); ?>
            </option>
            <?php endforeach; ?>
        </select>
        <?php endif; ?>
        
        <button type="submit"><?php _e('Search Jobs', 'simple-job-board'); ?></button>
    </div>
</form>