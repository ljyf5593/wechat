<form class="form-horizontal" role="form" action="<?php echo URL::site('/wechat/test');?>" method="post">
  <div class="form-group">
    <label for="url" class="col-sm-2 control-label">URL(/wechat/cgi/response/1)</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="url" name="url" placeholder="<?php echo URL::site('/wechat/cgi/response/1', 'http');?>">
    </div>
  </div>
  <div class="form-group">
    <label for="data" class="col-sm-2 control-label">data</label>
    <div class="col-sm-10">
      <textarea class="form-control" id="data" name="data" placeholder="data"></textarea>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">Send</button>
    </div>
  </div>
</form>