<div class="fixed-overlay fixed-overlay__modal">
    <div class="close_hover" onclick="KouziModal.cancelBtn();"></div>
    <div class="win_modal">
        <div class="modal_container">
            <div id="content_modal">

            </div>
            <div class="clearfix"></div>
            <div class="price-box">
                Стоимость: <span>0</span> руб.<br/>
                <small>за единицу товара</small>
            </div>
            <div class="action-block">
                <a class="btn btn-add" onclick="KouziModal.applayBtn();">Добавить</a>
                <a class="btn about" onclick="KouziModal.aboutBtn();">Закрыть</a>
                <a class="btn cancel" onclick="KouziModal.cancelBtn();">Отмена</a>
            </div>  
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<div id="ral_modal">
    <div class="close_hover" onclick="RalPicker.close();"></div>
    <div class="win_modal">
        <div class="modal_container">
            <div class="clearfix"></div>
        </div>
    </div>    
</div>

<div id="load_modal">
    <div class="win_modal">
        <div class="modal_container">
            <div class="info-applay">
                <h3>Заказ принят</h3>
                <p>Заказ принят в работу, наш сотрудник свяжется с вами в ближайшее рабочее время.</p>
                <a class="btn back">Вернуться</a>
            </div>
            <div class="load-box"></div>
            <div class="clearfix"></div>
        </div>
    </div>    
</div>

