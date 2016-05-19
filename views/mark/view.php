<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\web\UrlManager;


/* @var $this yii\web\View */
/* @var $model app\models\Mark */

$this->title = $model->user->username;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mark-view">

    <h1><?= Html::encode($this->title) ?></h1>
    

   
    <p>
        <?php if(!$model):?>
            <?= Html::a('Create Mark', ['create'], ['class' => 'btn btn-success btn-lg']) ?>
        <?php else:?>
            <?=$model->status_text?>
    
            <?=  $model->activeMark() ?
             Html::a('Метка активна', 'view', ['class' => 'btn btn-success btn-lg'] ) : 
             Html::a('Активировать', ['activate', 'id' => $model->user_id], ['class' => 'btn btn-default btn-lg']) ?>
            <?= Html::a('Обновить', ['update', 'id' => $model->user_id], ['class' => 'btn btn-primary btn-xs col-sm-offset-2']) ?>
            <?= Html::a('Удалить', ['delete', 'id' => $model->user_id], [
                'class' => 'btn btn-danger btn-xs',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        
         <?php endif?>
    </p>

   
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
   <script src="//api-maps.yandex.ru/2.0/?load=package.standard,package.geoObjects&lang=ru-RU" type="text/javascript"></script>

    <script src="//yandex.st/jquery/2.1.0/jquery.min.js" type="text/javascript"></script>
   
   

  <script type="text/javascript">   
             ymaps.ready(init);
            var myMap;

            function getmarks(){
                var cornercoord=myMap.getBounds();
                         var leftlat=cornercoord[0][0];
                         var leftlong=cornercoord[0][1];
                         var rightlat=cornercoord[1][0];
                         var rightlong=cornercoord[1][1];

                         
                         $.ajax({
                                            type: "POST",
                                         dataType: 'json',
                                          data: {
                                        leftlat:leftlat,
                                        leftlong:leftlong,
                                        rightlat:rightlat,
                                        rightlong:rightlong,
                                        _csrf : '<?=Yii::$app->request->getCsrfToken()?>'
                                         },
                                            url: '<?php echo Yii::$app->request->baseUrl. '/mark/markupdate' ?>',
                                          success: function(marks){
                                            
                                            if(!myMap.geoObjects.getBounds()){
                                              $(marks).each(function(){
                                                  coord=[this.lat, this.long];
                                                        
                                                          myMap.geoObjects.add(new ymaps.Placemark(coord, {
                                                            balloonContent: this.status_text, 

                                                        }));                                                   
                                                     
                                                  
                                             });
                                              }
                                            else{
                                                    $(marks).each(function(){
                                                        coord=[this.lat, this.long];
                                                         text=  this.status_text;
                                                        myMap.geoObjects.each(function (geoObject) { 

                                                            abc= geoObject.geometry.getCoordinates();
                                                          
                                                            if(abc[0]==coord[0] && abc[0]==coord[0]){
                                                               
                                                                    return false;
                                                                }
                                                                myMap.geoObjects.add(new ymaps.Placemark(coord, {
                                                                        balloonContent:  text, 
                                                                    }));
                                                        });
                                                        });

                                                  }

                                         },
                               });
                }

                                                /**/
            function init () {
                myMap = new ymaps.Map("map", {
                    center: [59.95, 30.3061], // СПБ
                    zoom: 14
                }, {
                    balloonMaxWidth: 200
                });

                myMap.behaviors.enable('scrollZoom'); //управление зумом по скроллу
                myMap.controls.add('typeSelector')      //типы карты          
                .add('smallZoomControl', { right: 5, top: 75 }) //управление зумом с координатами
                .add('mapTools') //набор кнопок
                .add(new ymaps.control.ScaleLine()) //линейка масштаба

                myMap.events.add('boundschange', function (event) {
                    if (event.get('newZoom') != event.get('oldZoom') || event.get('newCenter') != event.get('oldCenter')) {
                        getmarks();
                    }

                });
                
                getmarks();
               
                
                myMap.events.add('click', function (e) {
                    if (!myMap.balloon.isOpen()) {
                        var coords = e.get('coordPosition');
                        var lat=coords[0];
                         var long=coords[1];

                         $.ajax({
                                            type: "POST",
                                         dataType: 'json',
                                          data: {
                                        lat: lat,
                                        long: long,
                                        _csrf : '<?=Yii::$app->request->getCsrfToken()?>'
                                         },
                                            url: '<?php echo Yii::$app->request->baseUrl. '/mark/markupdate' ?>',
                                          success: function(model){
                                            
                                            myMap.geoObjects.add(new ymaps.Placemark(coords, {
                                                        balloonContent: "Я здесь", 
                                                    }));
                                            console.log(myMap.geoObjects);
                                         },
                                  });
                          
                         
                    }
                    else {
                        myMap.balloon.close();
                    }
                });

            }
   </script>
    
 <div id="map" style="width:600px; height:600px"></div>
  


        
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'user_id',
            'lat',
            'long',
            'status_text',
            // 'get_date',

         //   ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

   

</div>
