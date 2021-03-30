<?php
/**
* @package RSFiles!
* @copyright (C) 2010-2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined( '_JEXEC' ) or die( 'Restricted access' );
JText::script('COM_RSFILES_PROCESS_START');
JText::script('COM_RSFILES_START_UPLOAD'); ?>

<script type="text/javascript">
var upload_files = false;
jQuery(document).ready(function(){
	jQuery('#com-rsfiles-upload-field').on('change', function(e) { upload_files = true; });

	jQuery('#com-rsfiles-upload-files').on('click', function () {
		if (upload_files === false) {
			jQuery('#com-rsfiles-upload-results li').remove();
			jQuery('#com-rsfiles-upload-results').prepend(jQuery('<li>', {'class': 'rs_error' }).html('<span class="fa fa-times com-rsfiles-close-message"></span> <?php echo JText::_('COM_RSFILES_NO_FILES_SELECTED',true);?>'));
			jQuery('.com-rsfiles-close-message').on('click',function() {
				jQuery(this).parent('li').hide('fast');
			});

			return false;
		}
	});

	jQuery('.rsfiles-upload-box').on('dragover dragenter', function() {
		jQuery(this).addClass('rsfiles-upload-dragover');
	});

	jQuery('.rsfiles-upload-box').on('dragleave dragend drop', function() {
		jQuery(this).removeClass('rsfiles-upload-dragover');
	});
});
</script>

<div class="rsfiles-layout">
	<div class="navbar navbar-info">
		<div class="navbar-inner rsf_navbar">
			<a class="btn btn-navbar" id="rsf_navbar_btn" data-toggle="collapse" data-target=".rsf_navbar .nav-collapse"><i class="fa fa-arrow-down"></i></a>
			<a class="brand visible-tablet visible-phone" href="javascript:void(0)"><?php echo JText::_('COM_RSFILES_NAVBAR'); ?></a>
			<div class="nav-collapse collapse">
				<div class="nav pull-left">
					<ul class="nav rsf_navbar_ul">
						<li>
							<a class="btn <?php echo rsfilesHelper::tooltipClass(); ?>" title="<?php echo rsfilesHelper::tooltipText(JText::_('COM_RSFILES_NAVBAR_HOME')); ?>" href="<?php echo JRoute::_('index.php?option=com_rsfiles'.$this->itemid); ?>">
								<span class="fa fa-home"></span>
							</a>
						</li>
						<li>
							<a class="btn <?php echo rsfilesHelper::tooltipClass(); ?>" title="<?php echo rsfilesHelper::tooltipText(JText::_('COM_RSFILES_NAVBAR_BACK')); ?>" href="<?php echo JRoute::_('index.php?option=com_rsfiles'.($this->briefcase ? '&layout=briefcase' : '').($this->folder ? '&folder='.rsfilesHelper::encode($this->folder) : '').$this->itemid); ?>">
								<span class="fa fa-arrow-left"></span>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<form action="<?php echo JRoute::_('index.php?option=com_rsfiles'); ?>" method="post" name="adminForm" id="rsfl_upload_form" enctype="multipart/form-data">

		<fieldset>
			<legend><?php echo JText::_('COM_RSFILES_UPLOAD_INTERNAL_FILES'); ?></legend>
			<div class="well">
				<div class="rsfiles-upload-box">
					<div class="rsfiles-upload-box-top">
						<div id="com-rsfiles-progress-info" style="display: none;"></div>
						<div class="clearfix"></div>
						<div id="com-rsfiles-progress" class="com-rsfiles-progress" style="display:none;"><div style="width: 1%;" id="com-rsfiles-bar" class="com-rsfiles-bar">0%</div></div>
					</div>

					<label for="com-rsfiles-upload-field" class="rsfiles-upload-box-label">
						<span class="fa fa-upload fa-4x rsfiles-upload-image"></span>
						<span id="com-rsfiles-add-files">
							<span id="com-rsfiles-simple-add-files" style="display:none;"><?php echo JText::_('COM_RSFILES_ADD_FILES'); ?></span>
							<span id="com-rsfiles-drag-add-files" style="display:none;"><?php echo JText::_('COM_RSFILES_ADD_DRAG_FILES'); ?></span>
						</span>
						<span id="com-rsfiles-no-files" style="display: none;"><?php echo JText::_('COM_RSFILES_READY_FOR_UPLOAD', true);?></span>
					</label>
					<input id="com-rsfiles-upload-field" name="file" type="file" class="input-large" size="20" <?php if (!$this->single) { ?>multiple<?php } ?> />

					<div class="rsfiles-upload-box-bottom">
						<label for="overwrite" class="btn" id="rsf_overwrite">
							<input type="checkbox" name="overwrite" id="overwrite" value="1" /> <span class="rsf_inline"><?php echo JText::_('COM_RSFILES_OVERWRITE'); ?></span>
						</label>

						<button class="btn btn-primary" type="button" id="com-rsfiles-upload-files">
							<span class="fa fa-upload"></span>
							<span id="com-rsfiles-upload-text"><?php echo JText::_('COM_RSFILES_START_UPLOAD'); ?></span>
						</button>

						<button class="btn btn-danger" type="reset" id="com-rsfiles-cancel-upload">
							<span class="fa fa-times"></span>
							<span><?php echo JText::_('COM_RSFILES_CANCEL'); ?></span>
						</button>
					</div>
				</div>

				<ul id="com-rsfiles-upload-results"></ul>
			</div>
		</fieldset>

		<?php echo JHTML::_( 'form.token' ); ?>
		<input type="hidden" name="task" value="rsfiles.upload" />
		<input type="hidden" name="folder" value="<?php echo $this->folder; ?>" />
		<input type="hidden" name="from" value="<?php echo $this->app->input->getString('from'); ?>" />
		<input type="hidden" id="chunk" name="chunk" value="<?php echo rsfilesHelper::getChunkSize(); ?>" />
		<?php if ($this->briefcase) { ?>
		<input type="hidden" id="number" name="number" value="<?php echo $this->curentfilesno; ?>" />
		<input type="hidden" id="quota" name="quota" value="<?php echo ($this->currentquota * 1048576); ?>" />
		<?php } ?>
		<span id="siteroot" style="display:none;"><?php echo JURI::root(); ?></span>
		<span id="itemid" style="display:none;"><?php echo $this->app->input->getInt('Itemid'); ?></span>
		<span id="singleupload" style="display:none;"><?php echo $this->escape($this->singleupload); ?></span>
	</form>

	<?php if (!$this->briefcase) { ?>
	<fieldset>
		<legend><?php echo JText::_('COM_RSFILES_UPLOAD_EXTERNAL_FILES'); ?></legend>
		<div class="alert" id="externalfiles" style="display: none;"></div>
		<div class="well" id="rsf_external">
			<div class="control-group">
				<div class="controls">
					<div class="input-append">
						<input type="text" name="external[]" value="" class="input-xxlarge rsf_reset_margin" />
						<a href="javascript:void(0);" onclick="rsf_add_external();" class="btn btn-info">
							<i class="fa fa-plus"></i>
						</a>
					</div>
				</div>
			</div>
			<span id="external"></span>
			<div class="control-group">
				<div class="controls" style="margin: 10px auto; max-width: 300px;">
					<button type="button" class="btn btn-block btn-primary" onclick="rsf_upload_external();">
						<?php echo JHtml::image('com_rsfiles/loading.gif', '', array('id' => 'rsf_loading', 'style' => 'display: none;'), true); ?>
						<?php echo JText::_('COM_RSFILES_UPLOAD_EXTERNAL'); ?>
					</button>
				</div>
			</div>
		</div>
	</fieldset>
	<?php } ?>
</div>
