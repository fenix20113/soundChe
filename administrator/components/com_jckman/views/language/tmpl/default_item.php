<tr class="<?php echo "row".$this->item->index % 2; ?>" <?php echo $this->item->style; ?>>
	<td><?php echo $this->pagination->getRowOffset( $this->item->index ); ?></td>
	<td><input type="checkbox" id="cb<?php echo $this->item->index;?>" name="eid[<?php echo $this->item->id; ?>]" value="<?php echo $this->item->client_id; ?>" onclick="isChecked(this.checked);" <?php echo $this->item->cbd; ?> /></td>
	<td><span class="bold"><?php echo $this->item->name; ?></span></td>
	<td align="center"><?php echo $this->item->tag; ?></td>
	<td align="center"><?php echo $this->item->plugin; ?></td>
	<td align="center"><?php echo @$this->item->version != '' ? $this->item->version : '&nbsp;'; ?></td>
	<td align="center"><?php echo @$this->item->creationDate != '' ? $this->item->creationDate : '&nbsp;'; ?></td>
	<td align="center">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_JCK_UNINSTALLER_AUTHOR_INFORMATION' );?>::<?php echo $this->item->author_info; ?>">
			<?php echo @$this->item->author != '' ? $this->item->author : '&nbsp;'; ?>
		</span>
	</td>
</tr>
