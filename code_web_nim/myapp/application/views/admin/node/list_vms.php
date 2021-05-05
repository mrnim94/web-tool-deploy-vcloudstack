<?php
foreach ($list_vms->data as $vm)
{
?>
<tr>
  <td class="text-center"><?php echo $vm->vmid; ?></td>
  <td><?php echo $vm->name; ?></td>
  <td><?php echo $vm->status; ?></td>
  <td class="td-actions text-right">
    <button type="button" rel="tooltip" class="btn btn-info">
      <i class="material-icons console_vm">aspect_ratio</i>
    </button>
  </td>
</tr>
<?php
}
?>