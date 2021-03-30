<?php
/**
 * @package       RSFiles!
 * @copyright (C) 2010-2014 www.rsjoomla.com
 * @license       GPL, http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator');
JHtml::_('formbehavior.chosen', '#jform_tags');

$extension = strtolower(rsfilesHelper::getExt($this->item->FilePath));
$icon      = 'fa fa-file';
$ext       = $this->item->icon ? $this->item->icon : $extension;

if (in_array($ext, rsfilesHelper::fileExtensions()))
{
	$icon = 'flaticon-' . $ext . '-file';
}
?>

<script type="text/javascript">
    Joomla.submitbutton = function (task) {
        if (task == 'rsfiles.cancel' || document.formvalidator.isValid(document.getElementById('adminForm'))) {
			<?php echo $this->form->getField('FileDescription')->save(); ?>
            Joomla.submitform(task);
        }
    }
</script>

<div class="rsfiles-layout">
    <div class="navbar navbar-info">
        <div class="navbar-inner rsf_navbar">
            <a class="btn btn-navbar" id="rsf_navbar_btn" data-toggle="collapse"
               data-target=".rsf_navbar .nav-collapse"><i class="fa fa-arrow-down"></i></a>
            <div class="nav-collapse collapse">
                <div class="nav pull-left">
                    <ul class="nav rsf_navbar_ul">
						<?php if (!$this->briefcase) { ?>
                            <li>
                                <a class="btn <?php echo rsfilesHelper::tooltipClass(); ?>"
                                   title="<?php echo rsfilesHelper::tooltipText(JText::_('COM_RSFILES_NAVBAR_HOME')); ?>"
                                   href="<?php echo JRoute::_('index.php?option=com_rsfiles' . $this->itemid); ?>">
                                    <span class="fa fa-home"></span>
                                </a>
                            </li>
						<?php } else { ?>
                            <li>
                                <a class="btn <?php echo rsfilesHelper::tooltipClass(); ?>"
                                   title="<?php echo rsfilesHelper::tooltipText(JText::_('COM_RSFILES_NAVBAR_BRIEFCASE')); ?>"
                                   href="<?php echo JRoute::_('index.php?option=com_rsfiles&layout=briefcase' . $this->itemid); ?>">
                                    <span class="fa fa-briefcase"></span>
                                </a>
                            </li>
						<?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <form action="<?php echo JRoute::_('index.php?option=com_rsfiles' . $this->itemid, false); ?>" method="post"
          enctype="multipart/form-data" name="adminForm" id="adminForm" class="form-validate form-vertical">
        <div style="text-align: right;">
            <button type="button" class="btn btn-primary"
                    onclick="Joomla.submitbutton('rsfiles.save')"><?php echo JText::_('COM_RSFILES_SAVE'); ?></button>
            <button type="button" class="btn"
                    onclick="Joomla.submitbutton('rsfiles.cancel')"><?php echo JText::_('COM_RSFILES_CANCEL'); ?></button>
        </div>

		<?php echo JHtml::_('bootstrap.startTabSet', 'editForm', array('active' => 'details')); ?>
		<?php echo JHtml::_('bootstrap.addTab', 'editForm', 'details', JText::_('COM_RSFILES_FILE_DETAILS')); ?>

		<?php if ($this->item->FileType) { ?>
            <div class="row-fluid">
                <div class="control-group">
                    <div class="control-label">
						<?php echo $this->form->getLabel('FilePath'); ?>
                    </div>
                    <div class="controls">
						<?php echo $this->form->getInput('FilePath'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
						<?php echo $this->form->getLabel('DownloadName'); ?>
                    </div>
                    <div class="controls">
						<?php echo $this->form->getInput('DownloadName'); ?>
                    </div>
                </div>
            </div>
		<?php } ?>

        <div class="row-fluid">
            <div class="span6 rsspan6 rstolft">
                <div class="control-group">
                    <div class="control-label">
						<?php echo $this->form->getLabel('published'); ?>
                    </div>
                    <div class="controls">
                        <select name="jform[published]" id="jform_published" size="1" class="span12">
							<?php echo JHtml::_('select.options', $this->state, 'value', 'text', $this->form->getValue('published')); ?>
                        </select>
                    </div>
                </div>

                <div class="control-group">
                    <div class="control-label">
						<?php echo $this->form->getLabel('FileName'); ?>
                    </div>
                    <div class="controls">
						<?php echo $this->form->getInput('FileName'); ?>
                    </div>
                </div>

                <div class="control-group">
                    <div class="control-label">
                        <label><?php echo JText::_('COM_RSFILES_FILE_ICON'); ?></label>
                    </div>
                    <div class="controls">
                        <button type="button" onclick="jQuery('#rsfIcon').modal('show');"
                                class="btn btn-block hasTooltip"
                                title="<?php echo JText::_('COM_RSFILES_FILE_ICON'); ?>" id="file_icon"><i
                                    id="rsfiles-icon" class="<?php echo $icon; ?>"></i></button>
                    </div>
                </div>

                <div class="control-group">
                    <div class="control-label">
						<?php echo $this->form->getLabel('DateAdded'); ?>
                    </div>
                    <div class="controls">
						<?php echo $this->form->getInput('DateAdded'); ?>
                    </div>
                </div>

				<?php if (!$this->briefcase) { ?>
                    <div class="control-group">
                        <div class="control-label">
							<?php echo $this->form->getLabel('IdLicense'); ?>
                        </div>
                        <div class="controls">
							<?php echo $this->form->getInput('IdLicense'); ?>
                        </div>
                    </div>
				<?php } ?>

                <div class="control-group">
                    <div class="control-label">
                        <label for="thumb" class="<?php echo rsfilesHelper::tooltipClass(); ?>"
                               title="<?php echo rsfilesHelper::tooltipText(JText::_('COM_RSFILES_FILE_THUMB_DESC')); ?>"><?php echo JText::_('COM_RSFILES_FILE_THUMB'); ?></label>
                    </div>
                    <div class="controls">
						<?php if (empty($this->item->FileThumb) || !file_exists(JPATH_SITE . '/components/com_rsfiles/images/thumbs/files/' . $this->item->FileThumb)) { ?>
                            <input type="file" id="thumb" name="thumb" size="50"/>
						<?php } else { ?>
							<?php echo JHTML::_('image', JURI::root() . 'components/com_rsfiles/images/thumbs/files/' . $this->item->FileThumb . '?sid=' . rand(), '', 'class="rsf_thumb" style="vertical-align: middle;"'); ?>
                            <a href="javascript:void(0);" onclick="Joomla.submitbutton('rsfiles.deletethumb');">
                                <i class="fa fa-trash"></i>
                            </a>
						<?php } ?>
                    </div>
                </div>

            </div>
            <div class="span6 rsspan6 rstorgt">
				<?php if (!$this->briefcase) { ?>
                    <div class="control-group">
                        <div class="control-label">
							<?php echo $this->form->getLabel('FileStatistics'); ?>
                        </div>
                        <div class="controls">
                            <select name="jform[FileStatistics]" id="jform_FileStatistics" size="1" class="span12">
								<?php echo JHtml::_('select.options', $this->yesno, 'value', 'text', $this->form->getValue('FileStatistics')); ?>
                            </select>
                        </div>
                    </div>
				<?php } ?>
                <div class="control-group">
                    <div class="control-label">
						<?php echo $this->form->getLabel('FileVersion'); ?>
                    </div>
                    <div class="controls">
						<?php echo $this->form->getInput('FileVersion'); ?>
                    </div>
                </div>

                <div class="control-group">
                    <div class="control-label">
						<?php echo $this->form->getLabel('tags'); ?>
                    </div>
                    <div class="controls">
						<?php echo $this->form->getInput('tags'); ?>
                    </div>
                </div>

                <div class="control-group">
                    <div class="control-label">
						<?php echo $this->form->getLabel('publish_down'); ?>
                    </div>
                    <div class="controls">
						<?php echo $this->form->getInput('publish_down'); ?>
                    </div>
                </div>

				<?php if (!$this->briefcase) { ?>
                    <div class="control-group">
                        <div class="control-label">
							<?php echo $this->form->getLabel('DownloadMethod'); ?>
                        </div>
                        <div class="controls">
							<?php echo $this->form->getInput('DownloadMethod'); ?>
                        </div>
                    </div>
				<?php } ?>

                <div class="control-group">
                    <div class="control-label">
                        <label for="thumb" class="<?php echo rsfilesHelper::tooltipClass(); ?>"
                               title="<?php echo rsfilesHelper::tooltipText(JText::sprintf('COM_RSFILES_FILE_PREVIEW_DESC', rsfilesHelper::previewExtensions(true))); ?>"><?php echo JText::_('COM_RSFILES_FILE_PREVIEW'); ?></label>
                    </div>
                    <div class="controls">
						<?php if (empty($this->item->preview) || !file_exists(JPATH_SITE . '/components/com_rsfiles/images/preview/' . $this->item->preview)) { ?>
                            <input type="file" id="preview" name="preview" size="50"/> <br/>
                            <input type="checkbox" id="resize" name="resize" value="1"/>
                            <label class="checkbox inline"
                                   for="resize"><?php echo JText::_('COM_RSFILES_FILE_PREVIEW_RESIZE'); ?></label>
                            <input type="text" value="200" class="input-mini" size="5" name="resize_width"/> px
						<?php } else { ?>
							<?php $properties = rsfilesHelper::previewProperties($this->item->IdFile); ?>
                            <a href="javascript:void(0);"
                               onclick="rsfiles_show_modal('<?php echo JRoute::_('index.php?option=com_rsfiles&task=preview&tmpl=component&id=' . $this->item->IdFile, false); ?>','<?php echo JText::_('COM_RSFILES_FILE_PREVIEW'); ?>', '<?php echo $properties['size']['height']; ?>')"><?php echo JText::_('COM_RSFILES_FILE_PREVIEW'); ?></a>
                            / <a href="javascript:void(0);"
                                 onclick="Joomla.submitbutton('rsfiles.deletepreview');"><?php echo JText::_('COM_RSFILES_DELETE'); ?></a>
						<?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
		<?php echo $this->form->getInput('FileDescription'); ?>
        <div class="clearfix"></div>

		<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php echo JHtml::_('bootstrap.addTab', 'editForm', 'meta', JText::_('COM_RSFILES_FILE_METADATA')); ?>
        <div class="row-fluid">
            <div class="control-group">
                <div class="control-label">
					<?php echo $this->form->getLabel('metatitle'); ?>
                </div>
                <div class="controls">
					<?php echo $this->form->getInput('metatitle'); ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
					<?php echo $this->form->getLabel('metadescription'); ?>
                </div>
                <div class="controls">
					<?php echo $this->form->getInput('metadescription'); ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
					<?php echo $this->form->getLabel('metakeywords'); ?>
                </div>
                <div class="controls">
					<?php echo $this->form->getInput('metakeywords'); ?>
                </div>
            </div>
        </div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php echo JHtml::_('bootstrap.endTabSet'); ?>

		<?php if ($this->config->consent) { ?>
            <div class="row-fluid">
                <div class="control-group">
                    <div class="controls">
                        <label class="checkbox" for="consent">
                            <input type="checkbox" id="consent" class="required" name="consent" value="1"/>
							<?php echo JText::_('COM_RSFILES_CONSENT'); ?>
                        </label>
                    </div>
                </div>
            </div>
		<?php } ?>

		<?php echo JHTML::_('form.token'); ?>
        <input type="hidden" name="task" value=""/>
		<?php echo $this->form->getInput('IdFile'); ?>
		<?php echo $this->form->getInput('FileType'); ?>
		<?php echo $this->form->getInput('icon'); ?>
        <input type="hidden" name="from" value="<?php echo $this->app->input->getString('from'); ?>"/>
        <input type="hidden" name="return" value="<?php echo $this->return_page; ?>"/>
        <input type="hidden" name="return_from" value="<?php echo base64_encode(JURI::getInstance()); ?>"/>
    </form>
</div>

<?php if ($this->config->modal == 1) echo JHtml::_('bootstrap.renderModal', 'rsfRsfilesModal', array('title' => '', 'bodyHeight' => 70)); ?>
<?php echo JHtml::_('bootstrap.renderModal', 'rsfIcon', array('title' => JText::_('COM_RSFILES_SELECT_FILE_ICON'), 'bodyHeight' => 70, 'modalWidth' => 40), $this->loadTemplate('icon')) ?>
