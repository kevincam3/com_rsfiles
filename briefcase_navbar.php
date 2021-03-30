<?php
/**
 * @package       RSFiles!
 * @copyright (C) 2010-2014 www.rsjoomla.com
 * @license       GPL, http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access'); ?>

<div class="navbar navbar-info">
    <div class="navbar-inner rsf_navbar">
        <a class="btn btn-navbar" id="rsf_navbar_btn" data-toggle="collapse" data-target=".rsf_navbar .nav-collapse"><i
                    class="fa fa-arrow-down"></i></a>
        <div class="nav-collapse collapse">
            <div class="nav pull-left">
                <ul class="nav rsf_navbar_ul">
                    <li>
                        <a class="btn <?php echo rsfilesHelper::tooltipClass(); ?>"
                           title="<?php echo rsfilesHelper::tooltipText(JText::_('COM_RSFILES_NAVBAR_BRIEFCASE')); ?>"
                           href="<?php echo JRoute::_('index.php?option=com_rsfiles&layout=briefcase' . $this->itemid); ?>">
                            <span class="fa fa-briefcase"></span>
                        </a>
                    </li>

					<?php if ($this->config->show_search) { ?>
                        <li>
                            <a class="btn <?php echo rsfilesHelper::tooltipClass(); ?>"
                               title="<?php echo rsfilesHelper::tooltipText(JText::_('COM_RSFILES_NAVBAR_SEARCH')); ?>"
                               href="<?php echo JRoute::_('index.php?option=com_rsfiles&layout=search&from=briefcase' . $this->itemid); ?>">
                                <span class="fa fa-search"></span>
                            </a>
                        </li>
					<?php } ?>

					<?php if ($this->config->show_bookmark) { ?>
                        <li>
                            <a class="btn <?php echo rsfilesHelper::tooltipClass(); ?>"
                               title="<?php echo rsfilesHelper::tooltipText(JText::_('COM_RSFILES_NAVBAR_BOOKMARK')); ?>"
                               href="<?php echo JRoute::_('index.php?option=com_rsfiles&layout=bookmarks' . $this->itemid); ?>">
                                <span class="fa fa-bookmark"></span>
                            </a>
                        </li>
					<?php } ?>

					<?php $current = $this->briefcase_root . ($this->currentfolder ? $this->ds . $this->currentfolder : ''); ?>
					<?php if ((($this->upload && $this->curentfilesno < $this->maxfilesno && $this->currentquota < $this->maxfilessize) || $this->maintenance)) { ?>

						<?php if ($this->maintenance && $this->config->briefcase_folder == $current)
						{
							$new_folder = JRoute::_('index.php?option=com_rsfiles&view=users&tmpl=component' . $this->itemid);
							$height     = 400;
						}
						else
						{
							$new_folder = JRoute::_('index.php?option=com_rsfiles&layout=create&from=briefcase&tmpl=component' . ($this->currentfolder ? '&folder=' . rsfilesHelper::encode($this->currentfolder) : '') . $this->itemid);
							$height     = 200;
						} ?>

                        <li>
                            <a class="btn <?php echo rsfilesHelper::tooltipClass(); ?>"
                               title="<?php echo rsfilesHelper::tooltipText(JText::_('COM_RSFILES_NAVBAR_NEW_FOLDER')); ?>"
                               href="javascript:void(0)"
                               onclick="rsfiles_show_modal('<?php echo $new_folder; ?>', '&nbsp;', <?php echo $height; ?>)">
                                <span class="fa fa-folder-open"></span>
                            </a>
                        </li>

						<?php if ($this->config->briefcase_folder != $current) { ?>
							<?php if ($this->config->enable_upload == 1 && ($this->upload || $this->maintenance)) { ?>
								<?php if ($this->maxfilesno > $this->curentfilesno) { ?>
									<?php if ($this->maxfilessize > $this->currentquota) { ?>
                                        <li>
                                            <a class="btn <?php echo rsfilesHelper::tooltipClass(); ?>"
                                               title="<?php echo rsfilesHelper::tooltipText(JText::_('COM_RSFILES_NAVBAR_UPLOAD')); ?>"
                                               href="<?php echo JRoute::_('index.php?option=com_rsfiles&layout=upload&from=briefcase' . ($this->currentfolder ? '&folder=' . rsfilesHelper::encode($this->currentfolder) : '') . $this->itemid); ?>">
                                                <span class="fa fa-upload"></span>
                                            </a>
                                        </li>
									<?php }
								}
							}
						}
					} ?>

					<?php if ((!$this->maintenance && $this->delete && !empty($this->folder)) || ($this->maintenance && $this->current != $this->config->briefcase_folder)) { ?>
                        <li>
                            <a class="btn <?php echo rsfilesHelper::tooltipClass(); ?>"
                               title="<?php echo rsfilesHelper::tooltipText(JText::_('COM_RSFILES_NAVBAR_DELETE')); ?>"
                               href="<?php echo JRoute::_('index.php?option=com_rsfiles&task=rsfiles.delete&from=briefcase' . ($this->currentfolder ? '&folder=' . rsfilesHelper::encode($this->currentfolder) : '') . $this->itemid); ?>"
                               onclick="if (!confirm('<?php echo JText::_('COM_RSFILES_DELETE_MESSAGE', true); ?>')) return false;">
                                <span class="fa fa-trash"></span>
                            </a>
                        </li>
					<?php } ?>

					<?php if (!empty($this->folder)) { ?>
                        <li>
                            <a class="btn <?php echo rsfilesHelper::tooltipClass(); ?>"
                               title="<?php echo rsfilesHelper::tooltipText(JText::_('COM_RSFILES_NAVBAR_UP')); ?>"
                               href="<?php echo JRoute::_('index.php?option=com_rsfiles&layout=briefcase' . $this->previous . $this->itemid); ?>">
                                <span class="fa fa-arrow-up"></span>
                            </a>
                        </li>
					<?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="alert" id="rsf_alert" style="display:none;">
    <button type="button" class="close" onclick="document.getElementById('rsf_alert').style.display = 'none';">&times;
    </button>
    <span id="rsf_message"></span>
</div>

<?php if ($this->config->file_path == 1) { ?>
    <ul class="breadcrumb">
		<?php if (empty($this->navigation)) { ?>
            <li class="active"><?php echo JText::_('COM_RSFILES_HOME'); ?></li>
		<?php } else { ?>
            <li>
                <a href="<?php echo JRoute::_('index.php?option=com_rsfiles&layout=briefcase' . $this->itemid); ?>"><?php echo JText::_('COM_RSFILES_HOME'); ?></a>
            </li>
			<?php end($this->navigation); ?>
			<?php $last_item_key = key($this->navigation); ?>
			<?php foreach ($this->navigation as $key => $element) { ?>
				<?php if ($key != $last_item_key) { ?>
                    <li>
                        <span class="divider">/</span>
                        <a href="<?php echo JRoute::_('index.php?option=com_rsfiles&layout=briefcase&folder=' . rsfilesHelper::encode($element->fullpath) . $this->itemid); ?>"><?php echo $element->name; ?></a>
                    </li>
				<?php } else { ?>
                    <li class="active">
                        <span class="divider">/</span>
						<?php echo $element->name; ?>
                    </li>
				<?php } ?>
			<?php } ?>
		<?php } ?>
    </ul>
<?php } ?>
