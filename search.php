<?php
/**
 * @package       RSFiles!
 * @copyright (C) 2010-2014 www.rsjoomla.com
 * @license       GPL, http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access'); ?>

<div class="rsfiles-layout">
    <div class="navbar navbar-info">
        <div class="navbar-inner rsf_navbar">
            <a class="btn btn-navbar" id="rsf_navbar_btn" data-toggle="collapse"
               data-target=".rsf_navbar .nav-collapse"><i class="fa fa-arrow-down"></i></a>
            <div class="nav-collapse collapse">
                <div class="nav pull-left">
                    <ul class="nav rsf_navbar_ul">
                        <li>
                            <a class="btn <?php echo rsfilesHelper::tooltipClass(); ?>"
                               title="<?php echo rsfilesHelper::tooltipText(JText::_('COM_RSFILES_NAVBAR_HOME')); ?>"
                               href="<?php echo JRoute::_('index.php?option=com_rsfiles' . ($this->briefcase ? '&layout=briefcase' : '') . $this->itemid); ?>">
                                <span class="fa fa-<?php echo $this->briefcase ? 'briefcase' : 'home'; ?>"></span>
                            </a>
                        </li>
                        <li>
                            <a class="btn <?php echo rsfilesHelper::tooltipClass(); ?>"
                               title="<?php echo rsfilesHelper::tooltipText(JText::_('COM_RSFILES_NAVBAR_BACK')); ?>"
                               href="javascript:history.go(-1);">
                                <span class="fa fa-arrow-left"></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="alert" id="rsf_alert" style="display:none;">
        <button type="button" class="close" onclick="document.getElementById('rsf_alert').style.display = 'none';">
            &times;
        </button>
        <span id="rsf_message"></span>
    </div>

    <form action="<?php echo JRoute::_('index.php?option=com_rsfiles&layout=search' . ($this->briefcase ? '&from=briefcase' : '') . $this->itemid, false); ?>"
          method="post" name="adminForm" id="adminForm" class="form-horizontal">

        <div class="well well-small">
            <div class="control-group">
                <div class="control-label">
                    <label for="filter_search"><?php echo JText::_('COM_RSFILES_SEARCH_KEYWORD'); ?></label>
                </div>
                <div class="controls">
                    <input type="text" id="filter_search" name="filter_search" class="input-xlarge" size="30"
                           value="<?php echo $this->escape($this->filter); ?>"/>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label><?php echo JText::_('COM_RSFILES_SEARCH_ORDERING'); ?></label>
                </div>
                <div class="controls">
                    <select name="rsfl_ordering" id="rsfl_ordering">
						<?php echo JHtml::_('select.options', rsfilesHelper::getOrdering(), 'value', 'text', $this->order); ?>
                    </select>

                    <select name="rsfl_ordering_direction" id="rsfl_ordering_direction">
						<?php echo JHtml::_('select.options', rsfilesHelper::getOrderingDirection(), 'value', 'text', $this->order_dir); ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label>&nbsp;</label>
                </div>
                <div class="controls">
                    <button type="submit" class="btn btn-primary"><?php echo JText::_('COM_RSFILES_SEARCH'); ?></button>
                </div>
            </div>
        </div>

		<?php echo JHTML::_('form.token'); ?>
        <input type="hidden" name="task" value=""/>
    </form>

	<?php if (!empty($this->filter)) { ?>
        <table class="rsf_files table table-striped">
            <thead>
            <tr>
                <th width="30%"><?php echo JText::_('COM_RSFILES_FILE_NAME'); ?></th>
				<?php if ($this->config->list_show_date) { ?>
                    <th width="10%"><?php echo JText::_('COM_RSFILES_FILE_DATE'); ?></th><?php } ?>
                <th width="10%">&nbsp;</th>
            </tr>
            </thead>
            <tbody>
			<?php if (!empty($this->items)) { ?>
				<?php foreach ($this->items as $i => $item) { ?>
					<?php $fullpath = $this->dld_fld . $this->ds . urldecode($item->fullpath); ?>
					<?php $path = str_replace($this->config->download_folder . $this->ds, '', $fullpath); ?>
					<?php $canDownload = rsfilesHelper::permissions('CanDownload', $path); ?>
					<?php if (!empty($item->DownloadLimit) && $item->Downloads >= $item->DownloadLimit) $canDownload = false; ?>

                    <tr class="row<?php echo $i % 2; ?>">
                        <td class="rsfiles-download-info">
							<?php $thumbnail = rsfilesHelper::thumbnail($item); ?>
							<?php if ($item->type != 'folder') { ?>
							<?php $download = rsfilesHelper::downloadlink($item, $item->fullpath); ?>
							<?php if ($canDownload && $this->config->direct_download) { ?>
							<?php if ($download->ismodal) { ?>
                            <a class="rsfiles-file <?php echo $thumbnail->class; ?>" href="javascript:void(0)"
                               onclick="rsfiles_show_modal('<?php echo $download->dlink; ?>', '<?php echo JText::_('COM_RSFILES_DOWNLOAD'); ?>', 600)"
                               title="<?php echo $thumbnail->image; ?>">
								<?php } else { ?>
                                <a class="rsfiles-file <?php echo $thumbnail->class; ?>"
                                   href="<?php echo $download->dlink; ?>" title="<?php echo $thumbnail->image; ?>">
									<?php } ?>
									<?php } else { ?>
                                    <a href="<?php echo JRoute::_('index.php?option=com_rsfiles&layout=download' . ($this->briefcase ? '&from=briefcase' : '') . '&path=' . rsfilesHelper::encode($item->fullpath) . $this->itemid); ?>"
                                       class="rsfiles-file <?php echo $thumbnail->class; ?>"
                                       title="<?php echo $thumbnail->image; ?>">
										<?php } ?>
                                        <i class="rsfiles-file-icon <?php echo $item->icon; ?>"></i> <?php echo(!empty($item->filename) ? $item->filename : $item->name); ?>
                                    </a>

                                    <br/>
                                    <small class="muted"><?php echo JText::sprintf('COM_RSFILES_SEARCH_PATH', $item->path) ?></small>

                                    <br/>

									<?php if ($item->isnew) { ?>
                                        <span class="badge badge-info"><?php echo JText::_('COM_RSFILES_NEW'); ?></span>
									<?php } ?>

									<?php if ($item->popular) { ?>
                                        <span class="badge badge-success"><?php echo JText::_('COM_RSFILES_POPULAR'); ?></span>
									<?php } ?>

									<?php if ($this->config->list_show_version && !empty($item->fileversion)) { ?><span
                                        class="badge <?php echo rsfilesHelper::tooltipClass(); ?>"
                                        title="<?php echo rsfilesHelper::tooltipText(JText::_('COM_RSFILES_FILE_VERSION')); ?>">
                                        <i class="fa fa-code-branch"></i> <?php echo $item->fileversion; ?>
                                        </span><?php } ?>
									<?php if ($this->config->list_show_license && !empty($item->filelicense)) { ?><span
                                        class="badge <?php echo rsfilesHelper::tooltipClass(); ?> badge-license"
                                        title="<?php echo rsfilesHelper::tooltipText(JText::_('COM_RSFILES_FILE_LICENSE')); ?>">
                                        <i class="fa fa-flag"></i> <?php echo $item->filelicense; ?></span><?php } ?>
									<?php if ($this->config->list_show_size && !empty($item->size)) { ?><span
                                        class="badge <?php echo rsfilesHelper::tooltipClass(); ?>"
                                        title="<?php echo rsfilesHelper::tooltipText(JText::_('COM_RSFILES_FILE_SIZE')); ?>">
                                        <i class="fa fa-file"></i> <?php echo $item->size; ?></span><?php } ?>
									<?php if ($this->config->list_show_downloads && !empty($item->downloads)) { ?><span
                                        class="badge <?php echo rsfilesHelper::tooltipClass(); ?>"
                                        title="<?php echo rsfilesHelper::tooltipText(JText::_('COM_RSFILES_FILE_DOWNLOADS')); ?>">
                                        <i class="fa fa-download"></i> <?php echo $item->downloads; ?></span><?php } ?>

									<?php } else { ?>
                                        <a href="<?php echo JRoute::_('index.php?option=com_rsfiles' . ($this->briefcase ? '&layout=briefcase' : '') . '&folder=' . rsfilesHelper::encode($item->fullpath) . $this->itemid); ?>"
                                           class="<?php echo $thumbnail->class; ?>"
                                           title="<?php echo $thumbnail->image; ?>">
                                            <i class="rsfiles-file-icon fa fa-folder"></i> <?php echo(!empty($item->filename) ? $item->filename : $item->name); ?>
                                        </a>
                                        <br/>
                                        <small class="muted"><?php echo JText::sprintf('COM_RSFILES_SEARCH_PATH', $item->path) ?></small>
									<?php } ?>
                        </td>
						<?php if ($this->config->list_show_date) { ?>
                            <td><?php if ($item->type != 'folder') echo $item->dateadded; ?></td><?php } ?>
                        <td>
							<?php if ($item->type != 'folder') { ?>
							<?php if ($canDownload && $this->config->direct_download) { ?>
							<?php if ($download->ismodal) { ?>
                            <a class="<?php echo rsfilesHelper::tooltipClass(); ?>"
                               title="<?php echo rsfilesHelper::tooltipText(JText::_('COM_RSFILES_DOWNLOAD')); ?>"
                               href="javascript:void(0)"
                               onclick="rsfiles_show_modal('<?php echo $download->dlink; ?>', '<?php echo JText::_('COM_RSFILES_DOWNLOAD'); ?>', 600);">
								<?php } else { ?>
                                <a class="<?php echo rsfilesHelper::tooltipClass(); ?>"
                                   title="<?php echo rsfilesHelper::tooltipText(JText::_('COM_RSFILES_DOWNLOAD')); ?>"
                                   href="<?php echo $download->dlink; ?>">
									<?php } ?>
									<?php } else { ?>
                                    <a href="<?php echo JRoute::_('index.php?option=com_rsfiles&layout=download' . ($this->briefcase ? '&from=briefcase' : '') . '&path=' . rsfilesHelper::encode($item->fullpath) . $this->itemid); ?>"
                                       class="<?php echo rsfilesHelper::tooltipClass(); ?>"
                                       title="<?php echo rsfilesHelper::tooltipText(JText::_('COM_RSFILES_DOWNLOAD')); ?>">
										<?php } ?>
                                        <i class="fa fa-download fa-fw"></i>
                                    </a>

									<?php if ($this->config->show_details) { ?>
                                        <a href="<?php echo JRoute::_('index.php?option=com_rsfiles&layout=details' . ($this->briefcase ? '&from=briefcase' : '') . '&path=' . rsfilesHelper::encode($item->fullpath) . $this->itemid); ?>"
                                           class="<?php echo rsfilesHelper::tooltipClass(); ?>"
                                           title="<?php echo rsfilesHelper::tooltipText(JText::_('COM_RSFILES_DETAILS')); ?>">
                                            <i class="fa fa-list fa-fw"></i>
                                        </a>
									<?php } ?>

									<?php if ($canDownload) { ?>
										<?php $properties = rsfilesHelper::previewProperties($item->id, $item->fullpath); ?>
										<?php $extension = $properties['extension']; ?>
										<?php $size = $properties['size']; ?>

										<?php if (in_array($extension, rsfilesHelper::previewExtensions()) && $item->show_preview) { ?>
                                            <a href="javascript:void(0)"
                                               onclick="rsfiles_show_modal('<?php echo JRoute::_('index.php?option=com_rsfiles&layout=preview' . ($this->briefcase ? '&from=briefcase' : '') . '&tmpl=component&path=' . rsfilesHelper::encode($item->fullpath) . $this->itemid); ?>', '<?php echo JText::_('COM_RSFILES_PREVIEW'); ?>', <?php echo $size['height']; ?>, '<?php echo $properties['handler']; ?>');"
                                               class="<?php echo rsfilesHelper::tooltipClass(); ?>"
                                               title="<?php echo rsfilesHelper::tooltipText(JText::_('COM_RSFILES_PREVIEW')); ?>">
                                                <i class="fa fa-search fa-fw"></i>
                                            </a>
										<?php } ?>
									<?php } ?>

									<?php if ($canDownload && $this->config->show_bookmark && !$item->FileType) { ?>
                                        <a href="javascript:void(0);"
                                           class="<?php echo rsfilesHelper::tooltipClass(); ?>"
                                           title="<?php echo rsfilesHelper::isBookmarked($item->fullpath) ? JText::_('COM_RSFILES_NAVBAR_FILE_IS_BOOKMARKED') : JText::_('COM_RSFILES_NAVBAR_BOOKMARK_FILE'); ?>"
                                           onclick="rsf_bookmark('<?php echo JURI::root(); ?>','<?php echo $this->escape(addslashes(urldecode($item->fullpath))); ?>','<?php echo $this->briefcase ? 1 : 0; ?>','<?php echo $this->app->input->getInt('Itemid', 0); ?>', this)">
                                            <i class="<?php echo rsfilesHelper::isBookmarked($item->fullpath) ? 'fa' : 'far'; ?> fa-bookmark fa-fw"></i>
                                        </a>
									<?php } ?>

									<?php } ?>
                        </td>
                    </tr>
				<?php } ?>
			<?php } else { ?>
                <tr>
                    <td colspan="3"><?php echo JText::_('COM_RSFILES_NO_FILES'); ?></td>
                </tr>
			<?php } ?>
            </tbody>
        </table>
	<?php } ?>
</div>

<?php if ($this->config->modal == 1) echo JHtml::_('bootstrap.renderModal', 'rsfRsfilesModal', array('title' => '', 'bodyHeight' => 70)); ?>
