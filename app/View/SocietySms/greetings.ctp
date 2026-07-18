<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
            <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Greetings To Memebers</h6>
                    </div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="form-wrap">
                            <form id="member-greeting-form">
                                <div class="row">
                                    <div class="col-md-8" class="form-group">                                
                                            <textarea class="form-control" rows="3" id ="greeting-message-text" ></textarea> 
                                        </div>
                                        <div class="col-md-4">
                                            <a type="button" id="send-greeting-message"   class="btn btn-success btn-anim btns" ><i class="icon-rocket"></i><span class="btn-text">Send Message</span></a>                                                                                
                                        </div>
                                </div>                            
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#send-greeting-message').click(function(e){
        showLoader();
        var greetingMsg = $('#greeting-message-text').val();
        $.ajax({
            url : 'receipt_acknowledgment',
            type : 'post',
            data : {'greeting_msg': greetingMsg},
            success : function(msg){
                hideLoader();
                 alert(msg);
            }
            error : function(){
                hideLoader();
            }
        });
    })
</script>