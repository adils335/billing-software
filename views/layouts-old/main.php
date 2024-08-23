<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */


if (Yii::$app->controller->action->id === 'login') { 
/**
 * Do not use this code in your template. Remove it. 
 * Instead, use the code  $this->layout = '//main-login'; in your controller.
 */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {

    if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    }

    dmstr\web\AdminLteAsset::register($this);

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="hold-transition skin-blue sidebar-mini" onload="$('#loader').hide();">
    <?php $this->beginBody() ?>
    <div id="loader"><img src="/images/loading.gif" alt="Loading"/></div>
    <div class="wrapper">

        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>

        <?= $this->render(
            'left.php',
            ['directoryAsset' => $directoryAsset]
        )
        ?>

        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ) ?>

    </div>

    <!-- Erro Modal -->
  <div class="modal fade" id="error-modal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Error</h4>
        </div>
        <div class="modal-body">
          <p class="message">Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
   <!--Success Modal -->
  <div class="modal fade" id="success-modal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><i class="fa fa-check text-success" aria-hidden="true"></i> Success</h4>
        </div>
        <div class="modal-body">
          <p class="message">Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
<div id="popup-sidebar" class="popup-sidebar box-warning">
  <div class="box-header with-border">
        <a href="javascript:void(0)" class="closebtn" id="sidebar-close-btn">×</a>
  </div>
  <div class="box-body">
      
  </div>
</div>
    <?php $this->endBody() ?>
    <script>
  $(function(){
    window.initSelect2Loading = function(id, optVar){
	initS2Loading(id, optVar)
};
window.initSelect2DropStyle = function(id, kvClose, ev){
	initS2Change($('#'+id)) // CHANGE HERE
};
})
</script>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
