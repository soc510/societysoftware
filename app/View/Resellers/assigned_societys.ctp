<style>
    .incrwith{
        width:100% !important;
    }
</style>
<div class="container-fluid">
    <div class="row">
    <?php echo $this->Session->flash(); ?>
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Assigned Societies</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <form method="post">
                <div class="">
                    <div class="row">
                        <div class="col-md-6 assigned-society-box"> 
                            <label for="exampleInputPassword1" class="form-label">Society Name</label><br>
                            <select  class="form-control" name="society_id" id="" onchange="setAssignedYear(this.value)" style="width:100% !important">
                                <option>select Society</option>
                            <?php 
                            if(!empty($assignedSocietysList)) {
                                foreach($assignedSocietysList as $society){ ?>
                                    <option value="<?php echo $society['Society']['user_id']; ?>"><?= $society['Society']['society_name']; ?></option>
                            <?php 
                                }
                            }
                            ?>
                            
                                
                            </select>                        
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 assigned-society-box"> 
                            <label for="exampleInputPassword1" class="form-label">Assigned Year</label>
                            <select  class="form-control" name="finacial_year_id" id="assigned_finacial_year_id">
                                <option>Select Year</option>
                            </select>                         
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 assigned-society-box">                            
                            <button type="submit" class="btn btn-success btn-anim"><i class="icon-rocket"></i><span class="btn-text">Proceed</span></button>
                        </div>
                    </div>                    
                </div>
                </form>
            </div>	
        </div>
    </div>
</div>
<script>
    function setAssignedYear(id){
        $.ajax({
           url : 'getAssignedYear' ,
           data : {'soc_id' : id},
           success : function (data){
               let yearData = JSON.parse(data);
               $('#assigned_finacial_year_id').find('option').remove();
               $('#assigned_finacial_year_id').append("<option value='-1'>select Year</option>");
               if(yearData.length > 0) {
                   $.each(yearData,function(idx,obj){
                       $('#assigned_finacial_year_id').append("<option value="+obj.FinancialYearMaster.id+">"+obj.FinancialYearMaster.year+"</option>");
                   });
               }
           }
        });
    }
</script>
