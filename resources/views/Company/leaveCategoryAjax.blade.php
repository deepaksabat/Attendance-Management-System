<?php
if($allCategory){
foreach($allCategory as $key=>$category): ?>
<tr class="list" id="row_<?php echo $category->id ?>">
    <td><?php echo $key+1?></td>
    <td class="center"><?php echo $category->category?></td>
    <td class="center"><?php echo $category->category_num?></td>
    <td class="center">
        <a class="btn btn-danger" id="delete_<?php echo $category->id ?>" >
            <i class="icon-white icon-trash"></i>Delete</a>
    </td>
</tr>
<script type="text/javascript">
    $(document).ready(function() {
        $("#delete_<?php echo $category->id ?>").click(function(event) {
            event.preventDefault();
            var values = ' ';
            var chk = confirm("Are you sure to delete this?");
            if (chk)
            {
                $.ajax({
                    url: '{!! URL::to("company/delete-leave-category/$category->id") !!}',
                    type: "GET",
                    data: values,
                    cache: false,
                    success: function(data) {
                        if(data == 'true' ){
                            $("#row_<?php echo $category->id ?>").hide();
                            $.pnotify({
                                title: 'Success',
                                text: 'Leave Category Deleted',
                                type: 'success',
                                delay: 3000
                            });
                        }else{
                            $.pnotify({
                                title: 'ERROR',
                                text: data,
                                type: 'error',
                                delay: 3000
                            });
                        }
                    }
                });
            }
        });
    });
</script>
<?php endforeach;
}else{?>
<tr>
    <td>
        No data are availables
    </td>
    <td>
        No data are availables
    </td>
    <td>
        No data are availables
    </td>
    <td>
        No data are availables
    </td>
</tr>
<?php   }
?>