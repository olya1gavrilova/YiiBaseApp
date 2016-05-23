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
    <?php if($model):?>
    <p id='status_text'><?=$model->status_text;?> </p>
    <?php endif?>  

   
    <p>
        <?php if(!$model):?>
             <?= Html::submitButton('Поставить метку',['class' => 'btn btn-success btn-lg', 'id' => 'createmark']) ?>
        <?php else:?>
            <?=  $model->activeMark() ?
             Html::submitButton('Изменить', ['class' => 'btn btn-success btn-lg', 'id' => 'createmark'] ) : 
             Html::a('Активировать', ['activate', 'id' => $model->user_id], ['class' => 'btn btn-default btn-lg', 'id' => 'createmark']) ?>
            <?= Html::a('Удалить', ['delete', 'id' => $model->user_id], [
                'class' => 'btn btn-danger btn-xs col-sm-offset-2',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        
         <?php endif?>
           <div id='labelmark'  style='display: none; width:80%'> Напишите текст статуcа:<br />
            <input type="text" required  maxlength="255" id='createtext' >
            <?= Html::submitButton('Сохранить',['class' => 'btn btn-success btn-xs', 'id' => 'okmark', ]) ?><br />
            Отметьте кликом своё местоположение: <br />
            </div>
        
    </p>

   
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
   <script src="//api-maps.yandex.ru/2.0/?load=package.standard,package.geoObjects&lang=ru-RU" type="text/javascript"></script>

    <script src="//yandex.st/jquery/2.1.0/jquery.min.js" type="text/javascript"></script>
   
   
   Показывать:<br /> 

    <input type="radio" name="show" <?php if($sex==8):?>checked="checked"<?php endif?> value="9">Девушек<Br />
   <input type="radio" name="show" <?php if($sex==9):?>checked="checked"<?php endif?> value="8">Мужчин<Br/>
   <input type="radio" name="show" value="both">Девушей и мужчин<Br />
   
    <?=Html::submitButton('Отправить', ['class' => 'btn btn-success btn-lg', 'id' => 'submit', 'type'=>'submit']) ?>
  


  <script type="text/javascript">   
             ymaps.ready(init);
            var myMap;

            function checkIt() {
                 theGroup = document.theForm.gender;
                 for (i=0; i< theGroup.length; i++) {
                     if (theGroup[i].checked) {
                         alert("The value is " + theGroup[i].value);
                         break;
                     }
                }
            }

            function getmarks(){
                var cornercoord=myMap.getBounds();
                         var leftlat=cornercoord[0][0];
                         var leftlong=cornercoord[0][1];
                         var rightlat=cornercoord[1][0];
                         var rightlong=cornercoord[1][1];
                         
                         var sex = $('input[name=show]:checked').val();
                        
                         
                         $.ajax({
                                            type: "POST",
                                         dataType: 'json',
                                          data: {
                                        leftlat:leftlat,
                                        leftlong:leftlong,
                                        rightlat:rightlat,
                                        rightlong:rightlong,
                                        sex:sex,
                                        _csrf : '<?=Yii::$app->request->getCsrfToken()?>'
                                         },
                                            url: '<?php echo Yii::$app->request->baseUrl. '/mark/markupdate' ?>',
                                          success: function(marks){
                                            
                                            //если ещё нет меток, то у области меток нет краев
                                            if(!myMap.geoObjects.getBounds()){
                                                //просто перебираем все метки 
                                              $(marks).each(function(){
                                                  coord=[this.lat, this.long];
                                                        //и расставлем их на карту
                                                          myMap.geoObjects.add(new ymaps.Placemark(coord, {
                                                            balloonContent: this.status_text, 
                                                        }));                                                   
                                                     
                                                  
                                             });
                                              }
                                            else{
                                                //для каждой координаты метки из базы проверяем
                                                    $(marks).each(function(){
                                                        coord=[this.lat, this.long];
                                                         text=  this.status_text;
                                                         //перебираем все отисованные объекты на карте
                                                        myMap.geoObjects.each(function (geoObject) { 
                                                            abc= geoObject.geometry.getCoordinates();
                                                          //если есть метка с такими координатами
                                                            if(abc[0]==coord[0] && abc[0]==coord[0]){
                                                               //прерываем перебор
                                                                    return false;
                                                                }
                                                                //если перебор не прервался, выставляется метка
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
                .add(new ymaps.control.ScaleLine())
                .add(new ymaps.control.SearchControl()) //линейка масштаба
                //управление метками в зависимости от поведения
                myMap.events.add('boundschange', function (event) {
                    if (event.get('newZoom') != event.get('oldZoom') || event.get('newCenter') != event.get('oldCenter')) {
                        getmarks();
                    }
                });
                //расставляем метки после загрузки карты
                
                getmarks();

                 myMap.events.add('click', function (e) {
                  
                        var coords = e.get('coordPosition');
                        var lat=coords[0];
                         var long=coords[1];
                         var marktext= $('#createtext').val();
                         
     
                  });
               
            }
        
        $('#submit').click(function(){
           myMap.geoObjects.each(function(context) {
                       myMap.geoObjects.remove(context);
                    });
           
            getmarks();

        });
            


         $('#createmark').click(function(event){
            var marknew=false;


                   $('#labelmark').show();
                   //удаляем все объекты
                   myMap.geoObjects.each(function(context) {
                       myMap.geoObjects.remove(context);
                    });
                   
                   myMap.events.add('click', function (e) {

                    if(marknew==false){;   
                        //удаляем все объекты
                         myMap.geoObjects.each(function(context) {
                           myMap.geoObjects.remove(context);
                        });
                        //получаем координаты клика
                        var coords = e.get('coordPosition');
                        
                         
                         //создаем метку
                         myPlacemark=new ymaps.Placemark(coords, {
                                                        balloonContent:'marktext',

                                                    },
                                                    {draggable: true} );
                         //ставим метку

                         myMap.geoObjects.add(myPlacemark);

                         
                         

                         $('#okmark').click(function(){
                            //присваиваем координаты того места, где в итоге оказалась метка
                            newcoord= myPlacemark.geometry.getCoordinates();
                             var lat=newcoord[0];
                             var long=newcoord[1];
                             var marktext="no status"; 
                             if($('#createtext').val()!=''){
                                marktext=$('#createtext').val();
                             }
                         
                                $.ajax({
                                        type: "POST",
                                        dataType: 'json',
                                        data: {

                                        lat: lat,
                                        long: long,
                                        marktext: marktext,
                                        _csrf : '<?=Yii::$app->request->getCsrfToken()?>'
                                         },
                                            url: '<?php echo Yii::$app->request->baseUrl. '/mark/markupdate' ?>',
                                          success: function(model){
                                            myMap.geoObjects.each(function(context) {
                                               myMap.geoObjects.remove(context);
                                            });  
                                             $('#labelmark').hide(); 
                                             $('#status_text').html(marktext);     
                                            marknew=true;
                                            getmarks();

                                         },
                                  });
                           
                            

                         });}


                  }); 
            }); 


   </script>
    
 <div id="map" style="width:600px; height:600px"></div>
     

</div>