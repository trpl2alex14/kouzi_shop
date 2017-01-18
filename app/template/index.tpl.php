<div class="fixed-overlay fixed-overlay__modal">
    <div class="win_modal">
        <div class="modal_container">
            <div id="content_modal">

            </div>
            <div class="action-block">
                <a class="btn btn-add" onclick="KouziModal.applayBtn();">Добавить</a>
                <a class="btn about" onclick="KouziModal.aboutBtn();">Закрыть</a>
                <a class="btn cancel" onclick="KouziModal.cancelBtn();">Отмена</a>
            </div>    
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
            <h3>Ваш заказ</h3>
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
                <div class="col total-line">Итого:</div>
                <div id="total-price" class="col total">10400 руб.</div>                
                <div class="clearfix"></div>
            </div>                         
        </div>
    </div>
    
    
    <div class="action-bar">
        <a class="btn clear" onclick="KouziList.clear();">Очистить корзину</a>
        <a class="btn sale" onclick="KouziShop.nextStep(1);">Оформить заказ</a>        
    </div>    
</div>