<style>
    #service-list tbody tr{
        cursor: pointer;
    }
</style>
<div class="py-3">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h5 class="card-title">Book an Appointment</h5>
            <div class="card-tools">
                <button class="btn-primary" type="button" onclick="location.replace(document.referrer)"><i class="fa fa-angle-left"></i> Back</button>
            </div>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                <form action="" id="appointment-form">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fullname" class="control-label">Fullname</label>
                                <input type="text" pattern="[a-zA-Z'-'\s]*" name="fullname" id="fullname" class="form-control form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contact" class="control-label">Contact #</label>
                                <input type="text" name="contact" pattern = "[0-9]{1}[0-9]{9}" id="contact" class="form-control form-control" required
                                 title="contact with 0-9 and remaing 9 digit with 0-9">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="control-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control form-control" required>
                                
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date" class="control-label">Schedule Date</label>
                                <input type="date" name="schedule" id="date" class="form-control form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <table class="table table-striped table-hover table-bordered" id="service-list">
                            <colgroup>
                                <col width="10%">
                                <col width="45%">
                                <col width="45%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th class=""></th>
                                    <th class="text-center px-1 py-1">Service</th>
                                    <th class="text-center px-1 py-1">Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                                $service = $conn->query("SELECT * FROM `service_list` where `status` = 1 order by `name` asc");
                                while($row = $service->fetch_assoc()):
                            ?>
                            <tr>
                                <td class="text-center px-1 py-1">
                                    <div class="form-check">
                                        <input class="form-check-input service_id" name="service_id[<?= $row['id'] ?>]" value="<?= $row['id'] ?>" type="checkbox">
                                        <input type="hidden" name="cost[<?= $row['id'] ?>]" value="<?= $row['cost'] ?>">
                                    </div>
                                </td>
                                <td class="px-1 py-1"><?= $row['name'] ?></td>
                                <td class="px-1 py-1 text-right"><?= number_format($row['cost'],2) ?></td>
                            </tr>
                            <?php endwhile; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="px-2 text-right" colspan="2">Total <input type="hidden" name="total" value="0"></th>
                                    <th class="text-right" id="total_amount">0.00</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="col-12 text-center">
                        <button class="btn btn-primary btn-lg rounded-0" type="submit">Book Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function calc(){
        var total = 0;
        $('.service_id:checked').each(function(){
            var cost = $('input[name="cost['+$(this).val()+']"]').val()
            total += parseFloat(cost)
        })
        $('#total_amount').text(parseFloat(total).toLocaleString('en-US',{style:"decimal", minimumFractionDigits: 2, maximumFractionDigits: 2}))
        $('[name="total"]').val(parseFloat(total))
    }
    $(function(){
        $('#service-list tbody tr').click(function(){
            if($(this).find('.service_id').is(":checked") == true){
                $(this).find('.service_id').prop("checked",false).trigger("change")
            }else{
                $(this).find('.service_id').prop("checked",true).trigger("change")
            }
            calc()
        })
        $('#appointment-form').submit(function(e){
            e.preventDefault();
            var _this = $(this)
            if($('.service_id:checked').length <=0){
                alert_toast("Please select atleast 1 service first.","warning")
                return false;
            }
            $('.pop-msg').remove()
            var el = $('<div>')
                el.addClass("pop-msg alert")
                el.hide()
            start_loader();
            $.ajax({
                url:_base_url_+"classes/Master.php?f=save_appointment",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error');
					end_loader();
				},
                success:function(resp){
                    if(resp.status == 'success'){
                        uni_modal("Success","message.php")
                        $('#uni_modal').on('hide.bs.modal',function(e){
                            location.reload()
                        })
                        $('#uni_modal').on('shown.bs.modal',function(e){
                            end_loader();
                        })
                    }else if(!!resp.msg){
                        el.addClass("alert-danger")
                        el.text(resp.msg)
                        _this.prepend(el)
                    }else{
                        el.addClass("alert-danger")
                        el.text("An error occurred due to unknown reason.")
                        _this.prepend(el)
                    }
                    el.show('slow')
                    end_loader();
                    $('html, body').animate({scrollTop:_this.offset().top},'fast')
                }
            })
        })
    })
</script>