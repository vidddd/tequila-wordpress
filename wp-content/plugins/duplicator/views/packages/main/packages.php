<?php
	$qryResult = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}duplicator_packages` ORDER BY id DESC", ARRAY_A);
	$qryStatus = $wpdb->get_results("SELECT status FROM `{$wpdb->prefix}duplicator_packages` WHERE status >= 100", ARRAY_A);
	$totalElements	= count($qryResult);
	$statusCount	= count($qryStatus);
	$package_debug	= DUP_Settings::Get('package_debug');
    $ajax_nonce		= wp_create_nonce('package_list');
?>

<style>
	div#dup-list-alert-nodata {padding:50px 20px;text-align:center; font-size:20px; line-height:26px}
	div.dup-notice-msg {border:1px solid silver; padding: 10px; border-radius: 5px; width: 550px; 
		margin:40px auto 0px auto; font-size:12px; text-align: left; word-break:normal;
		background: #fefcea; 
		background: -moz-linear-gradient(top,  #fefcea 0%, #efe5a2 100%);
		background: -ms-linear-gradient(top,  #fefcea 0%,#efe5a2 100%);
		background: linear-gradient(to bottom,  #fefcea 0%,#efe5a2 100%);
	}
	input#dup-bulk-action-all {margin:0px;padding:0px 0px 0px 5px;}
	button.dup-button-selected {border:1px solid #000 !important; background-color:#dfdfdf !important;}
	
	/* Table package details */
	table.dup-pack-table {word-break:break-all;}
	table.dup-pack-table th {white-space:nowrap !important;}
	table.dup-pack-table td.pack-name {text-overflow:ellipsis; white-space:nowrap}
	table.dup-pack-table input[name="delete_confirm"] {margin-left:15px}
	table.dup-pack-table td.fail {border-left: 4px solid #d54e21;}
	table.dup-pack-table td.pass {border-left: 4px solid #2ea2cc;}
	tr.dup-pack-info td {white-space:nowrap; padding:12px 30px 0px 7px;}
	tr.dup-pack-info td.get-btns {text-align:right; padding:3px 5px 6px 0px !important;}
	textarea.dup-pack-debug {width:98%; height:300px; font-size:11px; display:none}
	td.error-msg a {color:maroon}
	td.error-msg a i {color:maroon}
	td.error-msg span {display:inline-block; padding:7px 18px 0px 0px; color:maroon}
	
</style>

<form id="form-duplicator" method="post">
	
<?php if($statusCount >= 2)  :	?>
	<div style="font-size:14px; position: absolute; top:15px; right:25px">
		<a href="admin.php?page=duplicator-about"  style="color:maroon"><i><i class="fa fa-check-circle"></i> <?php _e("Help Support Duplicator", 'duplicator') ?></i> </a>
	</div>
<?php endif; ?>	

<!-- ====================
TOOL-BAR -->
<table id="dup-toolbar">
	<tr valign="top">
		<td style="white-space: nowrap">
			<select id="dup-pack-bulk-actions">
				<option value="-1" selected="selected"><?php _e("Bulk Actions", 'duplicator') ?></option>
				<option value="delete" title="<?php _e("Delete selected package(s)", 'duplicator') ?>"><?php _e("Delete", 'duplicator') ?></option>
			</select>
			<input type="button" id="dup-pack-bulk-apply" class="button action" value="<?php _e("Apply", 'duplicator') ?>" onclick="Duplicator.Pack.Delete()">
		</td>
		<td align="center" >
			<a href="?page=duplicator-tools" id="btn-logs-dialog" class="button"  title="<?php _e("Package Logs", 'duplicator') ?>..."><i class="fa fa-list-alt"></i>
		</td>
		<td>						
			<span><i class="fa fa-archive"></i> <?php _e("All Packages", 'duplicator'); ?></span>
			<a id="dup-pro-create-new"  href="?page=duplicator&tab=new1" class="add-new-h2"><?php _e("Create New", 'duplicator'); ?></a>
		</td>
	</tr>
</table>	


<?php if($totalElements == 0)  :	?>
	<!-- ====================
	NO-DATA MESSAGES-->
	<table class="widefat dup-pack-table">
		<thead><tr><th>&nbsp;</th></tr></thead>
		<tbody>
			<tr>
				<td>
				<div id='dup-list-alert-nodata'>
					<i class="fa fa-archive"></i> 
					<?php _e("No Packages Found.", 'duplicator'); ?><br/>
					<?php _e("Click the 'Create New' button to build a package.", 'duplicator'); ?>
					<div style="height:75px">&nbsp;</div>
				</div>
				</td>
			</tr>
		</tbody>
		<tfoot><tr><th>&nbsp;</th></tr></tfoot>
	</table>
<?php else : ?>	
	<!-- ====================
	LIST ALL PACKAGES -->
	<table class="widefat dup-pack-table">
		<thead>
			<tr>
				<th><input type="checkbox" id="dup-bulk-action-all"  title="<?php _e("Select all packages", 'duplicator') ?>" style="margin-left:15px" onclick="Duplicator.Pack.SetDeleteAll()" /></th>
				<th><?php _e("Created", 'duplicator') ?></th>
				<th><?php _e("Size", 'duplicator') ?></th>
				<th style="width:90%;"><?php _e("Name", 'duplicator') ?></th>
				<th style="text-align:center;" colspan="2">
					<?php _e("Package",  'duplicator')?>
				</th>
			</tr>
		</thead>
		<?php
		$rowCount = 0;
		$totalSize = 0;
		$rows = $qryResult;
		foreach ($rows as $row) {
			$Package = unserialize($row['package']);
			
			if (is_object($Package)) {
				 $pack_name			= $Package->Name;
				 $pack_archive_size = $Package->Archive->Size;
				 $pack_storeurl		= $Package->StoreURL;
				 $pack_namehash	    = $Package->NameHash;		
			} else {
				 $pack_archive_size = 0;
				 $pack_storeurl		= 'unknown';
				 $pack_name			= 'unknown';
				 $pack_namehash	    = 'unknown';	
			}
			
			//Links
			$uniqueid  			= "{$row['name']}_{$row['hash']}";
			$packagepath 		= $pack_storeurl . "{$uniqueid}_archive.zip";
			$installerpath		= $pack_storeurl . "{$uniqueid}_installer.php";
			$installfilelink	= "{$installerpath}?get=1&file={$uniqueid}_installer.php";
			$css_alt		    = ($rowCount % 2 != 0) ? '' : 'alternate';
			?>

			<!-- COMPLETE -->
			<?php if ($row['status'] >= 100) : ?>
				<tr class="dup-pack-info <?php echo $css_alt ?>">
					<td class="pass"><input name="delete_confirm" type="checkbox" id="<?php echo $row['id'] ;?>" /></td>
					<td><?php echo date( "m-d-y G:i", strtotime($row['created']));?></td>
					<td><?php echo DUP_Util::ByteSize($pack_archive_size); ?></td>
					<td class='pack-name'><?php	echo  $pack_name ;?></td>
					<td class="get-btns">
						<button id="<?php echo "{$uniqueid}_installer.php" ?>" class="button no-select" onclick="Duplicator.Pack.DownloadFile('<?php echo $installfilelink; ?>', this); return false;">
							<i class="fa fa-bolt"></i> <?php _e("Installer", 'duplicator') ?>
						</button> 
						<button id="<?php echo "{$uniqueid}_archive.zip" ?>" class="button no-select" onclick="Duplicator.Pack.DownloadFile('<?php echo $packagepath; ?>', this); return false;">
							<i class="fa fa-file-archive-o"></i> <?php _e("Archive", 'duplicator') ?>
						</button>
						<button type="button" class="button no-select" title="<?php _e("Package Details", 'duplicator') ?>" onclick="Duplicator.Pack.OpenPackageDetails(<?php echo "{$row['id']}"; ?>);">
							<i class="fa fa-archive" ></i> 
						</button>
					</td>
				</tr>
				
			<!-- NOT COMPLETE -->				
			<?php else : ?>	
			
				<?php
					$size = 0;
					$tmpSearch = glob(DUPLICATOR_SSDIR_PATH_TMP . "/{$pack_namehash}_*");
					if (is_array($tmpSearch)) {
						$result = array_map('filesize', $tmpSearch);
						$size = array_sum($result);
					}
					$pack_archive_size = $size;
					$error_url = "?page=duplicator&action=detail&tab=detail&id={$row['id']}";
				?>
				<tr class="dup-pack-info  <?php echo $css_alt ?>">
					<td class="fail"><input name="delete_confirm" type="checkbox" id="<?php echo $row['id'] ;?>" /></td>
					<td><?php echo date( "m-d-y G:i", strtotime($row['created']));?></td>
					<td><?php echo DUP_Util::ByteSize($size); ?></td>
					<td class='pack-name'><?php echo $pack_name ;?></td>
					<td class="get-btns error-msg" colspan="2">		
						<span>
							<i class="fa fa-exclamation-triangle"></i>
							<a href="<?php echo $error_url; ?>"><?php _e("Error Processing", 'duplicator') ?></a>
						</span>			
						<a class="button no-select" title="<?php _e("Package Details", 'duplicator') ?>" href="<?php echo $error_url; ?>">
							<i class="fa fa-archive"></i> 
						</a>						
					</td>
				</tr>
			<?php endif; ?>
			<?php
			$totalSize = $totalSize + $pack_archive_size;
			$rowCount++;
		}
	?>
	<tfoot>
		<tr>
			<th colspan="11" style='text-align:right; font-size:12px'>						
				<?php echo _e("Packages", 'duplicator') . ': ' . $totalElements; ?> |
				<?php echo _e("Total Size", 'duplicator') . ': ' . DUP_Util::ByteSize($totalSize); ?> 
			</th>
		</tr>
	</tfoot>
	</table>
<?php endif; ?>	
</form>

<script type="text/javascript">
jQuery(document).ready(function($) 
{
	/*	Removes all selected package sets 
	 *	@param event	To prevent bubbling */
	Duplicator.Pack.Delete = function (event) 
	{
		var arr = new Array;
		var count = 0;
		
		if ($("#dup-pack-bulk-actions").val() != "delete") {
			alert("<?php _e('Please select an action from the bulk action drop down menu to perform a specific action.', 'duplicator') ?>");
			return;
		}
		$("input[name=delete_confirm]").each(function() {
			 if (this.checked) { arr[count++] = this.id; }
		});
		var list = arr.join(',');
		if (list.length == 0) {
			alert("<?php _e('Please select at least one package to delete.', 'duplicator') ?>");
			return;
		}
		
		if (confirm("<?php _e('Are you sure, you want to delete the selected package(s)?', 'duplicator') ?>"))
		{
			$.ajax({
				type: "POST",
				url: ajaxurl,
				dataType: "json",
				data: {action : 'duplicator_package_delete', duplicator_delid : list, nonce: '<?php echo $ajax_nonce; ?>' },
				success: function(data) { 
					Duplicator.ReloadWindow(data); 
				}
			});
		} 
		if (event)
			event.preventDefault(); 
	};
	
	/* Toogles the Bulk Action Check boxes */
	Duplicator.Pack.SetDeleteAll = function() 
	{
		var state = $('input#dup-bulk-action-all').is(':checked') ? 1 : 0;
		$("input[name=delete_confirm]").each(function() {
			 this.checked = (state) ? true : false;
		});
	}
	
	/*	Opens detail screen */
	Duplicator.Pack.OpenPackageDetails = function (package_id) 
	{
		window.location.href = '?page=duplicator&action=detail&tab=detail&id=' + package_id;
	}
	
});
</script>