<div class="wrapper">
    <div class="catalog">
        <div class="title">
            <h3>Список товаров</h3>
            <p>Выберите интересующую вас продукцию</p>
        </div>
        <div id="catalog-article" class="items">
            <div class="item">
                <h4>КОУЗИ 250В</h4>
                <img src="image/k250.jpg">                
                <p>КОУЗИ 250Вт - прогреет площадь 5м2</p>
                <div class="price">5200 руб.</div>
                <div class="action-block">
                    <a class="btn btn-add">В корзину</a>
                    <a class="btn about">Подробнее</a>
                </div>
            </div>
            <div class="item">
                <h4>КОУЗИ 250В</h4>
                <img src="image/k250.jpg">                
                <p>КОУЗИ 250Вт - прогреет площадь 5м2</p>
                <div class="price">5200 руб.</div>
                <div class="action-block">
                    <a class="btn btn-add">В корзину</a>
                    <a class="btn about">Подробнее</a>
                </div>
            </div>    
            <div class="item">
                <h4>КОУЗИ 250В</h4>
                <img src="image/k250.jpg">                
                <p>КОУЗИ 250Вт - прогреет площадь 5м2</p>
                <div class="price">5200 руб.</div>
                <div class="action-block">
                    <a class="btn btn-add">В корзину</a>
                    <a class="btn about">Подробнее</a>
                </div>
            </div>             
            <div class="item">
                <h4>Терморегулятор</h4>
                <img src="image/term.jpg">                
                <p>контролирует заданную температуру в помещении</p>
                <div class="price">1500 руб.</div>
                <div class="action-block">
                    <a class="btn btn-add">В корзину</a>
                    <a class="btn about">Подробнее</a>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    
    <div id="article-list" class="lists">
        <div class="title">
            <h3>Ваш заказ <span></span></h3>
            <p>Список товаров в заказе</p>
        </div>        
        <div class="table">
            <div class="line bottom">
                <div class="col number">№</div>
                <div class="col name">Товар</div>
                <div class="col price">Цена за шт.</div>
                <div class="col count">Кол.</div>
                <div class="col total">Сумма</div>
                <div class="clearfix"></div>
            </div>   
            <div class="item-block">
                <div class="line">
                    <div class="col number">1.</div>
                    <div class="col name">КОУЗИ 450В М2</div>
                    <div class="col price">5200 руб.</div>
                    <div class="col count">2</div>
                    <div class="col total">10400 руб.</div>
                    <div class="col del">Х</div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <p class="list-clear">- Список пуст -</p>
            <div class="line top">                
                <div class="col total-line">Стоимость товаров в заказе:</div>
                <div id="total-price" class="col total">10400 руб.</div>                
                <div class="clearfix"></div>
            </div>                         
            <div class="line" id="delivery-info" style="display: none;">                
                <div class="col total-line">Стоимость доставки:</div>
                <div id="delivery-total" class="col total">500 руб.</div>                
                <div class="clearfix"></div>
            </div>                         
            <div class="line" id="all-info" style="display: none;">                
                <div class="col total-line">Итого:</div>
                <div id="total-all" class="col total">500 руб.</div>                
                <div class="clearfix"></div>
            </div>                         
            
        </div>
        <div class="action">            
            <a class="btn clear edit" onclick="KouziShop.nextStep(0);" style="display: none;">Изменить заказ</a>
        </div>
    </div>
    
    
    <div class="action-bar" id="action-1">
        <p>В заказе товаров на сумму: <span>0 руб.</span> (<a class="btn view" onclick="KouziShop.scrollList();">Просмотреть товары</a>)</p>                    
        <a class="btn sale" onclick="KouziShop.nextStep(1);">Оформить заказ</a>              
        <a class="btn clear" onclick="KouziList.clear();">Очистить корзину</a>
    </div>    
    
    <div class="order" style="display: none;">
        <div class="client-block">
            <h3>Укажите информацию о покупателе</h3>
            <ul class="client-select">
                <li class="active" onclick="KouziOrder.setClient('person',this);">Физ. лицо</li>
                <li onclick="KouziOrder.setClient('company',this);">Юр. лицо</li>
            </ul>
            <div class="box" id="person" >                
                <label for="lname">Фамилия<span>*</span></label><input id="lname" type="text" name="lname" value="" fm_check="y" fm_box="0"><p class="error-text lname-error">Обязательное поле</p><br>
                <label for="fname">Имя<span>*</span></label><input id="fname" type="text" name="fname" value="" fm_check="y" fm_box="0"><p class="error-text fname-error">Обязательное поле</p><br>
                <label for="pname">Отчество<span>*</span></label><input id="pname" type="text" name="pname" value="" fm_check="y" fm_box="0"><p class="error-text pname-error">Обязательное поле</p><br>
                <label for="phone">Телефон<span>*</span></label><input id="phone" type="text" name="phone" value="" fm_check="y" fm_box="0"><p class="error-text phone-error">Обязательное поле (Пример телефона: 73512009050)</p><br>
                <label for="email">E-mail</label><input id="email" type="text" name="email" value="" fm_check="n" fm_box="0"><p class="error-text email-error">Неверный адрес</p><br>
                <p><span>*</span> - обязательные поля</p>
            </div>
            <div class="box" id="company" style="display: none;" >
                <label for="cname">Контактное лицо<span>*</span></label><input id="cname" type="text" name="cname" value="" fm_check="y" fm_box="1"><p class="error-text cname-error">Обязательное поле</p><br>
                <label for="companyname">Название компании<span>*</span></label><input id="companyname" type="text" name="companyname" value="" fm_check="y" fm_box="1"><p class="error-text companyname-error">Обязательное поле</p><br>
                <label for="inn">ИНН<span>*</span></label><input id="inn" type="text" name="inn" value="" fm_check="y" fm_box="1"><p class="error-text inn-error">Обязательное поле</p><br>
                <label for="cphone">Телефон<span>*</span></label><input id="cphone" type="text" name="cphone" value="" fm_check="y" fm_box="1"><p class="error-text cphone-error">Обязательное поле (Пример телефона: 73512009050)</p><br>
                <label for="cemail">E-mail<span>*</span></label><input id="cemail" type="text" name="cemail" value="" fm_check="y" fm_box="1"><p class="error-text cemail-error">Обязательное поле</p><br>                                
                <p><span>*</span> - обязательные поля</p>
            </div>            
        </div>
        <div class="logistic-block">
            <h3>Способ получения</h3>            
            <label for="city">Выберите город<span>*</span></label>            
           <!-- 
            <input id="city" type="text" name="city" value="" fm_check="y">
           -->
           
           <select data-placeholder="Выберите ваш город..." class="chosen-select" style="width:350px;" id="city" name="city" >
            <!--<option value="-">Другой город</option>-->
           </select>
            
            <p><span>*</span> - обязательные поля</p>
            <input type="radio" id="logistic-1" name="logistic" value="0" checked><label for="logistic-1">Самовывоз со склада <span></span></label><br>
            <input type="radio" id="logistic-2" name="logistic" value="1" ><label for="logistic-2">Доставка курьером <span>(+ 300 руб.)</span></label><br>
            <div class="address-block" style="display: none;">
            <label for="address">Адрес доставки</label><input id="address" type="text" name="address" value=""><br>
            </div>
        </div>
        <div class="payment-block">    
            <h3>Способ оплаты</h3>
            <input type="radio" id="payment-1" name="payment" value="0" checked><label for="payment-1">Предоплата
            <p>Оплачивается полная стоимость заказа и доставки</p></label><br>
            <input type="radio" id="payment-2" name="payment" value="1" ><label for="payment-2">Оплата заказа при получение            
            <p>Оплачивается только стоимость доставки, товар оплачивается в момент получения.</p></label><br>
        </div>
        <div class="other-block">  
            <label>Комментарий к заказу</label>
            <textarea id="comment" name="comment" rows="5"  tabindex="18" placeholder="Какую дополнительную информацию вы хотите сообщить к заказу?"></textarea>
        </div>        
    </div>
    
    <div class="action-bar" id="action-2" style="display: none;">
        <a class="btn clear" onclick="KouziShop.nextStep(0);">Изменить заказ</a>
        <a class="btn sale" onclick="KouziShop.nextStep(2);">Подтвердить заказ</a>              
    </div> 
    
    <div id="order-info" style="display: none;">
        <h3>Информация к заказу</h3>
        <h4>Доставка</h4>
        <p class="address-info">До <span>склада в г. Челябинск</span></p>
        <h4>Срок поставки</h4>
        <p class="time-info"><span>2 - 3</span> дн.</p>
        <h4>Получатель</h4>
        <p class="client-info">Иванов Иван Иванович</p>
        <p class="contact-info">Телефон <span>8 903090909</span></p>
        <div class="price-block">
            Сумма к оплате <span>100</span> руб. 
            <p class="post-pay" style="display: none;">Оплачивается только доставка, заказ в момент получения.</p>
        </div>            
    </div>

    <div class="action-bar" id="action-3" style="display: none;">
        <a class="btn clear" onclick="KouziShop.nextStep(1);">Изменить информацию к заказу</a>
        <a class="btn sale" id="pay-btn" onclick="KouziShop.pay();" >Оплатить</a>              
        <a class="btn sale" id="applay-btn" onclick="KouziShop.applay();">Оформить заказ</a>              
    </div>     
    <div id='payForm' style="display:none;">
    </div>
</div>