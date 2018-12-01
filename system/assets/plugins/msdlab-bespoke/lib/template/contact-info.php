<?php global $wpalchemy_media_access; ?>
<ul class="" id="gform_fields_4">
	<?php $mb->the_field('_team_last_name'); ?>
    <li class="gfield"
        id="field_team_last_name"><label for="<?php $mb->the_name(); ?>" class="gfield_label">Last Name (for alphabetizing)
    </label>
    <div class="ginput_container">
            <input type="text" tabindex="24" class="medium" value="<?php $mb->the_value(); ?>"
                id="<?php $mb->the_name(); ?>" name="<?php $mb->the_name(); ?>">
        </div>
    </li>
    <?php $mb->the_field('_team_user_id'); ?>
    <li class="gfield"
        id="field_team_user_id"><label for="<?php $mb->the_name(); ?>" class="gfield_label">Blog User (for blog posts)
    </label>
    <div class="ginput_container">
            <?php $blogusers = get_users(); ?>
            <select id="<?php $mb->the_name(); ?>" name="<?php $mb->the_name(); ?>">
                <option value=0>No Insights</option>
                <?php foreach($blogusers AS $bu){ ?>
                    <option value="<?php print $bu->ID; ?>"<?php $mb->the_select_state($bu->ID); ?>><?php print $bu->display_name; ?></option>
                <?php } ?>
            </select>
        </div>
    </li>
    <?php $mb->the_field('_team_title'); ?>
    <li class="gfield"
        id="field_team_title"><label for="<?php $mb->the_name(); ?>" class="gfield_label">Title
    </label>
    <div class="ginput_container">
            <input type="text" tabindex="28" class="medium" value="<?php $mb->the_value(); ?>"
                id="<?php $mb->the_name(); ?>" name="<?php $mb->the_name(); ?>">
        </div>
    </li>    
    <?php $mb->the_field('_team_position'); ?>
    <li class="gfield"
        id="field_team_position"><label for="<?php $mb->the_name(); ?>" class="gfield_label">Leadership?
    </label>
    <div class="ginput_container">
            <input type="checkbox" tabindex="28" class="medium" value="true"
                id="<?php $mb->the_name(); ?>" name="<?php $mb->the_name(); ?>"<?php $mb->the_checkbox_state('true'); ?>/>
        </div>
    </li>
    <?php $mb->the_field('_team_phone'); ?>
    <li class="gfield"
        id="field_team_phone"><label for="<?php $mb->the_name(); ?>" class="gfield_label">Phone
    </label>
    <div class="ginput_container">
            <input type="text" tabindex="28" class="medium" value="<?php $mb->the_value(); ?>"
                id="<?php $mb->the_name(); ?>" name="<?php $mb->the_name(); ?>">
        </div>
    </li>
	<?php $mb->the_field('_team_mobile'); ?>
	<li class="gfield"
		id="field_team_mobile"><label for="<?php $mb->the_name(); ?>" class="gfield_label">Mobile
	</label>
	<div class="ginput_container">
			<input type="text" tabindex="29" class="medium" value="<?php $mb->the_value(); ?>"
				id="<?php $mb->the_name(); ?>" name="<?php $mb->the_name(); ?>">
		</div>
	</li>
	<?php $mb->the_field('_team_fax'); ?>
	<li class="gfield" id="field_team_fax"><label for="<?php $mb->the_name(); ?>"
		class="gfield_label">Fax</label>
	<div class="ginput_container">
			<input type="text" tabindex="30" class="medium" value="<?php $mb->the_value(); ?>"
				id="<?php $mb->the_name(); ?>" name="<?php $mb->the_name(); ?>">
		</div>
	</li>
	
    <?php $mb->the_field('_team_email'); ?>
    <li class="gfield" id="field_team_email"><label
        for="<?php $mb->the_name(); ?>" class="gfield_label">Email</label>
    <div class="ginput_container">
            <input type="text" tabindex="34" class="medium" value="<?php $mb->the_value(); ?>"
                id="<?php $mb->the_name(); ?>" name="<?php $mb->the_name(); ?>">
        </div>
    </li>
    <?php $mb->the_field('_team_twitter'); ?>
    <li class="gfield" id="field_team_twitter"><label for="<?php $mb->the_name(); ?>"
        class="gfield_label">Twitter Handle</label>
    <div class="ginput_container">
            <input type="text" tabindex="32" class="medium" value="<?php $mb->the_value(); ?>"
                id="<?php $mb->the_name(); ?>" name="<?php $mb->the_name(); ?>" placeholder="@">
        </div>
    </li>
    
	<?php $mb->the_field('_team_linked_in'); ?>
	<li class="gfield" id="field_team_linked_in"><label for="<?php $mb->the_name(); ?>"
		class="gfield_label">Linked In URL</label>
	<div class="ginput_container">
			<input type="text" tabindex="32" class="medium" value="<?php $mb->the_value(); ?>"
				id="<?php $mb->the_name(); ?>" name="<?php $mb->the_name(); ?>" placeholder="http://">
		</div>
	</li>

	<?php $mb->the_field('_team_quote'); ?>
    <li class="gfield" id="field_team_quote"><label for="<?php $mb->the_name(); ?>"
        class="gfield_label">Personal Quote</label>
    <div class="ginput_container">
        <textarea class="medium" id="<?php $mb->the_name(); ?>" name="<?php $mb->the_name(); ?>"><?php $mb->the_value(); ?></textarea>
        </div>
    </li>
</ul>